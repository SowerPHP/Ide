SowerPHP: Módulo Ide
====================

Módulo para compilar diferentes lenguajes y ejecutarlos en el servidor a través
de la página web.

Características
---------------

-	Compilación de diferentes lenguajes en entorno GNU/Linux.
-	Concepto de perfiles de lenguajes, lo cual permite agregar un lenguaje
	nuevo fácilmente.
-	Editor de código fuente con opciones avanzadas gracias a
	[ACE](http://ace.c9.io)
-	Permite un archivo de entrada input.txt
-	Permite descargar el proyecto (código fuente, binarios y archivo de
	entrada).
-	Permite cargar código desde un directorio de ejemplos.

Instalación
-----------

Todos los comandos se asumen ejecutados desde el directorio del proyecto, si es
una instalación normal (no compartida) será el RUTA/A/SOWER/PHP/project.

1.	Habilitar extensión general (requerida por el módulo), en archivo
	*website/webroot/index.php* debe al menos estar habilitada la extensión
	general de la siguiente forma:

		$_EXTENSIONS = array('sowerphp/general');

	La extensión debe estar previamente instalada, si no lo está instalar en
	el directorio del proyecto con:

		$ git clone https://github.com/SowerPHP/extension-general.git \
			extensions/sowerphp/general

2.	Descargar módulo:

		$ cd website/Module
		$ git clone https://github.com/SowerPHP/Ide.git

3.	Habilitar módulo en *website/Config/core.php*:

		Module::uses ([
			'Ide'
		]);

Listo, ahora el módulo es accedible a través de la URL:

	example.com/ide
