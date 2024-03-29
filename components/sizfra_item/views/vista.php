<?php
require_once(COMPONENTS_PATH.'sizfra_item/model/sizfra_item.php');

class SizfraVista {
  var $template;
  var $datos;
	
  function SizfraVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datos = new SizfraModelo();
  }
  
	function filtroSizfra($arreglo) {  
    $this->template->loadTemplateFile('sizfra_item/views/tmpl/filtroSizfra.php');
    $this->template->setVariable('COMODIN','');
    
    $this->template->show();
	}
  	
  function listadoSizfra($arreglo,$datosInterfaz) {    
    $this->template->loadTemplateFile('sizfra_item/views/tmpl/listadoSizfra.php');
    $this->template->setVariable('COMODIN','');
    
    $this->template->setVariable('nombreinterfaz',$arreglo['nombreinterfaz']);
    $this->template->setVariable('emaildestino',$arreglo['emaildestino']);
    
    // Generación de Interfaz
    $contador = 1; 
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('csc', $contador);
      $this->template->setVariable('codigo_ref', $value['codigo_ref']);
      $this->template->setVariable('ref_prove', $value['ref_prove']);
      $this->template->setVariable('nombre', $value['nombre']);        
      $this->template->setVariable('naturaleza', $value['naturaleza']);
      $this->template->setVariable('grupo_item', $value['grupo_item']);
      $this->template->setVariable('codigo', $value['codigo']);
      $this->template->setVariable('factor_variable', $value['factor_variable']);
      $this->template->setVariable('fabricado_zf', $value['fabricado_zf']);
                                      
      // Escribe registro en el archivo de texto
      $linea  = $value['codigo_ref']."\t".$value['ref_prove']."\t";
      $linea .= $value['nombre']."\t".$value['naturaleza']."\t";
      $linea .= $value['grupo_item']."\t".$value['codigo']."\t";
      $linea .= $value['factor']."\t".$value['factor_variable']."\t".$value['fabricado_zf']."\n";
                  
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