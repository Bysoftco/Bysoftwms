<?php
require_once(COMPONENTS_PATH.'cargueInventarios/model/cargueInventarios.php');

class cargueInventariosVista {
  var $template;
  var $datos;
	
  function cargueInventariosVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datos = new cargueInventariosModelo();
  }
  
	function filtrocargueInventarios($arreglo) {
    $this->template->loadTemplateFile('cargueInventarios/views/tmpl/filtrocargueInventarios.php');
    $this->template->setVariable('COMODIN', '');
        
    $this->template->show();
	}
  	
  function validaDatos($arreglo) {
    $this->template->loadTemplateFile('cargueInventarios/views/tmpl/validaDatos.php');
    $this->template->setVariable('COMODIN', '');

    $n = 0;
    //Mostrar registros con novedades    
    foreach($arreglo['datos'] as $value) {
      $n++;
      if($value['O']!="" && $value['O']!='observacion') {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('n', $n);
        $this->template->setVariable('arribo', $value['A']);
        $this->template->setVariable('orden', $value['B']);
        $this->template->setVariable('fecha', $value['C']);
        $this->template->setVariable('referencia', $value['D']);
        $this->template->setVariable('cantidad', $value['E']);
        $this->template->setVariable('peso', $value['F']);
        $this->template->setVariable('valor', $value['G']);
        $this->template->setVariable('fmm', $value['H']);
        $this->template->setVariable('modelo', $value['I']);
        $this->template->setVariable('embalaje', $value['J']);
        $this->template->setVariable('unimedida', $value['K']);
        $this->template->setVariable('posicion', $value['L']);
        $this->template->setVariable('cant_declaraciones', $value['M']);
        $this->template->setVariable('observacion', $value['N']);
        $this->template->setVariable('fecha_expira', $value['O']);
        $this->template->parseCurrentBlock("ROW");
      }
    }
    
    $this->template->show();
  }
}
?>