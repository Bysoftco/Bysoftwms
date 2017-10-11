<?php 
require_once(CLASSES_PATH.'BDControlador.php');

class ClientesModelo extends BDControlador {
  var $tipo_documento;
  var $numero_documento;
  var $digito_verificacion;
  var $razon_social;
  var $tipo;
  var $direccion;
  var $telefonos_fijos;
  var $telefonos_celulares;
  var $telefonos_faxes;
  var $pagina_web;
  var $actividad_economica;
  var $correo_electronico;
  var $regimen;
  var $autoretenedor;
  var $vendedor;
  var $dias_para_facturar;
  var $tipo_facturacion;
  var $periodicidad;
  var $dias_gracia;
  var $cir170;

  var $table_name = "clientes";
  var $module_directory = 'clientes';
  var $object_name = "ClientesModelo";
	
  var $campos = array('tipo_documento', 'numero_documento', 'digito_verificacion', 'razon_social', 'tipo', 
    'direccion', 'telefonos_fijos', 'telefonos_celulares', 'telefonos_faxes', 'pagina_web', 'actividad_economica',
    'correo_electronico', 'regimen', 'autoretenedor', 'vendedor', 'dias_para_facturar', 'tipo_facturacion', 'periodicidad',
    'dias_gracia', 'cir170');

  function ClientesModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoClientes($arreglo) {
    $db = $_SESSION['conexion'];
	
    $orden = " c.numero_documento DESC ";
    $buscar = "";
    if(isset($arreglo['orden']) && !empty($arreglo['orden'])) {
      $orden = " $arreglo[orden] $arreglo[id_orden]";
    }
    if(isset($arreglo['buscar']) && !empty($arreglo['buscar'])) {
      $buscar = " AND (td.nombre LIKE '%$arreglo[buscar]%' 
                  OR c.numero_documento LIKE '%$arreglo[buscar]%'
                  OR c.digito_verificacion LIKE '%$arreglo[buscar]%'
                  OR c.razon_social LIKE '%$arreglo[buscar]%'
                  OR tc.nombre LIKE '%$arreglo[buscar]%'
                  OR ae.nombre LIKE '%$arreglo[buscar]%'
                  OR r.nombre LIKE '%$arreglo[buscar]%')";
    }
	
    $query = "SELECT c.tipo_documento,
				td.nombre AS tipo_doc,
				c.numero_documento,
				c.digito_verificacion,
				c.razon_social,
				c.tipo,
				tc.nombre AS nombre_tipo_cliente,
				c.actividad_economica,
				ae.nombre AS nombre_actividad_eco,
				c.regimen,
				r.nombre AS nombre_regimen
		      FROM clientes c,
				tipos_documentos td,
				tipos_cliente tc,
				actividades_economicas ae,
				regimenes r
                            WHERE c.inactivo=0
                            AND c.tipo_documento = td.codigo
			    AND c.tipo = tc.tipo
			    AND c.actividad_economica = ae.codigo
			    AND c.regimen = r.codigo
			    $buscar
	          ORDER BY $orden";
		
    $db->query($query);
    $mostrar = 20;
    $retornar['paginacion'] = $this->paginar($arreglo['pagina'],$db->countRows(),$mostrar);
	
