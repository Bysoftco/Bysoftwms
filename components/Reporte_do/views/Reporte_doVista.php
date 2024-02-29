<?php
require_once COMPONENTS_PATH.'Reporte_do/model/Reporte_doDatos.php';

class Reporte_doVista {
  var $template;
  var $datos;

  function Reporte_doVista() {
    $this->template = new HTML_Template_IT();
    $this->datos = new Reporte_doDatos();
  }

  function filtroReporte($arreglo) {
    $this->template->loadTemplateFile(COMPONENTS_PATH . 'Reporte_do/views/tmpl/filtroReporte.php');
    $this->template->setVariable('COMODIN', '');

    // Carga información del Perfil y Usuario
    $arreglo['perfil'] = $_SESSION['datos_logueo']['perfil_id'];
    $arreglo['usuario'] = $_SESSION['datos_logueo']['usuario'];
    // Valida el Perfil para identificar el Tercero
    if($arreglo['perfil'] == 23) {
      $this->template->setVariable('soloLectura', "readonly=''");
      $this->template->setVariable('usuario', $arreglo['usuario']);
      $cliente = $this->datos->findClientet($arreglo['usuario']);
      $this->template->setVariable('cliente', $cliente->razon_social);
    } else {
      $this->template->setVariable('soloLectura', "");
      $this->template->setVariable('usuario', "");
      $this->template->setVariable('cliente', "");
    }

    $listaAgencias = $this->datos->build_list("clientes", "numero_documento", "razon_social", " WHERE tipo = 4 ");
    $selectAgencias = $this->datos->armSelect($listaAgencias ,'Seleccione Opción...');
    $this->template->setVariable('select_agencias', $selectAgencias);

    $this->template->show();
  }

  function mostrarListadoOrdenes($arreglo) {
    $this->template->loadTemplateFile(COMPONENTS_PATH.'Reporte_do/views/tmpl/listadoDo.php');
    $this->template->setVariable('COMODIN', '');

    //Datos de Filtro para Exportar a Excel
    $this->template->setVariable('docCliente', $arreglo['docCliente']);
    $this->template->setVariable('do', $arreglo['do']);
    $this->template->setVariable('doc_transporte', $arreglo['doc_transporte']);
    $this->template->setVariable('modelo', $arreglo['modelo']);
    $this->template->setVariable('fecha_desde', $arreglo['fecha_desde']);
    $this->template->setVariable('fecha_hasta', $arreglo['fecha_hasta']);

    $datosLista = $this->datos->retornarInfoOrdenes($arreglo);
    
    foreach($datosLista as $value) {
      $control = $this->datos->retornarControl($value['orden']);
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('doc_cliente', $value['doc_cliente']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('modelo', $value['modelo']);
      $this->template->setVariable('controles', isset($control->item_control) ? $control->item_control." - ".$control->entidad : "");
      $this->template->setVariable('piezas', number_format($value['piezas'],2));
      $this->template->setVariable('peso', number_format($value['peso'],2));
      $this->template->parseCurrentBlock("ROW");
    }

    $this->template->show();
  }

  function verDetalleDo($arreglo) {
    $this->template->loadTemplateFile(COMPONENTS_PATH . 'Reporte_do/views/tmpl/detalleDo.php');
    $this->template->setVariable('COMODIN', '');

    $datosOrden = $this->datos->retornarInfoOrden($arreglo);
        
    $this->template->setVariable('numero_orden', isset($datosOrden->orden)?$datosOrden->orden:"");
    $this->template->setVariable('doc_transporte', isset($datosOrden->doc_tte)?$datosOrden->doc_tte:"");
    $this->template->setVariable('doc_cliente', isset($datosOrden->numero_documento)?$datosOrden->numero_documento:"");
    $this->template->setVariable('nombre_cliente', isset($datosOrden->razon_social)?$datosOrden->razon_social:"");
    $this->template->setVariable('modelo', isset($datosOrden->modelo)?$datosOrden->modelo:"");
    $control = $this->datos->retornarControl($arreglo['orden']);
    $this->template->setVariable('nombre_control', isset($control->item_control)?$control->item_control:"");
    $this->template->setVariable('nombre_entidad', isset($control->entidad)?$control->entidad:"");

    $datosMovimiento = $this->datos->retornarInfoMovimientos($arreglo['orden']);

    $primera = 0; $cod_ultima = $nom_ultima = $doc_agencia = $nom_agencia = "";
    foreach($datosMovimiento as $value) {
      $this->template->setCurrentBlock("ROW");
      if($primera == 0) {
        $cod_ultima = $value['num_operacion'];
        $nom_ultima = $value['nombre_tipo_movimiento'];
      }
      if($value['tipo_movimiento'] == 2) {
        $doc_agencia = $value['lev_sia'];
        $nom_agencia = $value['nom_sia'];      
      }
      $this->template->setVariable('operacion', $value['nombre_tipo_movimiento']);
      $this->template->setVariable('num_operacion', $value['num_operacion']);
      $fecha = date_create($value['fecha_operacion']);
      $this->template->setVariable('fecha_operacion', date_format($fecha,'Y-m-d H:i'));
      $this->template->setVariable('cod_referencia', $value['codigo_ref']);
      $this->template->setVariable('nom_referencia', $value['nombre_ref']);
      $this->template->setVariable('fmm', $value['fmm']);
      $this->template->setVariable('piezas', $value['piezas']);
      $this->template->setVariable('unidad_comercial', $value['unidad_comercial']);
      $this->template->parseCurrentBlock("ROW");
      $primera = 1;
    }
    $this->template->setVariable('cod_ultima', $cod_ultima);
    $this->template->setVariable('nom_ultima', $nom_ultima);
    $this->template->setVariable('doc_agencia', $doc_agencia);
    $this->template->setVariable('nom_agencia', $nom_agencia);

    $this->template->show();
  }
}
?>
