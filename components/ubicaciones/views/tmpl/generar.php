<?php
//Configuración Ruta de Acceso a los Paquetes PEAR
ini_set("include_path", '/home1/isamis/php:' . ini_get("include_path") );
require_once('Image/Barcode2.php');
$bcObj = new Image_Barcode2();
$ubicacion = $_GET['ubicacion'];
$bcAltura = 25;
$bc = $bcObj->draw($ubicacion, "Code128", "png", false, $bcAltura);
imagepng($bc);
?>