<?php
require_once(CLASSES_PATH.'BDControlador.php');

class SizfraModelo extends BDControlador {
  function SizfraModelo() {
    parent :: Manejador_BD();
  }
	
    function listadoSizfra($arreglo) {
        $db = $_SESSION['conexion'];
		//$sede = $_SESSION['sede'];
                                                     
        if(!empty($arreglo['nitfe']))
        {
            //echo "<script language='javascript'> alert('Nit '+'$arreglo[nitfe]'); </script>";
            $where = " ref.cliente=".$arreglo['nitfe'];
            //echo "<script language='javascript'> alert('$where'); </script>";
        }
                                                                             
        $query   = "SELECT	distinct ref.codigo_ref, ref.ref_prove, ref.nombre, ";        
		$query  .= "CASE ";
		$query  .= "WHEN ((doa.tipo_operacion = 3 OR doa.tipo_operacion = 10 OR doa.tipo_operacion = 24 OR doa.tipo_operacion = 30 OR doa.tipo_operacion = 31) ";
		$query  .= "	AND ";
		$query  .= "	(ref.tipo = 15 OR ref.tipo = 20 OR ref.tipo = 25)) THEN 'N' "; 
		$query  .= "WHEN 	((doa.tipo_operacion = 1 OR doa.tipo_operacion = 2) "; 
		$query  .= "	AND ";
		$query  .= "	(ref.tipo = 30 OR ref.tipo = 50)) THEN 'Z' ";
		$query  .= "WHEN 	((doa.tipo_operacion <> 3 AND doa.tipo_operacion <> 10 AND doa.tipo_operacion <> 24 AND doa.tipo_operacion <> 30 ";
		$query  .= "		AND doa.tipo_operacion <> 31 AND doa.tipo_operacion <> 1 AND doa.tipo_operacion <> 2) ";
		$query  .= "	AND (ref.tipo = 15 OR ref.tipo = 25 OR ref.tipo = 60)) THEN 'E' ";
		$query  .= "ELSE    'K' ";
		$query  .= "END AS naturaleza, ";		
        $query  .= "		ref.grupo_item, um.codigo, 1 AS factor, 'N' AS factor_variable, ";
        $query  .= "		CASE ref.tipo WHEN 30 THEN 'S' ELSE 'N' END AS fabricado_zf, ";
        $query  .= "        ie.referencia ";
        $query  .= "FROM	referencias ref ";	
        $query  .= "		LEFT JOIN unidades_medida um ON ref.unidad_venta = um.id ";
        $query  .= "        LEFT JOIN inventario_entradas ie ON ie.referencia = ref.codigo ";
        $query  .= "        LEFT JOIN inventario_movimientos im ON im.inventario_entrada = ie.codigo AND im.tipo_movimiento = 2 ";
		$query  .= "        INNER JOIN arribos arr ON arr.orden = ie.orden ";
		$query  .= "        INNER JOIN do_asignados doa ON doa.do_asignado = arr.orden ";
        $query  .= "WHERE ".$where;
    
        $db->query($query);                
        return $db->getArray();
  }    
}
?>