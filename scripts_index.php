<?php
session_start() ;
require_once('integrado/cz_configuracion/Constantes.php');
require_once(LIBRERIAS . "Funciones.php");

$sede = $_SESSION['sede'];

if(!isset( $_SESSION['datos_logueo']['usuario'] ) && $component!='login') {
    $mensaje="La cuenta estuvo  mucho tiempo inactiva por seguridad se cerrara.";
	echo "<script>
	alert('$mensaje');
	window.location='index.php';
	</script>";
	die();
}
   
init();
	
if(!empty($_GET['clase'])) {
  $kernel	=$_GET['clase'];

  $clase  = $kernel.'Logica';
  $metodo =$_GET['metodo'];

  require_once(LIBRERIAS .$kernel."Logica.php");
  $unKernel= new $clase();
		
  $unKernel->$metodo($_GET);
} else {
    if(!empty($_FILES)) {
      $info = explode("|",$_GET['folder']);
      $kernel = $info[2];
      $metodo = $info[3];
    } else {
      $kernel = $_POST['clase'];
      $metodo = $_POST['metodo'];
    }  
    $clase  = $kernel.'Logica';
    require_once(LIBRERIAS .$kernel."Logica.php");
    $unKernel= new $clase();
    $unKernel->$metodo($_POST);
}
?>