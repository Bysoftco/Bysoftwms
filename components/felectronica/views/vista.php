<?php
require_once COMPONENTS_PATH . 'felectronica/model/felectronica.php';
require_once COMPONENTS_PATH . 'felectronica/views/tmpl/montoEscrito.php';

class felectronicaVista {
  var $template;
  var $datos;

  function felectronicaVista() {
    $this->template = new HTML_Template_IT();
    $this->datos = new felectronicaModelo();
  }

  function filtroFacturas($arreglo) {
    $this->template->loadTemplateFile(COMPONENTS_PATH . 'felectronica/views/tmpl/filtroFacturas.php');
    $this->template->setVariable('COMODIN', '');

    // Carga información del Perfil y Usuario
    $arreglo[perfil] = $_SESSION['datos_logueo']['perfil_id'];
    $arreglo[usuario] = $_SESSION['datos_logueo']['usuario'];
    // Valida el Perfil para identificar el Tercero
    if($arreglo[perfil] == 23) {
      $this->template->setVariable('soloLectura', "readonly=''");
      $this->template->setVariable(usuario, $arreglo[usuario]);
      $cliente = $this->datos->findClientet($arreglo[usuario]);
      $this->template->setVariable(cliente, $cliente->razon_social);
    } else {
      $this->template->setVariable('soloLectura', "");
      $this->template->setVariable(usuario, "");
      $this->template->setVariable(cliente, "");
    }

    $this->template->show();
  }

  function listadoFacturas($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'felectronica/views/tmpl/listadoFacturas.php' );
    $this->template->setVariable('COMODIN', '');
    
    //Datos de Filtro para Impresión
    $this->template->setVariable('buscarClientefel', $arreglo['buscarClientefel']);
    $this->template->setVariable('nitfel', $arreglo['nitfel']);
    $this->template->setVariable('fechadesdefel', $arreglo['fechadesdefel']);
    $this->template->setVariable('fechahastafel', $arreglo['fechahastafel']);
    $this->template->setVariable('facturafiltrofel', $arreglo['facturafiltrofel']);
    $this->template->setVariable('dofiltrofel', $arreglo['dofiltrofel']);
    $this->template->setVariable('docfiltrofel', $arreglo['docfiltrofel']);

    $tsubtotal = $tiva = $tvalor = 0;
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      //$this->template->setVariable('n', $n);
      $this->template->setVariable('factura', $value['numero_oficial']);
      $this->template->setVariable('fecha', $value['fecha_factura']);
      $this->template->setVariable('nit', $value['por_cuenta']);
      $this->template->setVariable('cliente', $value['razon_social']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('documento', $value['documento_transporte']);
      $this->template->setVariable('subtotal', number_format(round($value['subtotal']),0));
      $this->template->setVariable('iva', number_format(round($value['iva']),0));
      $this->template->setVariable('total', number_format(round($value['total']),0));
      //Acumula Totales
      $tsubtotal += round($value['subtotal']); $tiva += round($value['iva']); $tvalor += round($value['total']);
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('total_subtotal', number_format($tsubtotal,0));
    $this->template->setVariable('total_iva', number_format($tiva,0));
    $this->template->setVariable('total_valor', number_format($tvalor,0));
    
    $this->template->show();
  }

