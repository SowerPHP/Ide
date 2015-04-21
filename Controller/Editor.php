<?php

/**
 * SowerPHP: Minimalist Framework for PHP
 * Copyright (C) SowerPHP (http://sowerphp.org)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General GNU para obtener
 * una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/gpl.html>.
 */

namespace website\Ide;

/**
 * Controlador para el editor del IDE, contiene todas las acciones que el IDE
 * permite realizar.
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2014-05-21
 */
class Controller_Editor extends \Controller_App
{

    private $languages = null; ///< Perfiles de los lenguajes

    /**
     * Acción principal del editor de texto, permite mostrar el editor y recibe
     * la solicitud para compilación y ejecución.
     * Si se pasa un lenguaje se debe pasar un archivo, no tiene sentido un
     * lenguaje sin un archivo que cargar.
     * @param language Lenguaje que se desea compilar y ejecutar
     * @param file Archivo (del lenguaje elegido) que se desea compilar y ejecutar
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-05-28
     */
    public function index ($language = '', $file = '')
    {
        // cargar lenguajes
        $this->languages = \sowerphp\core\Configure::read('ide.languages');
        // si lenguaje no existe error
        if ($language!='' and !isset($this->languages[$language])) {
            \sowerphp\core\Model_Datasource_Session::message(
                'Lenguaje <em>'.$language.'</em> no es soportado por el IDE'
            );
            $this->redirect ('/ide/editor');
        }
        // crear variables para select de lenguajes
        ksort($this->languages);
        $options = [];
        foreach ($this->languages as $l => $o) {
            $options[$l] = $o['name'];
        }
        // asignar lenguaje si es que se recibió uno por POST
        if (isset($_POST['language'])) {
            $language = $_POST['language'];
        }
        // si se pasó un código por post se usa
        if (isset($_POST['code'])) {
            $code = $_POST['code'];
        }
        // si no se pasó código, pero se indicó un lenguaje y archivo se trata de cargar
        else if (!empty($language) and !empty($file)) {
            // buscar ruta del ejemplo solicitado
            $code = (new Model_Ejemplos())->get(
                $language,
                implode('/', array_slice(func_get_args(), 1))
            );
            // si el ejemplo solicitado no existe error
            if (!$code) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Ejemplo solicitado no existe'
                );
                $this->redirect ('/ide/editor');
            }
        }
        // si no se pasó código por POST ni se pide un ejemplo entonces iniciamos con un "archivo" vacio
        else {
            $code = '';
        }
        // se reemplazan < y > para evitar que se procesen como tags html
        $code = str_replace(['<', '>'], ['&lt;', '&gt;'], $code);
        // asignar tamaño de la fuente
        $font_size = !empty($_POST['font_size']) ? $_POST['font_size'] : 12;
        // asignar variables generales
        $this->set([
            '_header_extra' => [
                'css' => ['/ide/css/style.css'],
                'js' => [
                    '/ide/js/ace-builds/src-min-noconflict/ace.js',
                    '/ide/js/ace-builds/src-min-noconflict/ext-language_tools.js',
                    '/ide/js/ide.js',
                ]
            ],
            'code' => $code,
            'language' => $language,
            'languages' => json_encode($this->languages),
            'options' => $options,
            'font_size' => $font_size,
        ]);
        // si se envió el formulario se procesa el código fuente y se asigna
        // como salida lo que retorna el método $this->runCode()
        if ($_POST) {
            $this->set('output', $this->runCode(
                $_POST['language'],
                $this->options($_POST['language']),
                $_POST['code'],
                $_POST['input'],
                $_POST['args'],
                $_POST['stdin'],
                \sowerphp\core\Configure::read('ide.timeout')
            ));
        }
    }

    /**
     * Acción para descargar el proyecto
     * @param formato Formato en que se desea descargar el proyecto (gz, tar o zip)
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2015-04-21
     */
    public function descargar($formato = 'gz')
    {
        $dir = (new Model_Editor)->open();
        if ($dir) {
            \sowerphp\general\Utility_File::compress($dir, ['format'=>$formato, 'delete'=>true]);
        } else {
            \sowerphp\core\Model_Datasource_Session::message(
                'No existe proyecto guardado que se pueda descargar'
            );
            $this->redirect ('/ide/editor');
        }
    }

    /**
     * Método que determina "más" opciones para el perfil del lenguaje,
     * específicamente el nombre del archivo de entrada (in), salida (out) y
     * el binario (bin). Para cada uno de estos archivos se determina, si es
     * necesario, su valor.
     * Esta función fue originalmente necesaria por el lenguaje Java, ya que en
     * Java los nombres de los archivos:
     *  - in: depende del nombre de la clase pública (se debe determinar esto)
     *  - out: será el nombre de la clase pública y .class
     *  - bin: la forma de ejecutar es solo con el nombre de la clase pública, sin usar el .class
     * Lo anterior requirió programar este método, quizás otros lenguajes
     * también requieran "hacks" similares.
     * @param language Lenguaje al que se determinarán sus opciones
     * @return Arreglo con el perfil/opciones del lenguaje
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-05-07
     */
    private function options ($language)
    {
        // obtener opciones del lenguaje
        $options = $this->languages[$language];
        // si es código en java determinar :in y :out
        if ($_POST['language']=='java') {
            $lines = explode ("\n", $_POST['code']);
            foreach ($lines as &$line) {
                if (preg_match('/public class/i', $line)) {
                    $options['in']['name'] = $options['out']['name'] = explode(' ',str_replace('{', '', $line))[2];
                    $options['in']['file'] = $options['in']['name'].'.'.$options['in']['ext'];
                    $options['out']['file'] = $options['out']['name'].'.'.$options['out']['ext'];
                    $options['bin'] = $options['in']['name'];
                    break;
                }
            }
            unset($lines);
        }
        // en cualquier otro caso solo se concatena nombre y extension
        else {
            $options['in']['file'] = $options['in']['name'].'.'.$options['in']['ext'];
            if (isset($options['out'])) {
                $options['out']['file'] = $options['bin'] = $options['out']['name'].(!empty($options['out']['ext'])?('.'.$options['out']['ext']):'');
            }
        }
        // entregar opciones
        return $options;
    }

    /**
     * Método que ejecuta el código
     * @param language Lenguaje que se está compilando y ejecutando
     * @param options Opciones o perfil del lenguaje
     * @param code Contenido del código fuente que se debe compilar y ejecutar
     * @param input Contenido del archivo de entrada (input.txt) en caso que exista
     * @param args Argumentos que se pasarán al programa
     * @param stdin Entrada estándar (teclado) una entrada por línea
     * @return String con la salida/resultado del proceso de ejecución de cada uno de los comandos asociados con el lenguaje (en su perfil)
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-05-28
     */
    private function runCode ($language, $options, $code, $input = '', $args = '', $stdin = '', $timeout = 60)
    {
        // crear directorio temporal para el proyecto y guardar codigo fuente ya archivo de entrada (si existe)
        $dir = TMP.'/ide_'.\sowerphp\core\Utility_String::random(6).'/';
        mkdir ($dir);
        file_put_contents ($dir.$options['in']['file'], $code);
        if (!empty($input)) {
            file_put_contents ($dir.'input.txt', $input);
        }
        if (!empty($stdin)) {
            file_put_contents ($dir.'stdin.txt', $stdin);
        }
        // compilar y ejecutar, para esto se procesa cada uno de los comandos
        // definidos en el perfil del lenguaje, en caso que un comando falle
        // se detiene la ejecución de los comandos, entregando la salida hasta
        // el último comando que fallo. Esta forma de ejecución permite ejecutar
        // cada instrucción necesaria para la compilación y ejecución de un
        // programa por partes para poder ir chequeando posible estado de error
        $output = [];
        $rc = 0;
        foreach ($options['cmd'] as &$c) {
            // se crea el comando reemplazando los parámetros :in, :out y:bin
            // por los nombres reales de estos
            $cmd = str_replace(
                [':in', ':out', ':bin', ':args', ':stdin'],
                [
                    $options['in']['file'],
                    (!empty($options['out']['file'])?$options['out']['file']:''),
                    (!empty($options['bin'])?$options['bin']:''),
                    $args,
                    (!empty($stdin)?'< stdin.txt':'')
                ],
            $c);
            // se ejecuta el comando guardando su salida
            $output[] = 'Ejecutando:'."\n".'$ '.$cmd."\n";
            exec ('cd '.$dir.'; timeout --kill-after='.($timeout+5).' '.$timeout.' '.$cmd.' 2>&1', $output, $rc);
            // agregar línea en blanco al último comando ejecutado si no existe
            $lastLine = count($output)-1;
            $lastChar = strlen($output[$lastLine])-1;
            if ($lastLine>=0 && $lastChar >=0 && $output[$lastLine][$lastChar]!="\n") {
                $output[$lastLine] .= "\n";
            }
            // en caso de error del comando (RC>=1) se rompe el ciclo de
            // ejecución de comandos
            if ($rc) break;
        }
        // se revisa error para ejecutar acciones en caso de que hayan ocurrido
        if ($rc && isset($options['rc'][$rc])) {
            foreach ($options['rc'][$rc] as &$c) {
                // se crea el comando reemplazando los parámetros :in, :out y:bin
                // por los nombres reales de estos
                $cmd = str_replace(
                    [':in', ':out', ':bin', ':args', ':stdin'],
                    [
                        $options['in']['file'],
                        (!empty($options['out']['file'])?$options['out']['file']:''),
                        (!empty($options['bin'])?$options['bin']:''),
                        $args,
                        (!empty($stdin)?'< stdin.txt':'')
                    ],
                $c);
                // se ejecuta el comando guardando su salida
                $output[] = 'Ejecutando:'."\n".'$ '.$cmd."\n";
                exec ('cd '.$dir.'; timeout --kill-after='.($timeout+5).' '.$timeout.' '.$cmd.' 2>&1', $output, $rc);
            }
        }
        // generar salida del proyecto
        $output = implode("\n", $output);
        file_put_contents ($dir.'exec.log', $output);
        // guardar proyecto en la sesión por si el usuario desea bajarlo
        (new Model_Editor)->save($dir);
        // eliminar directorio temporal
        \sowerphp\general\Utility_File::rmdir($dir);
        // retornar la salida de la ejecición del código
        return $output;
    }

}
