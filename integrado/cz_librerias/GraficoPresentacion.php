<?php

require_once ("HTML/Template/IT.php");



class GraficoPresentacion {
    
    var $datos;
    var $plantilla;
	var $colores;
  
    function GraficoPresentacion (&$datos,&$colores) {
        $this->datos = &$datos;
		$this->colores =&$colores;
		$this->plantilla = new HTML_Template_IT();
    } 
	
	
	function setAtributos(){	
   
        $this->plantilla->setVariable('nombre'	      , $this->datos->nombre);
     
	
     }

	
	 
	
} 



?>