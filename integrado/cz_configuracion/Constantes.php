<?php



//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

error_reporting(E_ERROR | E_WARNING | E_PARSE );

//error_reporting(E_ERROR);



define ('PATH' 			, './');

define ('CONFIG_DIR' 	, PATH . 'integrado/cz_configuracion/');

define ('PLANTILLAS' 	, PATH . 'integrado/cz_plantillas/');

define ('LIBRERIAS' 	, PATH . 'integrado/cz_librerias/');

define ('IMAGENES' 		, PATH . 'integrado/cz_imagenes/');

define ('LOGERRORES'	, PATH  . 'integrado/cz_librerias/log4php');

define ('CONFIG_FILE' 	, PATH . 'integrado/cz_configuracion/config.ini');

define ('DECIMALES' 	, 2);

define ('IVA' 	, 16);

define ('FECHA' 	, date('Y/m/d'));

define ('FECHAMAILS' 	, date('Y-m-d'));











?>