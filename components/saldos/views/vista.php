<?php
require_once(COMPONENTS_PATH.'saldos/model/saldos.php');

class SaldosVista {
  var $template;
  var $datos;
	
  function SaldosVista() {
    $this->template = new HTML_Template_IT();
    $this->datos = new SaldosModelo();
  }
  
	function filtroSaldos($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'saldos/views/tmpl/filtroSaldos.php' );
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
  	
  function listadoSaldos($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'saldos/views/tmpl/listadoSaldos.php' );
    $this->template->setVariable('COMODIN', '');
    
    //Datos de Filtro para Impresión
    $this->template->setVariable('buscarClientefs', $arreglo['buscarClientefs']);
    $this->template->setVariable('nitfs', $arreglo['nitfs']);
    $this->template->setVariable('fechadesdefs', $arreglo['fechadesdefs']);
    $this->template->setVariable('fechahastafs', $arreglo['fechahastafs']);
    $this->template->setVariable('doasignadofs', $arreglo['doasignadofs']);

    $n = 1; $tpiezas = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', $n);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('ucomercial', $value['ucomercial']);
      $this->template->setVariable('fecha_expira', $value['fecha_expira']);
      $this->template->setVariable('piezas', number_format(abs($value['cantidad']),2));
      $this->template->setVariable('piezas_nal', number_format(abs($value['c_nal']),2));
      $this->template->setVariable('piezas_ext', number_format(abs($value['c_ext']),2));
	  
	  $this->template->setVariable('c_ret_nal', number_format(abs($value['c_ret_nal']),2));
	  $this->template->setVariable('c_ret_ext', number_format(abs($value['c_ret_ext']),2));
	  
      $this->template->setVariable('saldo_piezas', number_format(abs($value['c_nal']+$value['c_ext']),2));
      //Acumula Totales
      $tpiezas += $value['cantidad']; $tpiezas_nal += $value['c_nal'];
      $tpiezas_ext += $value['c_ext']; $n++;
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('total_piezas', number_format($tpiezas,2));
    $this->template->setVariable('total_piezas_nal', number_format($tpiezas_nal,2));
    $this->template->setVariable('total_piezas_ext', number_format($tpiezas_ext,2));
    $this->template->setVariable('total_saldo_piezas', number_format($tpiezas_nal+$tpiezas_ext,2));
    
    $this->template->show();
  }

  function imprimeListadoSaldos($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'saldos/views/tmpl/verListadoSaldos.php' );
    $this->template->setVariable('COMODIN', '');

    $n = 1; $tpiezas = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', $n);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('ucomercial', $value['ucomercial']);
      $this->template->setVariable('fecha_expira', $value['fecha_expira']);
      $this->template->setVariable('piezas', number_format($value['cantidad'],2));
      $this->template->setVariable('piezas_nal', number_format($value['c_nal'],2));
      $this->template->setVariable('piezas_ext', number_format($value['c_ext'],2));
      $this->template->setVariable('saldo_piezas', number_format($value['c_nal']+$value['c_ext'],2));
      //Acumula Totales
      $tpiezas += $value['cantidad']; $tpiezas_nal += $value['c_nal'];
      $tpiezas_ext += $value['c_ext']; $n++;
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('total_piezas', number_format($tpiezas,2));
    $this->template->setVariable('total_piezas_nal', number_format($tpiezas_nal,2));
    $this->template->setVariable('total_piezas_ext', number_format($tpiezas_ext,2));
    $this->template->setVariable('total_saldo_piezas', number_format($tpiezas_nal+$tpiezas_ext,2));
    
    $this->template->show();
  }
}
?>