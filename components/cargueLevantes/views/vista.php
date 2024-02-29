<?php
require_once(COMPONENTS_PATH.'cargueLevantes/model/cargueLevantes.php');

class cargueLevantesVista {
  var $template;
  var $datos;
	
  function cargueLevantesVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datos = new cargueLevantesModelo();
  }
  
	function filtrocargueLevantes($arreglo) {
    $this->template->loadTemplateFile('cargueLevantes/views/tmpl/filtrocargueLevantes.php');
    $this->template->setVariable('COMODIN', '');
        
    $this->template->show();
	}
  	
  function validaDatos($arreglo) {
    $this->template->loadTemplateFile('cargueLevantes/views/tmpl/validaDatos.php');
    $this->template->setVariable('COMODIN', '');

    $n = 0;
    //Mostrar registros con novedades    
    foreach($arreglo['datos'] as $value) {
      $n++;
      if($value['F']!="" && $value['F']!='observacion') {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('n', $n);
        $this->template->setVariable('inventario_entrada', $value['A']);
        $this->template->setVariable('fecha', $value['B']);
        $this->template->setVariable('cod_maestro', $value['C']);
        $this->template->setVariable('num_declaracion', $value['D']);
        $this->template->setVariable('tipo_declaracion', $value['E']);
        $this->template->setVariable('subpartida', $value['F']);
        $this->template->setVariable('modalidad', $value['G']);
        $this->template->setVariable('trm', $value['H']);
        $this->template->setVariable('cantidad_nonac', $value['I']);
        $this->template->setVariable('fob', $value['J']);
        $this->template->setVariable('fletes', $value['K']);
        $this->template->setVariable('arancel', $value['L']);
        $this->template->setVariable('iva', $value['M']);
        $this->template->setVariable('ubicacion', $value['N']);
        $this->template->setVariable('observacion', $value['O']);
        $this->template->parseCurrentBlock("ROW");
      }
    }
    
    $this->template->show();
  }
}
?>