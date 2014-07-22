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

/**
 * Función que recorre recursivamente el arreglo ejemplos para generar los
 * enlaces (con estructura de directorio( hacia los ejemplos
 * @param ejemplos Arreglo con los ejemplos (obtenido desde el modelo)
 * @param path Ruta que se está revisando
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2014-07-22
 */
function ejemplos ($lang, $ejemplos, $path = '')
{
    foreach ($ejemplos as $key => &$val) {
        echo '<li>',"\n";
        if (is_numeric($key)) {
            echo '<a href="../../editor/',$lang,$path,'/',$val,'">',$val,'</a>',"\n";
        } else {
            echo '<span style="display:block;margin-bottom:0.5em"><strong>',$key,'</strong></span>',"\n";
            echo '<ul>',"\n";
            ejemplos ($lang, $val, $path.'/'.$key);
            echo '</ul>',"\n";
        }
        echo '</li>',"\n";
    }
}
