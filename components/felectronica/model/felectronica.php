<?php
require_once(CLASSES_PATH.'BDControlador.php');

class felectronicaModelo extends BDControlador {
  function felectronicaModelo() {
    parent :: Manejador_BD();
  }

  function listadoFacturas($arreglo) {
    $db = $_SESSION['conexion'];
		$sede = $_SESSION['sede'];

		//Prepara la condición del filtro
    if(!empty($arreglo[nitfel])) $arreglo[where] .= " AND fm.cliente = '$arreglo[nitfel]'";
    if(!empty($arreglo[fechadesdefel])) $arreglo[where] .= " AND fm.fecha_factura >= '$arreglo[fechadesdefel]'";
    if(!empty($arreglo[fechahastafel])) $arreglo[where] .= " AND fm.fecha_factura <= '$arreglo[fechahastafel]'";
    if(!empty($arreglo[facturafiltrofel])) $arreglo[where] .= " AND fm.numero_oficial = '$arreglo[facturafiltrofel]'";
    if(!empty($arreglo[dofiltrofel])) $arreglo[where] .= " AND fm.orden = '$arreglo[dofiltrofel]'";
    if(!empty($arreglo[docfiltrofel])) $arreglo[where] .= " AND fm.documento_transporte = '$arreglo[docfiltrofel]'";
    
    $query = "SELECT fm.*, clientes.razon_social, clientes.numero_documento AS por_cuenta
              FROM facturas_maestro fm, clientes 
              WHERE fm.cliente = clientes.numero_documento
                AND (fm.cerrada = 1 OR fm.numero_oficial <> 0)
                AND fm.anulada <> 1
                AND fm.e_factura = 0
                AND fm.sede = '$sede' $arreglo[where]
              ORDER BY fm.numero_oficial ASC";
    
    $db->query($query);
    return $db->getArray();
  }
  
	function findCliente($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT numero_documento,razon_social,correo_electronico,v.nombre as nvendedor
						FROM clientes, vendedores v WHERE (razon_social LIKE '%$arreglo[q]%') AND (v.codigo = vendedor)";

		$db->query($query);
    return $db->getArray();
	}

  function timequery() {
    static $querytime_begin;
    list($usec, $sec) = explode(' ', microtime());

    if (!isset($querytime_begin)) {
      $querytime_begin = ((float) $usec + (float) $sec);
    } else {
      $querytime = (((float) $usec + (float) $sec)) - $querytime_begin;
      echo sprintf('<br />La consulta tardó %01.5f segundos.- <br />', $querytime);
    }
  }

	function infoFactura($arreglo){
		$db = $_SESSION['conexion'];

    $query = "SELECT fm.*, CAST(fm.e_fecha AS DATE) AS fecha_f, CAST(fm.e_fecha AS TIME) AS hora_f,
                cl.*, ci.nombre AS ciudad, dp.nombre AS dpto
              FROM facturas_maestro fm, clientes cl, ciudades ci, departamentos dp
              WHERE fm.cliente = cl.numero_documento
                AND (fm.numero_oficial = $arreglo[factura])
                AND (ci.codigo = cl.ciudad)
                AND (ci.departamento = dp.codigo)";
		
		$db->query($query);
		$info = $db->getArray();
		return $info[0];
	}
    
  function detalleFactura($factura) {
    $db = $_SESSION['conexion'];
    $sede = $_SESSION['sede'];
    
    $query = "SELECT fd.*, servicios.nombre AS nombre_servicio, servicios.cuenta, fd.iva,
                fd.rte_fuente, fd.rte_ica, fd.tipo, conceptos_tarifas.nombre AS concep_tarifa
              FROM facturas_detalle fd
                LEFT JOIN servicios ON fd.concepto = servicios.codigo AND servicios.sede = '$sede'
                LEFT JOIN conceptos_tarifas ON fd.tipo = conceptos_tarifas.codigo              
              WHERE fd.factura = '$factura'
                AND fd.sede = '$sede'";
	
    $db->query($query);
    return $db->getArray();
  }
  
  //Busca el Cliente Tercero
	function findClientet($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT razon_social	FROM clientes WHERE (numero_documento = '$arreglo')";
    
		$db->query($query);
    return $db->fetch();
	}
}
?>