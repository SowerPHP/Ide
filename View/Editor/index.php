<h1>IDE</h1>
<p>Si lo desea puede probar alguno de los <a href="<?=$_base?>/ide/ejemplos">ejemplos</a> ya existentes.</p>

<div id="editor"><?=$code?></div>

<script>
    var languages = <?=$languages?>;
    $().ready(function() {
        asignarLenguaje(languages, "<?=$language?>");
    });
</script>

<?php
$f = new \sowerphp\general\View_Helper_Form ();
echo $f->begin(['id'=>'ide', 'action'=>$_base.'/ide/editor', 'onsubmit'=>'ejecutarCodigo()']);
echo $f->input([
    'type'      => 'select',
    'name'      => 'language',
    'label'     => 'Lenguaje de programación',
    'options'   => array_merge([''=>'Seleccionr un lenguaje'], $options),
    'selected'  => $language,
    'attr'      => 'onchange="asignarLenguaje(languages, this.value)"',
    'check'     => 'notempty',
]);
echo $f->input([
    'type'      => 'textarea',
    'name'      => 'input',
    'label'     => 'Archivo input.txt',
]);
echo $f->end('Ejecutar el código');

// mostrar salida del programa
if ($_POST) {
    echo '<div style="text-align:right"><a href="',$_base,'/ide/editor/descargar">Descargar proyecto</a></div>';
    debug($output);
}
