<?php
  require_once('Image/Barcode2.php');
  $bcObj = new Image_Barcode2();
  $serial = $_GET['serial'];
  $bcAltura = 25;
  $bc = $bcObj->draw($serial, "Code128", "png", false, $bcAltura);
  imagepng($bc);
?>