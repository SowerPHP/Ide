<h1>IDE</h1>
<p>Si lo desea puede probar alguno de los <a href="<?=$_base?>/ide/ejemplos">ejemplos</a> ya existentes.</p>

<div id="editor"><?=$code?></div>

<script>
    var languages = <?=$languages?>;
    $().ready(function() {
        asignarLenguaje(languages, "<?=$language?>");
        changeFontSize(<?=$font_size?>);
    });
</script>

<?php
$f = new \sowerphp\general\View_Helper_Form ();
echo $f->begin(['id'=>'ide', 'action'=>$_base.'/ide/editor', 'onsubmit'=>'ejecutarCodigo()']);
echo $f->input(['type' => 'hidden', 'name' => 'code', 'value'=>' ']);
echo $f->input([
    'type'      => 'select',
    'name'      => 'language',
    'label'     => 'Lenguaje de programación',
    'options'   => array_merge([''=>'Seleccionar un lenguaje'], $options),
    'value'  => $language,
    'attr'      => 'onchange="asignarLenguaje(languages, this.value)"',
    'check'     => 'notempty',
]);
echo $f->input([
    'name'      => 'args',
    'label'     => 'Argumentos',
    'help'      => 'En caso de querer usar comillas, utilizar simples.<br />Ejemplo: \'esto es un solo argumento\''
]);
echo $f->input([
    'type'      => 'textarea',
    'name'      => 'input',
    'label'     => 'Archivo input.txt',
]);
echo $f->input([
    'type'      => 'textarea',
    'name'      => 'stdin',
    'label'     => 'Entrada estándar (teclado)',
]);
echo $f->input([
    'type'      => 'select',
    'name'      => 'font_size',
    'label'     => 'Tamaño de la fuente',
    'options'   => [12=>'12px', 14=>'14px', 18=>'18px', 22=>'22px'],
    'value'  => $font_size,
    'attr'      => 'onchange="changeFontSize(this.value)"',
]);
echo $f->end('Ejecutar el código');

// mostrar salida del programa
if ($_POST) {
    echo '<div style="text-align:right">Descargar proyecto: ';
    echo '<a href="',$_base,'/ide/editor/descargar/gz">.tar.gz</a>';
    echo ', <a href="',$_base,'/ide/editor/descargar/bz2">.tar.bz2</a>';
    echo ', <a href="',$_base,'/ide/editor/descargar/tar">.tar</a>';
    echo 'o <a href="',$_base,'/ide/editor/descargar/zip">.zip</a>';
    echo '</div>',"\n";
    echo '<pre>',htmlspecialchars($output),'</pre>',"\n";
}
