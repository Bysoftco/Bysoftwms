<?php
require_once(COMPONENTS_PATH.'sizfra_grupo/model/sizfra_grupo.php');

class SizfraVista {
  var $template;
  var $datos;
	
  function SizfraVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datos = new SizfraModelo();
  }
  
	function filtroSizfra($arreglo) {  	   	   
    $this->template->loadTemplateFile('sizfra_grupo/views/tmpl/filtroSizfra.php');
    $this->template->setVariable('COMODIN','');

    $this->template->show();
	}
  	
  function listadoSizfra($arreglo,$datosInterfaz) {    
    $this->template->loadTemplateFile('sizfra_grupo/views/tmpl/listadoSizfra.php');
    $this->template->setVariable('COMODIN','');
    
    $this->template->setVariable('nombreinterfaz',$arreglo['nombreinterfaz']);
    $this->template->setVariable('emaildestino',$arreglo['emaildestino']);
    
    // Generación de Interfaz
    $contador = 1; 
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('csc', $contador);
      $this->template->setVariable('codigo', $value['codigo']);
      $this->template->setVariable('nombre', $value['nombre']);
      // Escribe registro en el archivo de texto      
      $linea = str_pad($value['codigo'], 3, " ",STR_PAD_LEFT)."\t".substr($value['nombre'], 0, 35)."\n";      
      $datosInterfaz->escribirContenido($linea);      
      $this->template->parseCurrentBlock("ROW");
      $contador++;
    }
    
    $this->template->show();
  }
    
	function mostrarMensaje($arreglo) {
		if($arreglo['info']) {
			$msg = "Se ha enviado un correo a la cuenta: ".$arreglo['destino']." satisfactoriamente.";
		} else {
			$msg = "Error al enviar el correo electr\u00f3nico, por favor revisar el servidor de correo saliente.";
		}
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}
}
?>