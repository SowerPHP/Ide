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

// tiempo máximo de ejecución para los programas lanzados por el IDE
\sowerphp\core\Configure::write('ide.timeout', 60);

// definición de lenguajes soportado por el IDE y como proceder con cada uno
// de ellos
\sowerphp\core\Configure::write('ide.languages', [
    'c' => [
        'name' => 'C',
        'mode' => 'c_cpp',
        'in'   => ['name'=>'main', 'ext'=>'c'],
        'out'  => ['name'=>'main'],
        'cmd'  => [
            'gcc -Wall -ansi -pedantic -pthread -ggdb :in -o :out',
            './:out :args :stdin',
            'valgrind --leak-check=full --track-origins=yes ./:out :args :stdin 2>&1 | grep -A 1000 "HEAP SUMMARY"'
        ],
        'rc'   => [
            139 => ['gdb --quiet --batch -ex "run" ./:out :args :stdin'],
        ]
    ],
    'cpp' => [
        'name' => 'C++',
        'mode' => 'c_cpp',
        'in'   => ['name'=>'main', 'ext'=>'cpp'],
        'out'  => ['name'=>'main'],
        'cmd'  => [
            'g++ -Wall -std=c++11 -pedantic -pthread -ggdb :in -o :out',
            './:out :args :stdin',
            'valgrind --leak-check=full --track-origins=yes ./:out :args :stdin 2>&1 | grep -A 100 "HEAP SUMMARY"'
        ],
        'rc'   => [
            139 => ['gdb --quiet --batch -ex "run" ./:out :args :stdin'],
        ]
    ],
    'python2' => [
        'name' => 'Python 2.x',
        'mode' => 'python',
        'in'  => ['name'=>'main', 'ext'=>'py'],
        'cmd'  => [
            'python2 -tt :in :args :stdin'
        ],
    ],
    'python3' => [
        'name' => 'Python 3.x',
        'mode' => 'python',
        'in'  => ['name'=>'main', 'ext'=>'py'],
        'cmd'  => [
            'python3 :in :args :stdin'
        ],
    ],
    'php' => [
        'name' => 'PHP',
        'mode' => 'php',
        'in'  => ['name'=>'index', 'ext'=>'php'],
        'cmd'  => [
            'php :in :args :stdin'
        ],
    ],
    'perl' => [
        'name' => 'Perl',
        'mode' => 'perl',
        'in'  => ['name'=>'main', 'ext'=>'pl'],
        'cmd'  => [
            'perl :in :args :stdin'
        ],
    ],
    'java' => [
        'name' => 'Java',
        'mode' => 'java',
        'in'   => ['ext'=>'java'],
        'out'  => ['ext'=>'class'],
        'cmd'  => [
            'javac :in',
            'java :bin :args :stdin'
        ],
    ],
]);
