<?php
require_once(CLASSES_PATH.'BDControlador.php');

class SizfraModelo extends BDControlador {
  function SizfraModelo() {
    parent :: Manejador_BD();
  }
	
    function listadoSizfra($arreglo) {
    $db = $_SESSION['conexion'];
		$sede = $_SESSION['sede'];

    //Prepara la condición del filtro
    /*
    if(!empty($arreglo[nitfe])) $arreglo[where] .= " AND do_asignados.por_cuenta='$arreglo[nitfe]'";
    if(!empty($arreglo[fechadesdefe])) $arreglo[where] .= " AND im.fecha >= '$arreglo[fechadesdefe]'";
    if(!empty($arreglo[fechahastafe])) $arreglo[where] .= " AND im.fecha <= '$arreglo[fechahastafe]'";
    */
    $query = "SELECT codigo, nombre FROM grupo_items";    

    $db->query($query);
    return $db->getArray();
  }    
}
?>