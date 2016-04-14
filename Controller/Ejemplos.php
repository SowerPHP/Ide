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
 * Controlador para mostrar los ejemplos disponibles en el IDE
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2014-05-07
 */
class Controller_Ejemplos extends \Controller_App
{

    /**
     * Método que muestra los lenguajes disponibles
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-07-22
     */
    public function index ()
    {
        $this->set('lenguajes', (new Model_Ejemplos())->getLenguages());
    }

    /**
     * Método que busca en el modelo los ejemplos de un lenguaje específico y
     * los asigna para que la vista los pueda mostrar.
     * @param
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-07-22
     */
    public function lenguaje ($lang)
    {
        $this->set([
            'lenguaje' => $lang,
            'ejemplos' => (new Model_Ejemplos())->getAllByLanguage($lang),
        ]);
    }

}
