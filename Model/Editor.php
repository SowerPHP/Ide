<?php

/**
 * SowerPHP
 * Copyright (C) SowerPHP (http://sowerphp.org)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */

namespace website\Ide;

/**
 * Modelo para salvar y recuperar proyectos desde el sistema de archivos
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2014-05-07
 */
class Model_Editor
{

    /**
     * Método que guarda el proyecto en la sesión, esto evita que el proyecto
     * deba seguir existiendo en el sistema de archivos y solo "vivirá" por lo
     * que dure la sesión del usuario.
     * @param dir Directorio que se desea "salvar" en la sesión
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-05-07
     */
    public function save ($dir)
    {
        $project = [];
        $files = scandir($dir);
        foreach ($files as &$file) {
            if ($file[0]=='.')
                continue;
            $project[$file] = file_get_contents($dir.$file);
        }
        \sowerphp\core\Model_Datasource_Session::write('ide.project', $project);
    }

    /**
     * Método que abre el proyecto que estaba en la sesión, esto creará un
     * directorio temporal donde se colocarán los archivos del proyecto
     * @return Directorio donde se ha dejado el proyecto abierto o null en caso de no encotrar un proyecto
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-05-07
     */
    public function open ()
    {
        $project = \sowerphp\core\Model_Datasource_Session::read('ide.project');
        if ($project) {
            $dir = TMP.'/ide_'.\sowerphp\core\Utility_String::random(6).'/';
            mkdir($dir);
            foreach ($project as $file => $content) {
                file_put_contents ($dir.'/'.$file, $content);
            }
            return $dir;
        }
        return null;
    }

}