  function verFactura($arreglo) {
    $this->template->loadTemplateFile(COMPONENTS_PATH . 'felectronica/views/tmpl/verFactura.php');
    $this->template->setVariable('COMODIN', '');

    //Preparación para visualizar datos de Cliente    
    $this->template->setVariable('facturaw',$arreglo['datosFactura']['numero_oficial']);
    $this->template->setVariable('razonsocial',$arreglo['datosFactura']['razon_social']);
    $this->template->setVariable('nroDoc',$arreglo['datosFactura']['numero_documento']);
    $this->template->setVariable('direccion',$arreglo['datosFactura']['direccion']);
    $this->template->setVariable('correo',$arreglo['datosFactura']['correo_electronico']);
    $this->template->setVariable('telefono',$arreglo['datosFactura']['telefonos_fijos']);
    // Validación del Régimen
    if($arreglo['datosFactura']['regimen']==2) {
      $this->template->setVariable('regimen',$arreglo['datosFactura']['regimen']);
      $this->template->setVariable('idregimen','comun');
    } else {
      $this->template->setVariable('regimen',0);
      $this->template->setVariable('idregimen','simplificado');
    }
    $this->template->setVariable('dpto',$arreglo['datosFactura']['dpto']);
    $this->template->setVariable('ciudad',$arreglo['datosFactura']['ciudad']);
    //Conversión Formato de Fecha de AAAA-MM-DD a DD-MMM-AAAA
    $meses = array("","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
    $mes = date("n",strtotime($arreglo['datosFactura']['fecha_f']));
    $dia = date("d",strtotime($arreglo['datosFactura']['fecha_f']));
    $ano = date("Y",strtotime($arreglo['datosFactura']['fecha_f']));
    $ffactura = $dia."-".$meses[$mes]."-".$ano;
    $this->template->setVariable('fechaemision',$arreglo['datosFactura']['fecha_f']);
    $this->template->setVariable('horaemision',$arreglo['datosFactura']['hora_f']);
    $this->template->setVariable('fechafactura',$ffactura);
    //Validación Forma de Pago
    if($arreglo['datosFactura']['efectivo']==1) {
      $this->template->setVariable('idmediopago','efectivo');
      $this->template->setVariable('codmediopago','10');
    } else {
      $this->template->setVariable('idmediopago','cheque');
      $this->template->setVariable('codmediopago','20');
    }
    $n = 1;
    //Preparación para visualizar Detalle de Factura
    foreach($arreglo['detalleFactura'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('numero',$n++);
      $this->template->setVariable('codigo',$value['cuenta']);
      $this->template->setVariable('descripcion',$value['nombre_servicio']);
      $this->template->setVariable('medida',$value['concep_tarifa']);
      $this->template->setVariable('base',number_format($value['base'],2,".",","));
      $this->template->setVariable('iva_detalle',$value['iva']);
      $this->template->setVariable('porcentaje',$value['porcentaje']);
      $this->template->setVariable('preciounitario',round($value['valor_unitario']));
      $this->template->setVariable('vrunitario',number_format(round($value['valor_unitario']),2,".",","));
      $this->template->setVariable('cantidad',$value['cantidad']);
      $this->template->setVariable('cantf',number_format($value['cantidad'],2,".",","));
      $ivacalc = $value['valor']*$value['iva']/100;
      $this->template->setVariable('iva_item',round($ivacalc));
      $vrtotal = $value['valor_unitario']*$value['cantidad'];
      $this->template->setVariable('vrtotal',round($vrtotal));
      $this->template->setVariable('vtotal',number_format(round($vrtotal),2,".",","));
      $videtalle = $value['valor_unitario']+$ivacalc;
      $this->template->setVariable('videtalle',$videtalle);
      $this->template->parseCurrentBlock("ROW");
    }
    //Muestra Totales
    $this->template->setVariable('subtotal',round($arreglo['datosFactura']['subtotal']));
    $this->template->setVariable('subtotalf',number_format(round($arreglo['datosFactura']['subtotal']),2,".",","));
    $this->template->setVariable('iva',round($arreglo['datosFactura']['iva']));
    $this->template->setVariable('ivaf',number_format(round($arreglo['datosFactura']['iva']),2,".",","));
    $this->template->setVariable('valor_anticipo',round($arreglo['datosFactura']['valor_anticipo']));
    $this->template->setVariable('valor_anticipof',number_format(round($arreglo['datosFactura']['valor_anticipo']),2,".",","));
    $this->template->setVariable('total',round($arreglo['datosFactura']['total']));
    $this->template->setVariable('totalf',number_format(round($arreglo['datosFactura']['total']),2,".",","));
    $this->template->setVariable('rte_fuente',round($arreglo['datosFactura']['rte_fuente']));
    $this->template->setVariable('rte_fuentef',number_format(round($arreglo['datosFactura']['rte_fuente']),2,".",","));
    $this->template->setVariable('rte_ica',round($arreglo['datosFactura']['rte_ica']));
    $this->template->setVariable('rte_icaf',number_format(round($arreglo['datosFactura']['rte_ica']),2,".",","));
    $this->template->setVariable('rte_iva',round($arreglo['datosFactura']['rte_iva']));
    $this->template->setVariable('rte_ivaf',number_format(round($arreglo['datosFactura']['rte_iva']),2,".",","));
    $this->template->setVariable('neto',round($arreglo['datosFactura']['neto']));
    $this->template->setVariable('netof',number_format(round($arreglo['datosFactura']['neto']),2,".",","));
    //Monto Escrito
    $monto = new EnLetras();
    $vr_letras = $monto->ValorEnLetras(round($arreglo['datosFactura']['total']),'Pesos');
    $this->template->setVariable('monto_letras',utf8_encode(strtoupper($vr_letras)));

    $this->template->show();
  }
}
?>