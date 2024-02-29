<?php
require_once(CLASSES_PATH.'BDControlador.php');

class SizfraModelo extends BDControlador {
  function SizfraModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoSizfra($arreglo) {
    $db = $_SESSION['conexion'];
  	$sede = $_SESSION['sede'];

    $query = "SELECT codigo, nombre FROM grupo_items";    

    $db->query($query);
    return $db->getArray();
  }    
}
?>