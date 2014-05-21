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
 * @file core.php
 * Configuración del módulo
 */

// ruta donde se encuentran los ejemplos
\sowerphp\core\Configure::write(
    'ide.examples',
    dirname(dirname(__FILE__)).'/webroot/archivos/ejemplos'
);

// definición de lenguajes soportado por el IDE y como proceder con cada uno
// de ellos
\sowerphp\core\Configure::write('ide.languages', [
    'c' => [
        'name' => 'C',
        'mode' => 'c_cpp',
        'in'   => ['name'=>'main', 'ext'=>'c'],
        'out'  => ['name'=>'main'],
        'cmd'  => [
            'gcc -Wall -ansi -pedantic -ggdb :in -o :out',
            './:out',
            'valgrind --leak-check=full --track-origins=yes ./:out 2>&1 | grep -A 100 "HEAP SUMMARY"'
        ],
        'rc'   => [
            139 => ['gdb --quiet --batch -ex "run" ./:out'],
        ]
    ],
    'cpp' => [
        'name' => 'C++',
        'mode' => 'c_cpp',
        'in'   => ['name'=>'main', 'ext'=>'cpp'],
        'out'  => ['name'=>'main'],
        'cmd'  => [
            'g++ -Wall -ansi -pedantic :in -o :out',
            './:out'
        ],
    ],
    'python3' => [
        'name' => 'Python 3',
        'mode' => 'python',
        'in'  => ['name'=>'main', 'ext'=>'py'],
        'cmd'  => [
            'python3 :in'
        ],
    ],
    'php' => [
        'name' => 'PHP',
        'mode' => 'php',
        'in'  => ['name'=>'index', 'ext'=>'php'],
        'cmd'  => [
            'php :in'
        ],
    ],
    'perl' => [
        'name' => 'Perl',
        'mode' => 'perl',
        'in'  => ['name'=>'main', 'ext'=>'pl'],
        'cmd'  => [
            'perl :in'
        ],
    ],
    'java' => [
        'name' => 'Java',
        'mode' => 'java',
        'in'   => ['ext'=>'java'],
        'out'  => ['ext'=>'class'],
        'cmd'  => [
            'javac :in',
            'java :bin'
        ],
    ],
]);
