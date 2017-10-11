<?php
  require_once("Image/Barcode/code128.php");
  $bc = new Image_Barcode_Code128('',2,4);
  $ubicacion = $_GET['ubicacion'];
  $bc->draw($ubicacion, 'png', true, 'png');
?>