<?php
  require_once("Image/Barcode/code128.php");
  $bc = new Image_Barcode_Code128('',2,4);
  $serial = $_GET['serial'];
  $bc->draw($serial, 'png', true, 'png');
?>