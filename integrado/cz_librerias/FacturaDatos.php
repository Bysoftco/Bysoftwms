<?php
require_once(DB.'BDControlador.php'); 
require_once("LevanteDatos.php"); 

class Factura extends BDControlador {
  var $db;

  function Factura() {
    $this->db = $_SESSION['conexion'];
    $this->estilo_error = "ui-state-error";
    $this->estilo_ok = "ui-state-highlight";
    // Version 25022017
    //Nueva Versi�n 01022023 - Fredy Salom
  } 
    
  // Funci�n que crea un concepto en el Cuerpo de la factura 
  function addConcepto(&$arregloDatos) {
    $fecha = FECHA;
    $sede = $_SESSION['sede']; 

    $sql = "INSERT INTO facturas_detalle(factura, fecha_factura, sede, cantidad) VALUES($arregloDatos[num_prefactura], '$fecha', '$sede', 1)";
		
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al crear nuevo Concepto $sql";
      $arregloDatos['estilo'] = $this->estilo_error;
      return TRUE;
    }
  }
    
  //Funci�n que retorna los conceptos de una factura
  function getConceptos($arregloDatos) {
    $sede = $_SESSION['sede'];  
    $sql = "SELECT fd.*,servicios.cuenta, servicios.nombre AS nombre_servicio, fd.iva, fd.rte_fuente, fd.rte_ica, fd.tipo, conceptos_tarifas.nombre as concep_tarifa
            FROM facturas_detalle fd
              LEFT JOIN servicios ON fd.concepto = servicios.codigo AND servicios.sede = '$sede'
              LEFT JOIN conceptos_tarifas ON fd.tipo=conceptos_tarifas.codigo
            WHERE factura = '$arregloDatos[num_prefactura]' AND fd.sede = '$sede'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "error al borrar mercancia del levante ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }
    
  function listarAnticipos($arregloDatos) {
    $sede = $_SESSION['sede'];  
    $sql = "SELECT * FROM anticipos WHERE factura = $arregloDatos[num_prefactura]";
     
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "error al consultar los anticipos del sistema ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }
    
  function pieFacturas($arregloDatos) {
    $sede = $_SESSION['sede'];  
    $sql = "SELECT numero_copias,pie1,pie2 FROM sedes WHERE codigo = '$sede'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "error al consultar los anticipos del sistema ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }
    
  function updateAnticipo($codigo,$recibo,$valor) {
    if(empty($valor)) { $valor = 0; }
    $sede = $_SESSION['sede'];  
    $sql = "UPDATE anticipos SET valor_anticipo = $valor, num_recibo = '$recibo' WHERE codigo = $codigo";
    
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "error al guardar anticipos ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }
    
  function getFormaAnticipos($arregloDatos) {
    $sede = $_SESSION['sede'];  
    $sql = "SELECT * FROM anticipos WHERE factura = $arregloDatos[num_factura]";
     
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "error al consultar los anticipos del sistema ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  function deleteAnticipos($arregloDatos) {
    $sede = $_SESSION['sede'];  
    $sql = "DELETE FROM anticipos WHERE factura = $arregloDatos[num_factura] AND codigo = $arregloDatos[id_factura]";
     
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "error al borrar el registro ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }
    
  function deleteAnticipos1($factura) {
    $sql = "DELETE FROM anticipos WHERE factura = '$factura'";
      
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
    }
  }
    
  function impresion($arregloDatos) {
    $this->getCabeza($arregloDatos);
  }
    
  //Funci�n que retorna los conceptos de una factura
  function getCabeza($arregloDatos) {
    $sql = "SELECT fm.*, clientes.razon_social AS facturado_a_nombre, clientes.numero_documento AS facturado_a_nit, clientes.direccion, clientes.telefonos_fijos,
              clientes.regimen, regimenes.nombre AS nombre_regimen, por_cuenta.numero_documento AS por_cuenta_nit, por_cuenta.razon_social AS por_cuenta_nombre,
              por_cuenta.direccion AS por_cuenta_dir, por_cuenta.telefonos_fijos AS por_cuenta_tel, vendedores.nombre AS comercial, fm.orden, fm.remesa,clientes.ciudad
            FROM regimenes,facturas_maestro fm
              LEFT JOIN clientes ON fm.cliente = clientes.numero_documento
              LEFT JOIN clientes AS por_cuenta ON fm.intermediario = por_cuenta.numero_documento
              LEFT JOIN vendedores ON fm.vendedor = vendedores.codigo
            WHERE fm.codigo = '$arregloDatos[num_prefactura]'
              AND regimenes.codigo=clientes.regimen";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "error al consultar Factura ";
      $this->estilo = $this->estilo_error; 
      return TRUE;
    }
  }
    
  function listarFacturas($arregloDatos) {
    $sede = $_SESSION['sede'];

    $sql = "SELECT fm.*, clientes.razon_social, clientes.numero_documento AS por_cuenta FROM facturas_maestro fm, clientes 
            WHERE fm.cliente = clientes.numero_documento
              AND fm.sede = '$sede'";

    if(!empty($arregloDatos['preliquidacion'])) {
      $sql .= " AND fm.cerrada=0";
    }
    
    if(!empty($arregloDatos['consecutivo'])) {
      $sql .= " AND fm.codigo=$arregloDatos[consecutivo]";
    }
        
    if(!empty($arregloDatos['por_cuenta_filtro'])) {
      $sql .= " AND fm.cliente='$arregloDatos[por_cuenta_filtro]'";
    }
    
    if(!empty($arregloDatos['doc_filtro'])) {
      $sql .= " AND fm.documento_transporte='$arregloDatos[doc_filtro]'";
    }
    
    if(!empty($arregloDatos['do_filtro'])) {
      $sql .= " AND fm.orden='$arregloDatos[do_filtro]'";
    }
    
    if(empty($arregloDatos['preliquidacion'])) {
      $sql .= " AND (fm.cerrada=1 OR numero_oficial <> 0)";
    }
    
    if(!empty($arregloDatos['ver_anuladas'])) {
      $sql .= " AND fm.anulada=1";
    }
        
    if(!empty($arregloDatos['factura_filtro_consulta'])) {
      $sql .= " AND fm.numero_oficial='$arregloDatos[factura_filtro_consulta]'";
    }
    
    if(!empty($arregloDatos['fecha_inicio']) and !empty($arregloDatos['fecha_fin'])) {
      $sql .= " AND fm.fecha_factura >= '$arregloDatos[fecha_inicio]' AND fm.fecha_factura <= '$arregloDatos[fecha_fin]' ";
    }
    $sql .= " ORDER BY fm.numero_oficial ASC";

    if(!empty($arregloDatos['excel'])) {
      return $sql;
    }
    
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje = "error al borrar mercancia del levante ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }

  //Funci�n que crea la Cabeza de La Factura
  function addCabeza(&$arregloDatos) {
    $fecha = FECHA;
    $sede = $_SESSION['sede']; 
  	// se averigua la firma vigente
  	$unaConsulta = new Factura();
  	$unaConsulta->getIdFirma($arregloDatos);
    $sql = "INSERT INTO facturas_maestro(cliente,intermediario,fecha_factura,sede,id_firma) VALUES('$arregloDatos[por_cuenta_filtro]', '$arregloDatos[por_cuenta_filtro]',
              '$fecha','$sede','$arregloDatos[id_firma]')";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al crear nuevo Factura $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      return FALSE;
    } else { return TRUE; }
  }
    
  //Funci�n que actualiza datos de la orden
  function updateInfoOrden(&$arregloDatos) {
    $sql = "UPDATE do_asignados SET prefactura = $arregloDatos[num_prefactura], do_estado = 5 WHERE do_asignado = '$arregloDatos[orden]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al actualizar  datos de Orden $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      echo $arregloDatos['mensaje'];
      return FALSE;
    } else { return TRUE; }    
  }
    
  //Funci�n que actualiza datos de la orden
  function reportarImpresion($arregloDatos) {
    $sql = "UPDATE facturas_maestro SET impresa = 1 WHERE codigo = '$arregloDatos[num_prefactura]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al actualizar estado de factura";
      $arregloDatos['estilo'] = $this->estilo_error;
      echo $arregloDatos['mensaje'];
      return FALSE;
    } else { return TRUE; }    
  }
  
  //Funci�n que habilita la impresi�n de la factura
  function habilitaReimpresion($arregloDatos) {
    $sql = "UPDATE facturas_maestro SET impresa = 0 WHERE codigo = '$arregloDatos[num_prefactura]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje_status'] = " Error al actualizar estado de factura";
      $arregloDatos['estilo_status'] = $this->estilo_error;
      echo $arregloDatos['mensaje'];
      return FALSE;
    } else {
      $arregloDatos['mensaje_status'] = " La factura se habilito para impresion correctamente";
      $arregloDatos['estilo_status'] = $this->estilo_ok;
      return TRUE;      
    }
  }
    
  //Funci�n que habilita la impresi�n de la factura
  function abrirFactura(&$arregloDatos) {
    $sql = "UPDATE  facturas_maestro SET cerrada = 0 WHERE codigo = '$arregloDatos[num_prefactura]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje_status'] = " Error al actualizar estado de factura";
      $arregloDatos['estilo_status'] = $this->estilo_error;
      echo $arregloDatos['mensaje'];
      return FALSE;
    } else {
      $arregloDatos['mensaje_status'] = " La factura se abrio para modificacion correctamente";
      $arregloDatos['estilo_status'] = $this->estilo_ok;
      return TRUE;      
    }
  }
    
  //Funci�n que anula una factura
  function anularFactura(&$arregloDatos) {
    $sql="UPDATE facturas_maestro SET anulada = 1 WHERE codigo = '$arregloDatos[num_prefactura]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje_status'] = " Error al intentar anular la factura";
      $arregloDatos['estilo_status'] = $this->estilo_error;
      echo $arregloDatos['mensaje'];
      return FALSE;
    } else {
      $arregloDatos['mensaje_status'] = " La factura se anulo correctamente";
      $arregloDatos['estilo_status'] = $this->estilo_ok;
      return TRUE;      
    }
  }
    
  //Funci�n que liga la remesa con la Factura
  function setLigarRemesa($arregloDatos) {
    $sql = "UPDATE  inventario_maestro_movimientos SET prefactura = $arregloDatos[num_prefactura] WHERE codigo = '$arregloDatos[remesa]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al ligar La remesa con la factura $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      echo $arregloDatos['mensaje'];
      return FALSE;
    } else { return TRUE; }    
  }
    
  //Funci�n que desliga la remesa con la Factura
  function setLiveraRemesa($arregloDatos) {
    $sql = "UPDATE inventario_maestro_movimientos SET prefactura = 0 WHERE prefactura = '$arregloDatos[num_prefactura]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al ligar La remesa con la factura $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      echo $arregloDatos['mensaje'];
      return FALSE;
    } else { return TRUE; }    
  }
    
  //Funci�n que actualiza la Cabeza de La Factura
  function ligarRemesa($arregloDatos) {
    if(empty($arregloDatos['rte_iva'])) { $arregloDatos['rte_iva'] = 0; }
    if(empty($arregloDatos['efectivo'])) { $arregloDatos['efectivo'] = 0; }
    if(empty($arregloDatos['cheque'])) { $arregloDatos['cheque'] = 0; }
    if(empty($arregloDatos['valor_anticipo'])) { $arregloDatos['valor_anticipo'] = 0; }
      
    $fecha = FECHA;
    $sql = "UPDATE facturas_maestro SET intermediario = '$arregloDatos[por_cuenta_nit]', cliente = '$arregloDatos[facturado_a_nit]',
              neto = $arregloDatos[neto], orden = '$arregloDatos[orden]', documento_transporte = '$arregloDatos[documento_transporte]',
              fecha_entrada = '$arregloDatos[fecha_entrada]', fecha_factura = '$arregloDatos[fecha_factura]', fecha_salida = '$arregloDatos[fecha_salida]',
              ubicacion = '$arregloDatos[ubicacion]', remesa = '$arregloDatos[remesa]', vendedor = '$arregloDatos[comercial]',
              centro_costo = '$arregloDatos[centro_costo]', subcentro_costo = '$arregloDatos[subcentro]', subtotal = '$arregloDatos[subtotal]',
              iva = '$arregloDatos[iva]', rte_fuente = '$arregloDatos[rte_fuente]', rte_iva = '$arregloDatos[rte_iva]', rte_ica = '$arregloDatos[rte_ica]',
              total = '$arregloDatos[total]', efectivo = '$arregloDatos[efectivo]', cheque = '$arregloDatos[cheque]', banco = '$arregloDatos[banco]',
              cuenta = '$arregloDatos[cuenta]', credito = '$arregloDatos[credito]', anticipo = '$arregloDatos[anticipo]',
              valor_anticipo = '$arregloDatos[valor_anticipo]', recibo = '$arregloDatos[recibo]', observaciones = '$arregloDatos[observaciones]',
			  trm='$arregloDatos[trm]'
            WHERE codigo = $arregloDatos[num_prefactura] ";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al actualizar  datos de Factura $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      echo $arregloDatos['mensaje'];
      return FALSE;
    } else { return TRUE; }    
  }

  //Funci�n que actualiza la Cabeza de La Factura
  function updateCabeza(&$arregloDatos) {
    if(empty($arregloDatos['rte_iva'])) { $arregloDatos['rte_iva'] = 0; }
    if(empty($arregloDatos['efectivo'])) { $arregloDatos['efectivo'] = 0; }
    if(empty($arregloDatos['cheque'])) { $arregloDatos['cheque'] = 0; }
    if(empty($arregloDatos['valor_anticipo'])) { $arregloDatos['valor_anticipo'] = 0; }

    $fecha = FECHA;
    $sql = "UPDATE facturas_maestro SET intermediario = '$arregloDatos[por_cuenta_nit]', cliente = '$arregloDatos[facturado_a_nit]', neto = $arregloDatos[neto],
              orden = '$arregloDatos[orden]', documento_transporte = '$arregloDatos[documento_transporte]', fecha_entrada = '$arregloDatos[fecha_entrada]',
              fecha_factura = '$arregloDatos[fecha_factura]', fecha_salida = '$arregloDatos[fecha_salida]', ubicacion = '$arregloDatos[ubicacion]',
              remesa = '$arregloDatos[remesa]', vendedor = '$arregloDatos[comercial]', centro_costo = '$arregloDatos[centro_costo]',
              subcentro_costo = '$arregloDatos[subcentro]', subtotal = '$arregloDatos[subtotal]', iva = '$arregloDatos[iva]',
              rte_fuente = '$arregloDatos[rte_fuente]', rte_iva = '$arregloDatos[rte_iva]', rte_ica = '$arregloDatos[rte_ica]',
              rte_cree = '$arregloDatos[rte_cree]', total = '$arregloDatos[total]', efectivo = '$arregloDatos[efectivo]', cheque = '$arregloDatos[cheque]',
              banco = '$arregloDatos[banco]', cuenta = '$arregloDatos[cuenta]', credito = '$arregloDatos[credito]', anticipo = '$arregloDatos[anticipo]',
              valor_anticipo = '$arregloDatos[valor_anticipo]', recibo = '$arregloDatos[recibo]', observaciones = '$arregloDatos[observaciones]',
			  trm='$arregloDatos[trm]'
            WHERE codigo = $arregloDatos[num_prefactura] ";
		
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al actualizar  datos de Factura $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      echo $arregloDatos['mensaje'];
      return FALSE;
    } else { return TRUE; }    
  }

  //Funci�n que actualiza los Conceptos de La Factura
  function updateConcepto($arregloDatos) {
    // Tipo hace referencia a la tabla  conceptos_tarifas
    $fecha = FECHA;
    $sql = "UPDATE facturas_detalle SET concepto = $arregloDatos[cod_concepto], iva = $arregloDatos[iva], rte_fuente = $arregloDatos[rte_fuente],
              rte_ica = $arregloDatos[rete_ica], rte_cree = $arregloDatos[rete_cree], tipo = $arregloDatos[concep_tarifa],
              cantidad = $arregloDatos[cantidad], valor = $arregloDatos[valor], valor_unitario = $arregloDatos[valor_unitario], base = $arregloDatos[base],
              porcentaje = $arregloDatos[multiplicador]
            WHERE codigo = $arregloDatos[id_concepto] ";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al actualizar  datos de Factura $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      echo $arregloDatos['mensaje'];
    }
  }


  //Funci�n que borra los Conceptos de La Factura
  function delConcepto(&$arregloDatos) {
    $fecha = FECHA;
    $sql = "DELETE FROM facturas_detalle WHERE codigo = $arregloDatos[id_concepto] ";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al borrar  servicio de Factura $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      return FALSE;
    } else { echo 1; } 
  }

  // Funci�n que obtiene el Listado de Remesas Para Facturar
  function getParaFacturarRemesa($arregloDatos) {
  	$sede = $_SESSION['sede']; 
    $sql = "SELECT DISTINCT MAX(imm.fecha) AS fecha, MAX(imm.codigo) AS remesa, MAX(imm.lev_documento) AS lev_documento, MAX(imm.prefactura) AS prefactura,
              MAX(im.tipo_movimiento) AS tipo_movimiento, MAX(itm.nombre) AS nombre_movimiento, MAX(clientes.numero_documento) AS numero_documento,
              MAX(clientes.razon_social) AS nombre_cliente, MAX(aduana.razon_social) AS nombre_aduana, MAX(camiones.placa) AS placa,
              MAX(camiones.conductor_nombre) AS conductor_nombre, MAX(im.tipo_movimiento) AS movimiento_tipo, MAX(do_asignados.do_asignado) AS do_asignado,
              MAX(do_asignados.doc_tte) AS doc_tte, MAX(imm.fmm) AS fmm, MIN(fecha_arribo) AS fecha_arribo,
			  
              MIN(DATE_ADD( fecha_arribo, INTERVAL clientes.dias_para_facturar DAY )) AS fecha_fin_antes,
			   IF( NOW() >   MIN(DATE_ADD( fecha_arribo, INTERVAL clientes.dias_para_facturar DAY )) ,DATE_FORMAT(ADDDATE(NOW(), INTERVAL 1 DAY),'%Y-%m-%d'), MIN(DATE_ADD( fecha_arribo, INTERVAL clientes.dias_para_facturar DAY ))) AS fecha_fin
			  
            FROM inventario_movimientos im, inventario_tipos_movimiento itm, inventario_entradas ie, arribos, do_asignados, clientes,
              inventario_maestro_movimientos imm
              LEFT JOIN clientes aduana ON imm.lev_sia = aduana.numero_documento
              LEFT JOIN camiones ON imm.id_camion = camiones.codigo
            WHERE im.cod_maestro = imm.codigo
              AND im.tipo_movimiento = itm.codigo
              AND im.inventario_entrada = ie.codigo
              AND arribos.arribo = ie.arribo
              AND arribos.orden = do_asignados.do_asignado
              AND do_asignados.por_cuenta = clientes.numero_documento
              AND do_asignados.factura = 0
              AND do_asignados.por_cuenta = '$arregloDatos[por_cuenta_filtro]'
			  AND do_asignados.sede='$sede'
			  ";
      
    // Por_cuenta_filtro
    if(!empty($arregloDatos['tipo_movimiento'])) {
      $sql .= " AND im.tipo_movimiento=$arregloDatos[tipo_movimiento] ";
    }
    
    if(!empty($arregloDatos['remesa'])) {
      $sql .= " AND imm.codigo=$arregloDatos[remesa] ";
    }         
		
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al obtener listado de Dos $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      return TRUE;
    }
  }
  
  // Funci�n que obtiene el Listado de Dos Para Facturar
  function getParaFacturar($arregloDatos) {
    // Fecha del primer arribo para fecha ingreso de la factura
    $fecha = FECHA;
    $sede = $_SESSION['sede']; 

    $sql = "SELECT MAX(do_asignados.do_asignado) AS do_asignado, MAX(do_asignados.fecha) AS fecha, MAX(do_asignados.doc_tte) AS doc_tte,
              MAX(do_asignados.ingreso) AS ingreso, MAX(do_asignados.pedido) AS pedido, MAX(do_asignados.factura) AS factura,
              MAX(do_asignados.bodega) AS bodega, MAX(do_bodegas.nombre) AS bodega_nombre, MAX(por_cuenta) AS por_cuenta, MAX(razon_social) AS razon_social,
              MAX(do_asignados.obs) AS obs, MAX(do_asignados.reasignado) AS reasignado, MAX(do_asignados.codigo) AS codigo,
              MAX(do_asignados.prefactura) AS prefactura, MIN(fecha_arribo) AS fecha_arribo, MIN(dias_para_facturar) AS dias_para_facturar,
               MIN(DATE_ADD( fecha_arribo, INTERVAL clientes.dias_para_facturar DAY )) AS fecha_fin_antes,
			  IF( NOW() >   MIN(DATE_ADD( fecha_arribo, INTERVAL clientes.dias_para_facturar DAY )) ,DATE_FORMAT(ADDDATE(NOW(), INTERVAL 1 DAY),'%Y-%m-%d'), MIN(DATE_ADD( fecha_arribo, INTERVAL clientes.dias_para_facturar DAY ))) AS fecha_fin
            FROM do_asignados,do_bodegas,clientes,arribos
            WHERE arribos.orden=do_asignados.do_asignado
              AND do_asignados.bodega = do_bodegas.codigo
              AND numero_documento = por_cuenta
              AND por_cuenta = '$arregloDatos[por_cuenta_filtro]'
              AND do_asignados.sede = '$sede'";

    if($arregloDatos['estado'] == 5){ // Para que solo liste asignados y en reapertura
      $sql .= " AND estado IN(1,5)";
    }
    
    if(!empty($arregloDatos['orden'])) {
      $sql .= " AND do_asignados.do_asignado='$arregloDatos[orden]'";
    }
    
    if(!empty($arregloDatos['documento_transporte'])) {
      $sql .= " AND do_asignados.doc_tte='$arregloDatos[documento_transporte]'";
    }
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al obtener listado de Dos $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      return TRUE;
    }
  }
  
  function findConcepto($arregloDatos) {
    $sede = $_SESSION['sede'];		

    $sql = "SELECT codigo, nombre, iva, rte_fuente, rte_ica, rte_cree FROM servicios WHERE nombre LIKE '%$arregloDatos[q]%' AND sede ='$sede' AND tipo = 0";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      return TRUE;
    }
  }
		
  // Funci�n que retorna el ultimo ID Insertado 
  function getConsecutivo(&$arregloDatos) {
    $fecha = FECHA;

    $sql = "SELECT LAST_INSERT_ID() AS consecutivo";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $arregloDatos['mensaje'] = " Error al obtener Consecutivo $sql".$this->_lastError->message;
      $arregloDatos['estilo'] = $this->estilo_error;
      return TRUE;
    } else {
      $dato = $this->db->fetch();
      $arregloDatos['num_prefactura'] = $dato->consecutivo;      
    }
  }

  //Funci�n que reporta un Do como facturado
  function setEstadoDo($arregloDatos) {
    $arregloDatos['estado_do'] = 5;	
    if(empty($arregloDatos['consecutivo'])) { $arregloDatos['consecutivo'] = 0; }
    $sql = "UPDATE do_asignados SET estado = $arregloDatos[estado_do], prefactura	= '$arregloDatos[num_prefactura]', factura = '$arregloDatos[consecutivo]'		
            WHERE codigo = $arregloDatos[codigo_do]";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje_error = " error al actualizar estado de Do ";
      echo $this->mensaje_error;
      return TRUE;
    }
  }

  //Funci�n que reporta una Prefactura Como factura con n�mero Oficial
  function setNuevaFactura(&$arregloDatos) {
    $unaConsulta = new Factura();
    $unaConsulta->getIdFirma($arregloDatos);
    if(empty($arregloDatos['consecutivo'])) { $arregloDatos['consecutivo'] = 0; }
    $sql = "UPDATE facturas_maestro SET numero_oficial = $arregloDatos[num_factura], cerrada = 1, id_resolucion = $arregloDatos[id_resolucion],
              id_firma = $arregloDatos[id_firma]
            WHERE codigo = $arregloDatos[num_prefactura]";
	
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje_error = " error generar consecutivo de Factura ";
      echo $this->mensaje_error;
      return TRUE;
    }
  }

  //Funci�n que verifica si a una prefactura ya se le asign� factura, para de esta forma evitar salto de CONSECUTIVO
  function verificaAsignaFactura(&$arregloDatos) { 
    $sql = "SELECT numero_oficial FROM facturas_maestro 
            WHERE codigo = $arregloDatos[num_prefactura]";
	
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje_error = " error al consultar consecutivo de la Factura ";
      echo $this->mensaje_error;
      return TRUE;
    } else {
      $dato = $this->db->fetch();
      return $dato->numero_oficial;      
    }
  }
    
  //Funci�n que obtiene el ID de la ultima resoluci�n
  function getResolucion(&$arregloDatos) {
    $sede = $_SESSION['sede'];

    $sql = "SELECT MAX(codigo) AS id_resolucion FROM resoluciones WHERE sede = '$sede'";
	
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje_error = " error al consultar ID de resolucion ";
      echo $this->mensaje_error;
      return TRUE;
    } else {
      $dato = $this->db->fetch();
      $arregloDatos['id_resolucion'] = $dato->id_resolucion;      
    }

  }
    
  //Funci�n que obtiene los Datos de la  resoluci�n
  function getDatosResolucion(&$arregloDatos,$resolucion) {
    $sede = $_SESSION['sede'];

    $sql = "SELECT * FROM resoluciones where codigo=$resolucion AND sede='$sede'";
	
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje_error = " error al consultar ID de resolucion ";
      echo $this->mensaje_error;
      return TRUE;
    } else {
      $dato = $this->db->fetch();
      $arregloDatos['resolucion'] = $dato->resolucion;
      $arregloDatos['fecha'] = $dato->fecha;
      $arregloDatos['desde'] = $dato->desde;
      $arregloDatos['hasta'] = $dato->hasta;
      $arregloDatos['nitResolucion'] = $dato->nit;
      $arregloDatos['ciiu'] = $dato->ciiu;
      $arregloDatos['regimen'] = $dato->regimen;
      $arregloDatos['prefijo'] = $dato->prefijo;
      $arregloDatos['cree'] = $dato->cree;
      $arregloDatos['vigencia'] = $dato->vigencia;      
    }
  }

  //Funci�n que obtiene el ID de la ultima firma
  function getIdFirma(&$arregloDatos) {
    $sede = $_SESSION['sede'];

    $sql = "SELECT MAX(codigo) AS id_firma FROM firmas WHERE sede = '$sede'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje_error = " error al consultar ID de resolucion ";
      echo $this->mensaje_error;
      return TRUE;
    } else {
      $dato = $this->db->fetch();
      $arregloDatos['id_firma'] = $dato->id_firma;      
    }

  }
  
  //Funci�n que obtiene los Datos de la resoluci�n
  function getRutaFirma(&$arregloDatos,$firma) {
    $sede = $_SESSION['sede'];

    $sql = "SELECT * FROM firmas WHERE codigo = $firma AND sede = '$sede'";
	
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje_error = " error al consultar ID de resolucion ";
      echo $this->mensaje_error;
      return TRUE;
    } else {
      $dato = $this->db->fetch();
      $arregloDatos['rutaFirma'] = $dato->ruta;      
    }
  }
  
  function getCuenta( &$arregloDatos) {
    $sede = $_SESSION['sede'];

    $sql = "SELECT * FROM sedes WHERE  codigo = '$sede'";
	
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      $this->mensaje_error = " error al consultar ID de resolucion ";
      echo $this->mensaje_error;
      return TRUE;
    } else {
      $dato = $this->db->fetch();
      $arregloDatos['banco_cheque'] = $dato->banco;
      $arregloDatos['nombre_sede'] = $dato->nombre;      
    }
  }
  
  function getFirma($arregloDatos) {
    $arregloDatos['id_firma'] = !isset($arregloDatos['id_firma']) ? 0 : $arregloDatos['id_firma'];
    $sql = "SELECT * FROM firmas WHERE codigo=$arregloDatos[id_firma]";
	
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      echo "error al consultar la firma";
      echo $this->mensaje_error;
      return TRUE;
    } else {
      $dato = $this->db->fetch();
      $arregloDatos['rutaFirma'] = $dato->ruta;      
    }
  }
 
  //Funci�n para Cargar Listas
  function lista($tabla,$condicion=NULL,$campoCondicion=NULL) {
    $sql = "SELECT codigo,nombre FROM $tabla WHERE codigo NOT IN('0')" ;

    if($condicion <> NULL and $condicion <> '%') {
      $sql .= " AND $campoCondicion IN ('$condicion')" ;
    }
    $sql .= "	ORDER BY nombre	" ;
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      return FALSE;
    } else {
      $arreglo = array();
      while($obj=$this->db->fetch()) {
        $arreglo[$obj->codigo] = ucwords(strtolower($obj->nombre));
      }
      return $arreglo;
    }
  }
    
  // Funci�n que consulta el Siguiente Consecutivo de una factura
  function numeroFactura($arregloCampos) {
    $sede = $_SESSION['sede'];
    $anio = date('Y');		
    $unFactura = new Factura();
    $verificaUnConsecutivo = new Factura();
    $sql = "SELECT MAX(consecutivo) AS nuevo_consecutivo FROM consecutivo_facturas WHERE sede = '$sede'";
					
    $resultado = $unFactura->db->query($sql);
    $sql=htmlentities($sql,ENT_QUOTES);
    $usuario=$_SESSION['datos_logueo']['usuario_id'].' '.$_SESSION['datos_logueo']['nombre_usuario'].' '.$_SESSION['datos_logueo']['apellido_usuario'] ;
    if(!is_null($resultado)) {
      echo $sql;
      $this->auditoria($usuario,'numeroFactura','numeroFactura',$sql,'ERROR');       
    } else {
      $unFactura = $unFactura->db->fetch();
      $nuevo = $unFactura->nuevo_consecutivo;
      $this->auditoria($usuario,'numeroFactura',"numeroFactura $nuevo",$sql,'INFO');
      $arregloCampos['concecutivo']= $nuevo;
      if(!$verificaUnConsecutivo->verificaAsignaFactura($arregloCampos)) { 
        $this->cambiaConsecutivo($arregloCampos);
      }
      return $nuevo;
    }
  }
    
  function cambiaConsecutivo($arregloDatos) {
    $sede = $_SESSION['sede'];
    $anio = date('Y');		
    $unFactura = new Factura();

    $sql = "UPDATE consecutivo_facturas SET consecutivo	= consecutivo+ 1 WHERE sede = '$sede'";
    
    $resultado = $unFactura->db->query($sql);
    $sql=htmlentities($sql,ENT_QUOTES);
    $usuario=$_SESSION['datos_logueo']['usuario_id'].' '.$_SESSION['datos_logueo']['nombre_usuario'].' '.$_SESSION['datos_logueo']['apellido_usuario'];
    if(!is_null($resultado)) {
      echo $sql;
      $this->auditoria($usuario,'FacturaDatos',"cambiaConsecutivo $arregloDatos[concecutivo]",$sql,'ERROR');
    } else { 
      $this->auditoria($usuario,'FacturaDatos',"cambiaConsecutivo $arregloDatos[concecutivo]",$sql,'INFO');
    }
  }

  function addAnticipos($factura,$valor,$recibo) {
    $sql = "INSERT INTO anticipos(factura,valor_anticipo,num_recibo) VALUES ('$factura','$valor','$recibo')";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
    }
  }

  function totalAnticipos($arregloDatos) {
    $sede = $_SESSION['sede']; 

    $sql = "SELECT SUM(valor_anticipo) AS total_anticipos FROM anticipos WHERE factura = $arregloDatos[num_prefactura] GROUP BY factura " ;
		
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      echo "<div class=error align=center> :( Error al Consultar Total Anticipo<br>$sql</div>.";
      return FALSE;
    } else {
      $arregloDatos['sql'] = htmlentities($sql, ENT_QUOTES);    
      $dato = $this->db->fetch();
      return $dato->total_anticipos;            
    }
	}
 
  function getDatosUnaOrden($arregloDatos) {
    $sql = "SELECT SUM(cantidad) AS cantidad, SUM(peso_bruto) AS peso, SUM(valor_fob) AS valor
              FROM arribos
            WHERE orden='$arregloDatos[una_orden]'";
    
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      echo "<div class=error align=center> :( Error al Consultar la Orden <br>$sql</div>.";
      return FALSE;
    }
  }
  
  function getTope($arregloDatos) {
    $sede = $_SESSION['sede'];
    $sql = "SELECT MAX(valor_minimo) AS tope_minimo,MAX(rete_ica) AS rete_ica FROM sedes WHERE codigo= '$sede'";
    
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      echo "<div class=error align=center> :( Error al Consultar Tope de retencion Minima <br>$sql</div>.";
      return FALSE;
    }
  }
  
  function findClientet($arregloDatos) {
		$sql = "SELECT razon_social	FROM clientes WHERE (numero_documento = '$arregloDatos')";
    
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
			return TRUE;
		} else {
      $dato = $this->db->fetch();
      return $dato->razon_social;
    }
	}

  function existeCliente($arregloDatos) {
    $sql = "SELECT numero_documento,razon_social FROM clientes
            WHERE numero_documento = '$arregloDatos[por_cuenta]'";

    $this->db->query($sql);
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      return TRUE;
    }
  }
	
	function getFacturaPreliquidacion($arregloDatos) {
		$sql = "SELECT fm.*, clientes.razon_social AS facturado_a_nombre, clientes.numero_documento AS facturado_a_nit, clientes.direccion, clientes.telefonos_fijos,
              clientes.regimen, regimenes.nombre AS nombre_regimen, por_cuenta.numero_documento AS por_cuenta_nit, por_cuenta.razon_social AS por_cuenta_nombre,
              por_cuenta.direccion AS por_cuenta_dir, por_cuenta.telefonos_fijos AS por_cuenta_tel, vendedores.nombre AS comercial, fm.orden, fm.remesa,clientes.ciudad
            FROM regimenes,facturas_maestro fm
              LEFT JOIN clientes ON fm.cliente = clientes.numero_documento
              LEFT JOIN clientes AS por_cuenta ON fm.intermediario = por_cuenta.numero_documento
              LEFT JOIN vendedores ON fm.vendedor = vendedores.codigo
            WHERE fm.codigo = '$arregloDatos[num_prefactura]'
              AND regimenes.codigo=clientes.regimen";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
			return TRUE;
		}   
	}
	
	function auditoria($usuario,$modulo,$evento,$descripcion,$tipo_error) {
		$unLog = new Orden();
		$fecha = date('Y/m/d H:i:s');
		
		$evento=$fecha.' :'.$evento;
		$modulo=$modulo.' :'.$tipo_error;
		$sql = "INSERT INTO log_aplicacion(usuario_id,modulo,funcion,codigo_sql)
						VALUES ('$usuario','$modulo','$evento','$descripcion')";
		
		$resultado = $unLog->db->query($sql); 
		if(!is_null($resultado)) {
			echo $sql;
		}
	}

	function proximaFactura($arregloDatos) {
		$sede = $_SESSION['sede'];
		$sql = "SELECT MAX(consecutivo) AS nuevo_consecutivo FROM consecutivo_facturas WHERE sede = '$sede'";
   
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
			return TRUE;
		} else {
      $dato = $this->db->fetch();
      return  $dato->nuevo_consecutivo;      
    }
  }
	
	function saldoInventario(&$arregloDatos) {
		$unLevante = new Levante();
		$unLevante->getInvParaProceso($arregloDatos);
		$unLevante = $unLevante->db->fetch();
		
		$arregloDatos['datos_remesa'] .= "Piezas:";
		
		if($unLevante->cantidad_nonac > 0){ $arregloDatos['datos_remesa'] .= "E:".number_format($unLevante->cantidad_nonac,2,',','.'); }
		if($unLevante->cantidad_naci > 0){ $arregloDatos['datos_remesa'] .= "N:".number_format($unLevante->cantidad_naci,2,',','.'); }
		
		$arregloDatos['datos_remesa'] .= "Peso:";
		if($unLevante->peso_nonac > 0){ $arregloDatos['datos_remesa'] .= "E:".number_format($unLevante->peso_nonac,2,',','.'); }
		if($unLevante->peso_naci > 0){ $arregloDatos['datos_remesa'] .= "N:".number_format($unLevante->peso_naci,2,',','.'); }
		
		$arregloDatos['datos_remesa'] .= "Valor:";
		if($unLevante->fob_nonac > 0){ $arregloDatos['datos_remesa'] .= "U$:".number_format($unLevante->fob_nonac,2,',','.'); }
		if($unLevante->cif > 0){ $arregloDatos['datos_remesa'] .= "$:".number_format($unLevante->cif,2,',','.'); }		
  }

  function getDatosRemesa($arregloDatos) {
    if(empty($arregloDatos['datos_remesa'])) { $arregloDatos['datos_remesa']=-1; }
    $sql = "SELECT ABS(SUM(peso_naci)) AS peso_naci, ABS(SUM( peso_nonac)) AS peso_nonac, ABS(SUM( cantidad_naci)) AS cantidad_naci,
              ABS( SUM( cantidad_nonac)) AS cantidad_nonac, ABS(SUM( cif)) AS cif , ABS( SUM( fob_nonac)) AS fob_nonac
            FROM inventario_maestro_movimientos imm, inventario_movimientos im
            WHERE imm.codigo = im.cod_maestro
              AND imm.codigo = $arregloDatos[datos_remesa]
            GROUP BY imm.codigo
              LIMIT 0, 30";
      
    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo "<div class=error align=center> :( Error al Consultar Datos de la remesa <br>$sql</div>.";
      return FALSE;
    } else {
      $dato = $this->db->fetch();
      $arregloDatos['datos_remesa'] .= "Piezas:";

      if($dato->cantidad_nonac > 0){ $arregloDatos['datos_remesa'] .= "E:". number_format($dato->cantidad_nonac,2,',','.'); }
      if($dato->cantidad_naci > 0){ $arregloDatos['datos_remesa'] .= "N:".number_format($dato->cantidad_naci,2,',','.'); }

      $arregloDatos['datos_remesa'] .= "Peso:";
      if($dato->peso_nonac > 0){ $arregloDatos['datos_remesa'] .= "E:".number_format($dato->peso_nonac,2,',','.'); }
      if($dato->peso_naci > 0){ $arregloDatos['datos_remesa'] .= "N:".number_format($dato->peso_naci,2,',','.'); }

      $arregloDatos['datos_remesa'] .= "Valor:";
      if($dato->fob_nonac > 0){ $arregloDatos['datos_remesa'] .= "U$:".number_format($dato->fob_nonac,2,',','.'); }
      if($dato->cif > 0){ $arregloDatos['datos_remesa'] .= "$:".number_format($dato->cif,2,',','.'); }

      echo $arregloDatos['datos_remesa'];      
    }
  }
  
  function getTarifas($arregloDatos) {
    $sql = "SELECT plena,minima,agente FROM servicios WHERE referencia= $arregloDatos[referencia]";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo "<div class=error align=center> :( Error al consultar las tarifas <br>$sql</div>.";
      return FALSE;
    }
  }
   
  function getNumeroRetiro($arregloDatos) {
    $sql = "SELECT codigo,prefactura,doc_tte FROM inventario_maestro_movimientos WHERE doc_tte= $arregloDatos[num_prefactura]";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo "<div class=error align=center> :( Error al consultar las tarifas <br>$sql</div>.";
      return FALSE;
    } else {
      $dato = $this->db->fetch();
      return $dato->codigo;      
    }
  }
   
  function getTipoSede($arregloDatos) {
 		$sede = $_SESSION['sede'];
 		$sql = "SELECT tipo_sede FROM sedes WHERE codigo='$sede'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo "<div class=error align=center> :( Error al consultar las tarifas <br>$sql</div>.";
      return FALSE;
    }
  }
   
  function getNitSede($arregloDatos) { // se busca el NIT de la sede en la tabla cliente, elcampo telefonos_fases debe tener la sede
    $sede = $_SESSION['sede'];
    $sql = "SELECT numero_documento FROM clientes WHERE telefonos_faxes='$sede'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo "<div class=error align=center> :( Error al consultar el nit de la sede <br>$sql</div>.";
      return FALSE;
    }
  }
   
  function getDatosCliente($arregloDatos) { // 
    $sede = $_SESSION['sede'];
    $sql = "SELECT * FROM clientes WHERE numero_documento='$arregloDatos[facturado_a_nit]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
  		echo "<div class=error align=center> :( Error al consultar los datos del cliente <br>$sql</div>.";
  		return FALSE;
    }
  }

  function getPlaca($arregloDatos) { // 
    $sql = "SELECT * FROM camiones WHERE placa='AAA111'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo "<div class=error align=center> :( Error al consultar la placa default <br>$sql</div>.";
      return FALSE;
    }
  }
   
  function setOrden($arregloDatos) { // 
    $sql = "UPDATE inventario_maestro_movimientos SET orden='$arregloDatos[una_orden]' WHERE codigo=$arregloDatos[id_levante]";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo "<div class=error align=center> :( Error al actualizar el n&uacute;mero de orden <br>$sql</div>.";
      return FALSE;
    }
  }  
}  
?>