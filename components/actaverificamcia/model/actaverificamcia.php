<?php
require_once(CLASSES_PATH.'BDControlador.php');

class actaverModelo extends BDControlador {
  function actaverModelo() {
    parent :: Manejador_BD();
  }
	
  function datosActa($arreglo) {
    /*$db = $_SESSION['conexion'];
    
    $query = "SELECT * FROM do_asignados
              WHERE do_asignado = $arreglo[orden]"
    
    $db->query($query);
    return $db->getArray();*/
  }
}
?>