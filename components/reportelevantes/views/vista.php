<?php
require_once(COMPONENTS_PATH.'reportelevantes/model/reportelevantes.php');

class ReportelevantesVista {
  var $template;
  var $datos;
	
  function ReportelevantesVista() {
    $this->template = new HTML_Template_IT();
    $this->datos = new ReportelevantesModelo();
  }
  
	function filtroReportelevantes($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'reportelevantes/views/tmpl/filtroReportelevantes.php' );
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
  	
  function listadoReportelevantes($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'reportelevantes/views/tmpl/listadoReportelevantes.php' );
    $this->template->setVariable('COMODIN', '');
    
    //Conservación datos del filtro
    $this->template->setVariable('nitrl',$arreglo[nitrl]);
    $this->template->setVariable('fechadesderl',$arreglo[fechadesderl]);
    $this->template->setVariable('fechahastarl',$arreglo[fechahastarl]);   
    $this->template->setVariable('doctterl',$arreglo[doctterl]);
    $this->template->setVariable('doasignadorl',$arreglo[doasignadorl]);
    $this->template->setVariable('referenciarl',$arreglo[referenciarl]);

    $n = 0; $tr = count($arreglo['datos']);
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', ++$n);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('fecha_naci', $value['fecha']);
      $this->template->setVariable('piezas', number_format($value['piezas'],2));
      $this->template->setVariable('peso', number_format($value['peso'],2));
      $this->template->setVariable('valor', number_format($value['valor'],2));
      $this->template->setVariable('ancho', $value['ancho']);
      $this->template->setVariable('partida', $value['partida']);
      $this->template->setVariable('trm', $value['trm']);
      $this->template->setVariable('fletes', $value['fletes']);
      $this->template->setVariable('arancel', $value['arancel']);
      $this->template->setVariable('iva', $value['iva']);
      $this->template->parseCurrentBlock("ROW");
    }
    
    $this->template->show();
  }
}
?>