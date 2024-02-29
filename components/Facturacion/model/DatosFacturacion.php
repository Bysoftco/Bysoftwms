<?php
require_once(CLASSES_PATH.'BDControlador.php');

class FacturacionModelo extends BDControlador{
	function ClientesModelo(){
		parent :: Manejador_BD();
	}
	
	function listarFacturas($arreglo){
		$db = $_SESSION['conexion'];
		
		$orden = " fm.fecha_factura DESC ";
		$buscar = "";
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])){
			$orden= " $arreglo[orden] $arreglo[id_orden]";
		}
		if(isset($arreglo['buscar']) && !empty($arreglo['buscar'])){
		}
		
		$query="
		 SELECT fm.codigo,
		 		fm.factura,
		 		fm.numero_oficial,
		 		fm.cliente,
		 		fm.documento_transporte,
		 		fm.serial,
		 		fm.total,
		 		fm.orden,
		 		fm.fecha_factura
		   FROM facturas_maestro fm
	   ORDER BY $orden";
		
		$db->query($query);
		$mostrar = 20;
		$retornar['paginacion']=$this->paginar($arreglo['pagina'],$db->countRows(),$mostrar);
		
		$limit= ' LIMIT '. ($arreglo['pagina'] -1) * $mostrar . ',' . $mostrar;
		$query.=$limit;
		$db->query($query);
		$retornar['datos']=$db->getArray();
		return $retornar;
	}
}
?>