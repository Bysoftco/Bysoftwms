<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class CosteardoModelo extends BDControlador {
  var $do_asignado;
  var $sede;
	var $fecha;
	var $por_cuenta;
  var $ingreso_total;
  var $gasto_total;
  
  var $table_name = "orden_costos";
  var $module_directory = 'costeardo';
  var $object_name = "CosteardoModelo";
	
  var $campos = array('do_asignado','sede','fecha','por_cuenta','ingreso_total','gasto_total');

  function CosteardoModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoCosteardo($arreglo) {
    $db = $_SESSION['conexion'];
		$sede = $_SESSION['sede'];
	
    $orden = " do_asignado DESC ";
    $buscar = "";
    if(isset($arreglo['orden']) && !empty($arreglo['orden'])) {
      $orden = " $arreglo[orden] $arreglo[id_orden]";
    }
    if(isset($arreglo['buscar']) && !empty($arreglo['buscar'])) {
      $buscar = " AND (do_asignados.do_asignado LIKE '%$arreglo[buscar]%' 
                  OR do_asignados.fecha LIKE '%$arreglo[buscar]%'
									OR do_asignados.doc_tte LIKE '%$arreglo[buscar]%'
									OR do_asignados.bodega LIKE '%$arreglo[buscar]%'
									OR razon_social LIKE '%$arreglo[buscar]%'									
                  OR do_asignados.ingreso_total LIKE '%$arreglo[buscar]%'
                  OR do_asignados.gasto_total LIKE '%$arreglo[buscar]%')";
    }
		
		$query = "SELECT do_asignados.do_asignado,
										do_asignados.fecha,
										do_asignados.doc_tte,
										do_asignados.bodega,
										do_bodegas.sigla,
										do_bodegas.nombre as bodega_nombre,
										por_cuenta,
										razon_social,
										do_asignados.ingreso_total,
										do_asignados.gasto_total
						FROM do_asignados,do_bodegas,clientes
						WHERE do_asignados.bodega = do_bodegas.codigo
							AND numero_documento = por_cuenta
							AND do_asignados.sede = '$sede'$buscar";
							
		//Prepara la condición de filtro
		if(!empty($arreglo[nitc])) $query .= " AND do_asignados.por_cuenta = '$arreglo[nitc]'";
		if(!empty($arreglo[fechadesdec])) $query .= " AND do_asignados.fecha >= '$arreglo[fechadesdec]'";
		if(!empty($arreglo[fechahastac])) $query .= " AND do_asignados.fecha <= '$arreglo[fechahastac]'";
		if(!empty($arreglo[doasignadoc])) $query .= " AND do_asignados.do_asignado = '$arreglo[doasignadoc]'";
		//Ordena el Listado
		$query .= " ORDER BY $orden";

    $db->query($query);
    $mostrar = 15;
    $retornar['paginacion'] = $this->paginar($arreglo['pagina'],$db->countRows(),$mostrar);
		
    $limit= ' LIMIT '. ($arreglo['pagina'] -1) * $mostrar . ',' . $mostrar;
    $query.=$limit;
    $db->query($query);
    $retornar['datos']=$db->getArray();
    return $retornar;
  }
  
 	function listadoDetallec($arreglo) {
		$db = $_SESSION['conexion'];

		$query = "SELECT ocd.numdetalle,ocd.do_asignado,ocd.codservicio,ocd.fecha,ocd.ingreso,ocd.gasto,ocd.sede,s.nombre AS nomservicio
              FROM orden_costos_detalle ocd, servicios s
							WHERE ocd.do_asignado = $arreglo[do_asignado] AND ocd.codservicio = s.codigo AND ocd.sede = s.sede";
		    
		$db->query($query);
		$retornar['datos'] = $db->getArray();
		return $retornar;
	}
  
  function actualizaCosteardo($arreglo) {
    $db = $_SESSION['conexion'];
    
    $query = "UPDATE do_asignados SET ingreso_total = $arreglo[totalingreso], gasto_total = $arreglo[totalgasto]
              WHERE do_asignado = $arreglo[do_asignado]";

    $db->query($query);
    return true;
  }  
	
  function datosCliente($arreglo) {
    $db = $_SESSION['conexion'];
    
    $query = "SELECT c.*, td.nombre AS tipo_identi, tc.nombre AS nombre_tipo_cliente, r.nombre AS nombre_regimen,
                ae.nombre AS nombre_actividad,
                CASE(c.tipo_facturacion) 
                  WHEN 'V' THEN 'Vencido'
                  WHEN 'A' THEN 'Anticipado'
                ELSE 'Error'
                END AS nombre_tipo_facturacion,
                mu.descripcion AS nombre_ciudad, mu.id_municipio AS ciudad,
                de.descripcion AS nombre_departamento, de.id_departamento AS departamento
              FROM clientes c, tipos_documentos td, tipos_clientes tc, regimenes r, actividades_economicas ae,
                municipios mu, departamentos de
              WHERE c.numero_documento = '$arreglo[documento]'
                AND c.ciudad = '$arreglo[ciudad]' AND c.departamento = '$arreglo[departamento]'
                AND c.tipo_documento = td.codigo
                AND c.tipo = tc.tipo
                AND c.actividad_economica = ae.codigo
                AND r.codigo = c.regimen
                AND c.ciudad = mu.id_municipio
                AND c.departamento = de.id_departamento";
             
    $db->query($query);
    return $db->getArray();
  }
  
  function findCliente($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT * FROM clientes WHERE (razon_social LIKE '%$arreglo[q]%')";
    
    $db->query($query);
    return $db->getArray();
  }

  function findDestino($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT * FROM clientes WHERE (correo_electronico LIKE '%$arreglo[q]%')";
    
    $db->query($query);
    return $db->getArray();
  }

  function findMaxDO($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT MAX(do_asignado) AS do FROM tracking WHERE (por_cuenta='$arreglo[por_cuenta]')";

    $db->query($query);
		return $db->getArray();
  }
}
?>