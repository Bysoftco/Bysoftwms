<?php
  require_once("Image/Barcode/code128.php");
  $bc = new Image_Barcode_Code128('',2,4);
  $ocupacion = $_GET['ocupacion'];
  $bc->draw($ocupacion, 'png', true, 'png');
?>