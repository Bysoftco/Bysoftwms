<?php
require_once(COMPONENTS_PATH.'existencias/model/existencias.php');

class ExistenciasVista {
  var $template;
  var $datos;
	
  function ExistenciasVista() {
    $this->template = new HTML_Template_IT();
    $this->datos = new ExistenciasModelo();
  }
  
	function filtroExistencias($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'existencias/views/tmpl/filtroExistencias.php' );
    $this->template->setVariable('COMODIN', '');
    
    // Carga información del Perfil y Usuario
    $arreglo[perfil] = $_SESSION['datos_logueo']['perfil_id'];
    $arreglo[usuario] = $_SESSION['datos_logueo']['usuario'];
    // Valida el Perfil para identificar el Tercero
    if($arreglo[perfil] == 23) {
      $this->template->setVariable(soloLectura, "readonly=''");
      $this->template->setVariable(usuario, $arreglo[usuario]);
      $cliente = $this->datos->findClientet($arreglo[usuario]);
      $this->template->setVariable(cliente, $cliente->razon_social);
    } else {
      $this->template->setVariable(soloLectura, "");
      $this->template->setVariable(usuario, "");
      $this->template->setVariable(cliente, "");
    }
    
    $this->template->show();
	}
  	
  function listadoExistencias($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'existencias/views/tmpl/listadoExistencias.php' );
    $this->template->setVariable('COMODIN', '');
    
    //Datos de Filtro para Impresión
    $this->template->setVariable('buscarClientefe', $arreglo['buscarClientefe']);
    $this->template->setVariable('nitfe', $arreglo['nitfe']);
    $this->template->setVariable('fechadesdefe', $arreglo['fechadesdefe']);
    $this->template->setVariable('fechahastafe', $arreglo['fechahastafe']);
    $this->template->setVariable('doasignadofe', $arreglo['doasignadofe']);
    $this->template->setVariable('tipoingresofe', $arreglo['tipoingresofe']);

    $n = 1; $tpiezas = $tpeso = $tvalor = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', $n);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('manifiesto', $value['manifiesto']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('modelo', $value['modelo']);
      $this->template->setVariable('fecha_ingreso', $value['fecha']);
      $this->template->setVariable('nombre_ubicacion', isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR');
      $this->template->setVariable('tipo_ingreso', $value['ingreso']);
      $this->template->setVariable('piezas', number_format($value['cantidad'],2));
      $this->template->setVariable('peso', number_format($value['peso'],2));
      $this->template->setVariable('valor', number_format($value['valor'],2));
      $this->template->setVariable('piezas_nal', number_format($value['c_nal'],2));
      $this->template->setVariable('piezas_ext', number_format($value['c_ext'],2));
      //Acumula Totales
      $tpiezas += $value['cantidad']; $tpeso += $value['peso']; $tvalor += $value['valor'];
      $tpiezas_nal += $value['c_nal']; $tpiezas_ext += $value['c_ext']; $n++;
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('total_piezas', number_format($tpiezas,2));
    $this->template->setVariable('total_peso', number_format($tpeso,2));
    $this->template->setVariable('total_valor', number_format($tvalor,2));
    $this->template->setVariable('total_piezas_nal', number_format($tpiezas_nal,2));
    $this->template->setVariable('total_piezas_ext', number_format($tpiezas_ext,2));
    
    $this->template->show();
  }

  function imprimeListadoExistencias($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'existencias/views/tmpl/verListadoExistencias.php' );
    $this->template->setVariable('COMODIN', '');

    $n = 1; $tpiezas = $tpeso = $tvalor = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', $n);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('manifiesto', $value['manifiesto']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('modelo', $value['modelo']);
      $this->template->setVariable('fecha_ingreso', $value['fecha']);
      $this->template->setVariable('nombre_ubicacion', isset($value['nombre_ubicacion'])?$value['nombre_ubicacion']:'POR ASIGNAR');
      $this->template->setVariable('tipo_ingreso', $value['ingreso']);
      $this->template->setVariable('piezas', number_format($value['cantidad'],2));
      $this->template->setVariable('peso', number_format($value['peso'],2));
      $this->template->setVariable('valor', number_format($value['valor'],2));
      $this->template->setVariable('piezas_nal', number_format($value['c_nal'],2));
      $this->template->setVariable('piezas_ext', number_format($value['c_ext'],2));
      //Acumula Totales
      $tpiezas += $value['cantidad']; $tpeso += $value['peso']; $tvalor += $value['valor'];
      $tpiezas_nal += $value['c_nal']; $tpiezas_ext += $value['c_ext']; $n++;
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('total_piezas', number_format($tpiezas,2));
    $this->template->setVariable('total_peso', number_format($tpeso,2));
    $this->template->setVariable('total_valor', number_format($tvalor,2));
    $this->template->setVariable('total_piezas_nal', number_format($tpiezas_nal,2));
    $this->template->setVariable('total_piezas_ext', number_format($tpiezas_ext,2));
    
    $this->template->show();
  }
}
?>