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

// variable para el editor
var editor;

// al cargar el documento preparar el editor ACE
$().ready(function() {
    ace.require("ace/ext/language_tools");
    editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true
    });
});

/**
 * Función que envía el código para ser ejecutado
 * @return false (en la práctica no importa, ya que no se debiera llegar a retornar)
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2014-05-07
 */
function ejecutarCodigo() {
    // chequeos básicos
    if (!Form.check())
        return false;
    // ejecutar POST
    __.post(
        document.getElementById("ide").getAttribute("action"),
        {
            "language" : document.getElementById("languageField").value,
            "code" : editor.getValue(),
            "input" : document.getElementById("inputField").value
        }
    );
    // retornar siempre falso
    return false;
}

/**
 * Función que asigna el modo/perfil de a cuerdo al lenguaje que se está
 * editando.
 * @param languages Objeto JSON con el perfil de todos los lenguajes
 * @param language El lenguaje que se está seleccionando
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2014-05-13
 */
function asignarLenguaje(languages, language) {
    try {
        editor.getSession().setMode("ace/mode/"+languages[language]["mode"]);
    } catch (error) {
    }
}
