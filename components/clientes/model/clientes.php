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
  var $ciudad;
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
    'direccion', 'telefonos_fijos', 'telefonos_celulares', 'telefonos_faxes', 'pagina_web', 'actividad_economica', 'ciudad',
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
    
    $query = "SELECT c.numero_documento, c.digito_verificacion, c.razon_social, td.nombre AS tipo_doc, tc.nombre AS nombre_tipo_cliente,
                ae.nombre AS nombre_actividad_eco, r.nombre AS nombre_regimen
              FROM clientes c
                INNER JOIN tipos_documentos td ON (c.tipo_documento = td.codigo)
                INNER JOIN tipos_cliente tc ON (c.tipo = tc.tipo)
                INNER JOIN actividades_economicas ae ON (c.actividad_economica = ae.codigo)
                INNER JOIN regimenes r ON (c.regimen = r.codigo)
              WHERE c.inactivo = 0 $buscar
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
    $query = "SELECT c.*, td.nombre AS tipo_identi, tc.nombre AS nombre_tipo_cliente, re.nombre AS nombre_regimen,
                ae.nombre AS nombre_actividad,
                CASE(c.tipo_facturacion) 
                  WHEN 'V' THEN 'Vencido'
                  WHEN 'A' THEN 'Anticipado'
                ELSE 'Error'
                END AS nombre_tipo_facturacion,
                v.nombre AS nombre_vendedor
              FROM clientes c
                INNER JOIN tipos_documentos td ON (c.tipo_documento = td.codigo)
                INNER JOIN tipos_cliente tc ON (c.tipo = tc.tipo)
                INNER JOIN regimenes re ON (c.regimen = re.codigo)
                INNER JOIN actividades_economicas ae ON (c.actividad_economica = ae.codigo)
                INNER JOIN vendedores v ON (c.vendedor = v.codigo)
              WHERE c.numero_documento = '$arreglo[documento]'";
                
    $db->query($query);
    return $db->getArray();
  }
	
  function datosTarifas($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT tc.* FROM tarifas_cliente tc WHERE tc.cliente = '$arreglo[documento]'";
    $db->query($query);
    return $db->getArray();
  }
  
  function datosTarifaCliente($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT tc.* FROM tarifas_cliente tc WHERE tc.id = '$arreglo[tarifa]'";
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
    $query = "INSERT INTO tarifas_cliente(cliente, nombre_tarifa, general)
              VALUES('$arreglo[cliente]', '$arreglo[nombre_tarifa]', $arreglo[tarifa_general])";
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
    $query = "SELECT * FROM referencias WHERE cliente = '$arreglo[documento]'";

    $db->query($query);
    return $db->getArray();
  }
  
  function datosReferenciaCliente($arreglo) {
    $db = $_SESSION['conexion']; 
    $query = "SELECT * FROM referencias WHERE codigo_ref = '$arreglo[referencia]'
                AND cliente = '$arreglo[numero_documento]'";
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
      $query = "INSERT INTO referencias(codigo_ref, ref_prove, nombre,  cliente, unidad,
                  unidad_venta, presentacion_venta, fecha_expira, min_stock, alto, largo,
                  ancho, serial, tipo, grupo_item, factor_conversion,vigencia,parte_numero,lote_cosecha)
                VALUES('$arreglo[id_referencia]', '$arreglo[SKU_Proveedor]', '$arreglo[nombre_referencia]',
                  '$arreglo[cliente]', $arreglo[embalaje_referencia], $arreglo[unidad_referencia],
                  '$arreglo[presenta_venta]', $arreglo[vence_referencia], $arreglo[minimo_stock], $arreglo[alto_referencia],
                  $arreglo[largo_referencia], $arreglo[ancho_referencia], $arreglo[serial_referencia],
                  $arreglo[tipo_referencia], RIGHT('$arreglo[grupo_items]',4),$arreglo[factor_conversion],'$arreglo[vigencia]',
				  '$arreglo[parte_numero]','$arreglo[lote_cosecha]')";

      $db->query($query);
      return $db->getInsertID();
    }  
  }

  function newGrupoItems($arreglo) {
    $db = $_SESSION['conexion'];

    $query = "INSERT INTO grupo_items(codigo,numide,nombre)
                VALUES(RIGHT('$arreglo[numero_documento]',4),'$arreglo[numero_documento]',UPPER('$arreglo[razon_social]'))";

    $db->query($query);

    return true;
  }

  //Función Creada por Fredy Salom - 21/01/2021
  function obtenerGrupoItems($arreglo) {
    $db = $_SESSION['conexion'];

    $query = "SELECT nombre FROM grupo_items WHERE numide = '$arreglo[numero_documento]'";

    $db->query($query);

    return $db->fetch();
  }

  //Función Creada por Fredy Salom - 21/01/2021
  function obtenerCodigoUnidadMedida($arreglo) {
    $db = $_SESSION['conexion'];

    $query = "SELECT codigo FROM unidades_medida WHERE id = $arreglo[id]";

    $db->query($query);

    return $db->fetch();
  }

  //Función Creada por Fredy Salom - 22/01/2021
  function obtenerIdUnidadInventario($arreglo) {
    $db = $_SESSION['conexion'];

    $query = "SELECT id FROM unidades_medida WHERE codigo = '$arreglo[presenta_venta]'";

    $db->query($query);

    return $db->fetch();
  }
 
  //Función Creada por Fredy Salom - 21/01/2021
  function obtenerCodUnidadMedida($arreglo) {
    $db = $_SESSION['conexion'];

    $query = "SELECT * FROM unidades_medida WHERE codigo = '$arreglo[codigo_unidadmedida]'";

    $db->query($query);

    return $db->getArray();
  }

  function editarReferencia($arreglo) {
    $arreglo[vence_referencia] = (isset($arreglo[vence_referencia])?1:0);
    $arreglo[serial_referencia] = (isset($arreglo[serial_referencia])?1:0);
    $arreglo[minimo_stock] = (isset($arreglo[minimo_stock])?1:0);
    $db = $_SESSION['conexion'];
 
    //Organizado por Fredy Salom - 21/01/2021   
    $query = "UPDATE referencias SET ref_prove = '$arreglo[SKU_Proveedor]',".
             "nombre = '$arreglo[nombre_referencia]',cliente = '$arreglo[cliente]',".
             "unidad = $arreglo[embalaje_referencia],".
             "unidad_venta = '$arreglo[unidad_referencia]',".
             "presentacion_venta = $arreglo[presenta_venta],".
             "fecha_expira = $arreglo[vence_referencia],".
             "min_stock = $arreglo[minimo_stock],".
             "alto = $arreglo[alto_referencia],".
             "largo = $arreglo[largo_referencia],".
             "ancho = $arreglo[ancho_referencia],".
             "serial = $arreglo[serial_referencia],".
             "tipo = $arreglo[tipo_referencia],".
             "factor_conversion = $arreglo[factor_conversion],".
             "parte_numero = '$arreglo[parte_numero]',".
             "vigencia = '$arreglo[vigencia]'".
             "WHERE (codigo_ref = '$arreglo[id_referencia]')".
             " AND (cliente = '$arreglo[cliente]')";

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

    $query = "SELECT * FROM actividades_economicas WHERE  codigo LIKE '%$buscar%' || nombre LIKE '%$buscar%' ";

    $db->query($query);
    return $db->getArray();
  }
    
  function editarOrdenes($documentoAnterior, $nuevoDocumento) {
    $db = $_SESSION['conexion'];

    $query = "UPDATE do_asignados SET por_cuenta = $nuevoDocumento WHERE por_cuenta = $documentoAnterior";

    $db->query($query);
  }

  function editReferencias($documentoAnterior, $nuevoDocumento) {
    $db = $_SESSION['conexion'];

    $query = "UPDATE referencias SET cliente = $nuevoDocumento WHERE cliente = $documentoAnterior";

    $db->query($query);
  }

  function eliminarCliente($documento) {
    $db = $_SESSION['conexion'];

    $query = " UPDATE clientes SET inactivo = 1 WHERE numero_documento = $documento";

    $db->query($query);
  }
  
  function datosDocumentos($arreglo) {
    $db = $_SESSION['conexion'];
    $query = "SELECT * FROM docclientes_anexos WHERE numero_documento = '$arreglo[documento]'";

    $db->query($query);
    return $db->getArray();
  }
  
  function cargarDocumentos($arreglo) {
    $db = $_SESSION['conexion'];
    $fecha = date("Y-m-d");

    $query = "INSERT INTO docclientes_anexos(numero_documento, fecha_documento, nombre_documento) 
                VALUES('$arreglo[numdoc]','$fecha','$arreglo[archivo]')";
    $db->query($query);
  }
  
  function eliminarDocumento($arreglo) {
    $db = $_SESSION['conexion'];
    $ruta = "integrado/_files/".$arreglo[documento]."/";
    
    // Eliminamos físicamente el archivo
    unlink($ruta.$arreglo[nombredoc]);
    // Eliminamos el archivo de la tabla
    $query = "DELETE FROM docclientes_anexos
              WHERE (numero_documento = '$arreglo[documento]') AND (nombre_documento = '$arreglo[nombredoc]')";
    $db->query($query);
    return true;
  }
}
?>