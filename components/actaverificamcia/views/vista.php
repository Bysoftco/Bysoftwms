<?php
require_once(COMPONENTS_PATH.'actaverificamcia/model/actaverificamcia.php');

class actaverVista {
  var $template;
  var $datos;
	
  function actaverVista() {
    $this->template = new HTML_Template_IT();
    $this->datos = new actaverModelo();
  }
  
	function verActa($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'actaverificamcia/views/tmpl/verActavm.php' );
    $this->template->setVariable('COMODIN', '');

    $this->template->setVariable('fecha_do',$arreglo['fecham']);
    $this->template->setVariable('doc_tte',$arreglo['doctte']);
    $this->template->setVariable('arribo',$arreglo['arribo']);
    $hoy = date('Y-m-d');
    $this->template->setVariable('salida',$hoy);
    $this->template->setVariable('orden',$arreglo['orden']);
    $this->template->setVariable('cliente',$arreglo['cliente']);
    $this->template->setVariable('conductor',$arreglo['conductor']);
    $this->template->setVariable('cc',$arreglo['idcc']);
    $this->template->setVariable('placa',$arreglo['placa']);
    if($arreglo['ind_cons']=='No') {
      $this->template->setVariable('ccarga1_si','');
      $this->template->setVariable('ccarga1_no','X');
    } else {
      $this->template->setVariable('ccarga1_si','X');
      $this->template->setVariable('ccarga1_no','');      
    }
    if($arreglo['ind_hielo']=='No') {
      $this->template->setVariable('ccarga2_si','');
      $this->template->setVariable('ccarga2_no','X');
    } else {
      $this->template->setVariable('ccarga2_si','X');
      $this->template->setVariable('ccarga2_no','');      
    }
    if($arreglo['ind_fragil']=='No') {
      $this->template->setVariable('ccarga3_si','');
      $this->template->setVariable('ccarga3_no','X');
    } else {
      $this->template->setVariable('ccarga3_si','X');
      $this->template->setVariable('ccarga3_no','');      
    }
    if($arreglo['ind_asig']=='No') {
      $this->template->setVariable('ccarga4_si','');
      $this->template->setVariable('ccarga4_no','X');
    } else {
      $this->template->setVariable('ccarga4_si','X');
      $this->template->setVariable('ccarga4_no','');      
    }
    if($arreglo['ind_venci']=='No') {
      $this->template->setVariable('ccarga5_si','');
      $this->template->setVariable('ccarga5_no','X');
    } else {
      $this->template->setVariable('ccarga5_si','X');
      $this->template->setVariable('ccarga5_no','');      
    }
    if($arreglo['ind_ubica']=='No') {
      $this->template->setVariable('ccarga6_si','');
      $this->template->setVariable('ccarga6_no','X');
    } else {
      $this->template->setVariable('ccarga6_si','X');
      $this->template->setVariable('ccarga6_no','');      
    }
        
    $this->template->show();
	}
  	
	function imprimirActa($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'actaverificamcia/views/tmpl/imprimirActavm.php' );
    $this->template->setVariable('COMODIN', '');

    $this->template->setVariable('fecha_do',$arreglo['fecham']);
    $this->template->setVariable('version',$arreglo['version']);
    $this->template->setVariable('doc_tte',$arreglo['doctte']);
    $this->template->setVariable('arribo',$arreglo['arribo']);
    $this->template->setVariable('salida',$arreglo['salida']);
    $this->template->setVariable('orden',$arreglo['orden']);
    $this->template->setVariable('cliente',$arreglo['cliente']);
    $this->template->setVariable('conductor',$arreglo['conductor']);
    $this->template->setVariable('cc',$arreglo['idcc']);
    $this->template->setVariable('placa',$arreglo['placa']);
    $this->template->setVariable('ccarga1_si',$arreglo['ccarga1_si']);
    $this->template->setVariable('ccarga1_no',$arreglo['ccarga1_no']);
    $this->template->setVariable('ccarga2_si',$arreglo['ccarga2_si']);
    $this->template->setVariable('ccarga2_no',$arreglo['ccarga2_no']);
    $this->template->setVariable('ccarga3_si',$arreglo['ccarga3_si']);
    $this->template->setVariable('ccarga3_no',$arreglo['ccarga3_no']);
    $this->template->setVariable('ccarga4_si',$arreglo['ccarga4_si']);
    $this->template->setVariable('ccarga4_no',$arreglo['ccarga4_no']);
    $this->template->setVariable('ccarga5_si',$arreglo['ccarga5_si']);
    $this->template->setVariable('ccarga5_no',$arreglo['ccarga5_no']);
    $this->template->setVariable('ccarga6_si',$arreglo['ccarga6_si']);
    $this->template->setVariable('ccarga6_no',$arreglo['ccarga6_no']);
        
    $this->template->show();
	}
}
?>