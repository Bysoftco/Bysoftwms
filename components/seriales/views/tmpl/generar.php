<?php
//Configuración Ruta de Acceso a los Paquetes PEAR
ini_set("include_path",'/home1/isamis/php:.:/opt/cpanel/ea-php56/root/usr/share/pear' . ini_get("include_path") );
require_once('Image/Barcode2.php');
$bcObj = new Image_Barcode2();
$serial = $_GET['serial'];
$bcAltura = 25;
$bc = $bcObj->draw($serial, "Code128", "png", false, $bcAltura);
imagepng($bc);
?>