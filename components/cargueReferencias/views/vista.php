<?php
require_once(COMPONENTS_PATH.'cargueReferencias/model/cargueReferencias.php');

class cargueReferenciasVista {
  var $template;
  var $datos;
	
  function cargueReferenciasVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datos = new cargueReferenciasModelo();
  }
  
	function filtrocargueReferencias($arreglo) {
    $this->template->loadTemplateFile('cargueReferencias/views/tmpl/filtrocargueReferencias.php');
    $this->template->setVariable('COMODIN', '');
        
    $this->template->show();
	}
  	
  function validaDatos($arreglo) {
    $this->template->loadTemplateFile('cargueReferencias/views/tmpl/validaDatos.php');
    $this->template->setVariable('COMODIN', '');

    $n = 0;
    //Mostrar registros con novedades    
    foreach($arreglo['datos'] as $value) {
      $n++;
      if($value['D']!="" && $value['D']!='observaciones') {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('n', $n);
        $this->template->setVariable('codigo_ref', $value['A']);
        $this->template->setVariable('subpartidas', $value['B']);
        $this->template->setVariable('nombre', $value['C']);
        $this->template->setVariable('observaciones', $value['D']);
        $this->template->setVariable('cliente', $value['E']);
        $this->template->setVariable('parte_numero', $value['F']);
        $this->template->setVariable('unidad', $value['G']);
        $this->template->setVariable('Embalaje', $value['H']);
        $this->template->setVariable('U_empaque', $value['I']);
        $this->template->setVariable('fecha_expira', $value['J']);
        $this->template->setVariable('vigencia', $value['K']);
        $this->template->setVariable('min_stock', $value['L']);
        $this->template->setVariable('lote_cosecha', $value['M']);
        $this->template->setVariable('alto', $value['N']);
        $this->template->setVariable('largo', $value['O']);
        $this->template->setVariable('ancho', $value['P']);
        $this->template->setVariable('serial', $value['Q']);
        $this->template->setVariable('tipo', $value['R']);
        $this->template->setVariable('grupo_item', $value['S']);
        $this->template->setVariable('factor_conversion', $value['T']);
        $this->template->parseCurrentBlock("ROW");
      }
    }
    
    $this->template->show();
  }
}
?>