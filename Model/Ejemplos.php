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

// definir como constante la ruta donde se encuentran los ejemplos
define ('DIR_EJEMPLOS', \sowerphp\core\Configure::read('ide.examples'));

/**
 * Modelo para recuperar ejemplos desde el sistema de archivos
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2014-05-07
 */
class Model_Ejemplos
{

    /**
     * Método que obtiene la ruta de un ejemplo solicitado
     * @param language Lenguaje del ejemplo buscado
     * @param file Archivo de ejemplo solicitado
     * @return Contenido del archivo de ejemplo o null si no existe
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-05-07
     */
    public function get ($language, $file)
    {
        $path = DIR_EJEMPLOS.'/'.$language.'/'.$file;
        return file_exists($path) ? file_get_contents($path) : null;
    }

    /**
     * Método que obtiene todos los ejemplos que se encuentran bajo una ruta
     * @param dir Directorio de los ejemplos (en la primera llamada es vacio y se usa DIR_EJEMPLOS)
     * @return Arreglo con los ejemplos (en estructura de árbol)
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-05-07
     */
    public function getAll ($dir = DIR_EJEMPLOS)
    {
        $files = scandir($dir);
        $ejemplos = [];
        foreach ($files as &$file) {
            if ($file[0]=='.')
                continue;
            if (is_dir($dir.'/'.$file)) {
                $ejemplos[$file] = $this->getAll ($dir.'/'.$file);
            } else {
                $ejemplos[] = $file;
            }
        }
        return $ejemplos;
    }

}
