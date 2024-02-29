<?php
require_once(COMPONENTS_PATH.'sizfra/model/sizfra.php');

class SizfraVista {
  var $template;
  var $datos;
	
  function SizfraVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datos = new SizfraModelo();
  }
  
	function filtroSizfra($arreglo) {  
    $this->template->loadTemplateFile('sizfra/views/tmpl/filtroSizfra.php');
    $this->template->setVariable('COMODIN','');
    
    // Carga información del Perfil y Usuario
    $arreglo['perfil'] = $_SESSION['datos_logueo']['perfil_id'];
    $arreglo['usuario'] = $_SESSION['datos_logueo']['usuario'];
    
    $this->template->show();
	}
  	
  function listadoSizfra($arreglo,$datosInterfaz) {    
    $this->template->loadTemplateFile('sizfra/views/tmpl/listadoSizfra.php');
    $this->template->setVariable('COMODIN','');
    
    $this->template->setVariable('nombreinterfaz',$arreglo['nombreinterfaz']);
    $this->template->setVariable('emaildestino',$arreglo['emaildestino']);
    
    $n = $arreglo['consecutivo'];
    
    // Generación de Interfaz 
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('consecutivo', $n);
      $this->template->setVariable('fecha', $value['fecha']);
      $this->template->setVariable('operacion', $value['operacion']);
      $this->template->setVariable('sku_proveedor', $value['sku_proveedor']);
      $this->template->setVariable('peso_bruto', number_format($value['peso_bruto'],2));
      $this->template->setVariable('peso_neto', number_format($value['peso_bruto'] * 0.95, 2));
      $this->template->setVariable('fletes', $value['fletes']);      
      $this->template->setVariable('seguros', $value['seguros']);
      $this->template->setVariable('otros_gastos', $value['otros_gastos']);
      $this->template->setVariable('embalaje', $value['cd_embalaje']);
      $this->template->setVariable('modo_transporte', $value['modo_transporte']);
      $this->template->setVariable('origen', $value['origen']);
      $this->template->setVariable('procedencia', $value['origen']);
      $this->template->setVariable('compra', $value['origen']);
      $this->template->setVariable('destino', $value['destino']);
      $this->template->setVariable('bandera', $value['origen']);
      $this->template->setVariable('bultos', number_format($value['bultos'],2));
      $this->template->setVariable('cod_referencia', $value['cod_referencia']);
      $this->template->setVariable('cantidad', number_format($value['cantidad'],2));
      $this->template->setVariable('cuc', number_format($value['cantidad'],2));
      $this->template->setVariable('precio', number_format($value['precio'],2));
      // Escribe registro en el archivo de texto            
      $linea  = $n."\t0\t".$value['operacion']."\t".$value['sku_proveedor']."\t".$value['peso_bruto']."\t";
      $linea .= $value['peso_neto']."\t".$value['fletes']."\t".$value['seguros']."\t".$value['otros_gastos']."\t";
      $linea .= $value['cd_embalaje']."\t".$value['modo_transporte']."\t".$value['origen']."\t".$value['origen']."\t";
      $linea .= $value['origen']."\t".$value['destino']."\t".$value['destino']."\t".$value['bultos']."\t".$value['cod_referencia']."\t";
      $linea .= $value['cantidad']."\t".$value['cantidad']."\t".$value['precio']."\n";
      
      $datosInterfaz->escribirContenido($linea);      
      $this->template->parseCurrentBlock("ROW");
      $n = $n + 1;
    }
    
    $this->template->show();
  }
  
  function consultaSizfra($arreglo) {    
    $this->template->loadTemplateFile('sizfra/views/tmpl/consultaSizfra.php');
    $this->template->setVariable('COMODIN','');
        
    $n = 1; 
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', $n);
      $this->template->setVariable('fecha', $value['fecha_interfaz']);
      $this->template->setVariable('interfaz', $value['interfaz']);
      $this->template->setVariable('cantidad', number_format($value['cantidad'],2));
      $this->template->setVariable('peso', number_format($value['peso'],2));
      $this->template->setVariable('valor', number_format($value['valor'],2));
      $this->template->parseCurrentBlock("ROW");
      $n++;
    }
    
    $this->template->show();
  }

	function capturaSizfra($arreglo) {
    $this->template->loadTemplateFile('sizfra/views/tmpl/capturaSizfra.php');
    $this->template->setVariable('COMODIN','');
    
    $this->template->show();
	}

	function mostrarMensaje($arreglo) {
		if($arreglo[info]) {
			$msg = "Se ha enviado un correo a la cuenta: ".$arreglo[destino]." satisfactoriamente.";
		} else {
			$msg = "Error al enviar el correo electr\u00f3nico, por favor revisar el servidor de correo saliente.";
		}
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}
}
?>