    $limit= ' LIMIT '. ($arreglo['pagina'] -1) * $mostrar . ',' . $mostrar;
    $query.=$limit;
    $db->query($query);
    $retornar['datos']=$db->getArray();
    return $retornar;
  }
	
  function clienteRepetido($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT * FROM clientes WHERE numero_documento = '$arreglo[documento]'";
    $db->query($query);
    return count($db->getArray());
  }
	
  function datosCliente($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT c.*,
                td.nombre AS tipo_identi,
                tc.nombre AS nombre_tipo_cliente,
                re.nombre AS nombre_regimen,
                ae.nombre AS nombre_actividad,
                case(c.tipo_facturacion) 
                when 'V' then 'Vencido'
                when 'A' then 'Anticipado'
                else 'Error'
                end AS nombre_tipo_facturacion,
                v.nombre AS nombre_vendedor
              FROM clientes c,
                tipos_documentos td,
                tipos_cliente tc,
                regimenes re,
                actividades_economicas ae,
                vendedores v
              WHERE c.numero_documento = '$arreglo[documento]'
                AND c.tipo_documento = td.codigo
                AND c.tipo = tc.tipo
                AND re.codigo = c.regimen
                AND ae.codigo = c.actividad_economica
                AND v.codigo = c.vendedor";
    $db->query($query);
    return $db->getArray();
  }
	
  function datosTarifas($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT tc.*
              FROM tarifas_cliente tc
              WHERE tc.cliente = '$arreglo[documento]'";
    $db->query($query);
    return $db->getArray();
  }
  
  function datosTarifaCliente($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT tc.*
              FROM tarifas_cliente tc
              WHERE tc.id = '$arreglo[tarifa]'";
    $db->query($query);
    return $db->fetch();
  }
  
  function datosTarifasServicios($tarifa) {
    $db = $_SESSION['conexion'];
    $query = "SELECT tcs.*.*, s.nombre AS nombre_servicio, ct.nombre AS nombre_base
              FROM tarifas_cliente tc,
                tarifas_cliente_servicios tcs,
                servicios s,
                conceptos_tarifas ct
              WHERE tcs.tarifa_id = tc.id
                AND tc.id = $tarifa
                AND s.codigo = tcs.servicios
		        AND s.sede = tc.sede
		        AND ct.codigo = tcs.base";
    $db->query($query);
    return $db->getArray();
  }
	
  function nuevaTarifa($arreglo) {
    if(isset($arreglo[tarifa_general])) {
      $arreglo[tarifa_general] = 1;
    } else {$arreglo[tarifa_general] = 0;}
    $db = $_SESSION['conexion'];
    $query = "INSERT INTO tarifas_cliente
                (cliente, nombre_tarifa, general)
              VALUES('$arreglo[cliente]',
                '$arreglo[nombre_tarifa]',
                $arreglo[tarifa_general])";
    $db->query($query);
    return $db->getInsertID();
  }
	
  function serviciosTarifa($tarifa, $servicio, $base, $valor_minimo, $tope, $valor, $adicional, $dias, $vigencia) {
    $db = $_SESSION['conexion'];
    $query = "INSERT INTO tarifas_cliente_servicios
                (tarifa_id, servicios, base, valor_minimo, tope, valor, adicional, dias, vigencia)
              VALUES($tarifa, '$servicio', '$base', $valor_minimo, $tope, $valor, '$adicional', $dias, '$vigencia')";
    $db->query($query);
  }

  function serviciosPorTarifa($arreglo) {
    $db = $_SESSION['conexion'];
	
    $query = "SELECT tcs.tarifa_id, servicios, base, valor_minimo, tope, valor, adicional, dias, vigencia
              FROM tarifas_cliente tc, tarifas_cliente_servicios tcs
              WHERE tc.id = $arreglo[tarifa] AND tcs.tarifa_id = tc.id";
    $db->query($query);
    return $db->getArray();
  }
  
  function datosReferencias($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT tr.*
              FROM referencias tr
              WHERE tr.cliente = '$arreglo[documento]'";
    $db->query($query);
    return $db->getArray();
  }
  
  function datosReferenciaCliente($arreglo) {
    $db = $_SESSION['conexion']; 
    $query = "SELECT * FROM referencias WHERE codigo_ref = '$arreglo[referencia]'";
    $db->query($query);
    return $db->fetch();
  }
  
  function nuevaReferencia($arreglo) {
    $arreglo[vence_referencia] = (isset($arreglo[vence_referencia])?1:0);
    $arreglo[serial_referencia] = (isset($arreglo[serial_referencia])?1:0);
    $arreglo[minimo_stock] = (isset($arreglo[minimo_stock])?1:0);
    $db = $_SESSION['conexion'];
     
    //Validación duplicidad de Referencia asociada a un mismo Cliente
    $resultado = mysql_query("SELECT * FROM referencias WHERE (codigo_ref = '$arreglo[id_referencia]') 
                                AND (cliente = '$arreglo[cliente]');");
    $existe = mysql_num_rows($resultado);
     
    if($existe > 0) {
      echo "<script language='javascript'> alert('Referencia '+'$arreglo[id_referencia]'+' para el Cliente '
        +'$arreglo[cliente]'+' ya existe'); </script>";
      return true;
    } else {
      $query = "INSERT INTO referencias
                  (codigo_ref, ref_prove, nombre,  cliente, unidad,
                   unidad_venta, presentacion_venta, fecha_expira, min_stock, alto, largo,
                   ancho, serial, tipo)
                VALUES('$arreglo[id_referencia]', '$arreglo[SKU_Proveedor]', '$arreglo[nombre_referencia]',
                  '$arreglo[cliente]', $arreglo[embalaje_referencia], '$arreglo[unidad_referencia]',
                  $arreglo[presenta_venta], $arreglo[vence_referencia], $arreglo[minimo_stock], $arreglo[alto_referencia],
                  $arreglo[largo_referencia], $arreglo[ancho_referencia], $arreglo[serial_referencia],
                  '$arreglo[tipo_referencia]')";
      $db->query($query);
      return $db->getInsertID();
    }  
  }
  
  function editarReferencia($arreglo) {
    $arreglo[vence_referencia] = (isset($arreglo[vence_referencia])?1:0);
    $arreglo[serial_referencia] = (isset($arreglo[serial_referencia])?1:0);
    $arreglo[minimo_stock] = (isset($arreglo[minimo_stock])?1:0);
    $db = $_SESSION['conexion'];
    
    $query = "UPDATE referencias SET ref_prove = '$arreglo[SKU_Proveedor]', nombre = '$arreglo[nombre_referencia]', 
                cliente = '$arreglo[cliente]', unidad = $arreglo[embalaje_referencia], unidad_venta = '$arreglo[unidad_referencia]',
                presentacion_venta = $arreglo[presenta_venta], fecha_expira = $arreglo[vence_referencia],
                min_stock = $arreglo[minimo_stock], alto = $arreglo[alto_referencia], largo = $arreglo[largo_referencia], 
                ancho = $arreglo[ancho_referencia], serial = $arreglo[serial_referencia], tipo = $arreglo[tipo_referencia]
              WHERE (codigo_ref = '$arreglo[id_referencia]') AND (cliente = '$arreglo[cliente]')";
    $db->query($query);
    return true;
  }
  
  function eliminaReferencia($arreglo) {
    $db = $_SESSION['conexion'];
    
    $query = "DELETE FROM referencias WHERE (codigo_ref = '$arreglo[referencia]') AND (cliente = '$arreglo[numero_documento]')";
    $db->query($query);
    return true;
  }
  
  function listadoActividades($buscar) {
       $db = $_SESSION['conexion'];
      
        $query = " SELECT *
                 FROM actividades_economicas
                 WHERE  codigo like '%$buscar%' || nombre like '%$buscar%' ";

        $db->query($query);
        return $db->getArray();
    }
    
    function editarOrdenes($documentoAnterior, $nuevoDocumento){
        $db = $_SESSION['conexion'];
      
        $query = " UPDATE do_asignados
                   SET por_cuenta = $nuevoDocumento
                   WHERE por_cuenta = $documentoAnterior
                 ";

        $db->query($query);
    }
    
    function eliminarCliente($documento){
        $db = $_SESSION['conexion'];
      
        $query = " UPDATE clientes
                   SET inactivo = 1
                   WHERE numero_documento = $documento
                 ";

        $db->query($query);
    }

}
?>