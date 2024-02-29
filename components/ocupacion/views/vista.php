<?php
class OcupacionVista {
  var $template;
  var $datos;

	function OcupacionVista() {
		$this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datos = new OcupacionModelo();
	}

  function filtroOcupacion($arreglo) {
		$this->template->loadTemplateFile('ocupacion/views/tmpl/filtroOcupacion.php');
		$this->template->setVariable('COMODIN', '');

    // Carga información del Perfil y Usuario
    $arreglo['perfil'] = $_SESSION['datos_logueo']['perfil_id'];
    $arreglo['usuario'] = $_SESSION['datos_logueo']['usuario'];
    // Valida el Perfil para identificar el Tercero
    if($arreglo['perfil'] == 23) {
      $this->template->setVariable('soloLectura', "readonly=''");
      $this->template->setVariable('nito', $arreglo['usuario']);
      $cliente = $this->datos->findClientet($arreglo['usuario']);
      $this->template->setVariable('cliente', $cliente->razon_social);
    } else {
      $this->template->setVariable('soloLectura', "");
      $this->template->setVariable('nito', "");
      $this->template->setVariable('cliente', "");
    }
    
		$this->template->show();
	}

  function listadoOcupacion($arreglo) {
    $this->template->loadTemplateFile('ocupacion/views/tmpl/listadoOcupacion.php');
    $this->template->setVariable('COMODIN', '');

    //Conservación datos del filtro
    $this->template->setVariable('nito',$arreglo['nito']);
    $this->template->setVariable('doctteo',$arreglo['doctteo']);
    $this->template->setVariable('doasignadoo',$arreglo['doasignadoo']);
    $this->template->setVariable('ocupaciono',$arreglo['ocupaciono']);
    $this->template->setVariable('referenciao',$arreglo['referenciao']);
    //Inicializa el número de registro
    $n = 0; $tr = count($arreglo['datos']); //Total de Registros    
    //Visualiza Listado Solicitado
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', ++$n);
      $this->template->setVariable('nombre_ubicacion', $value['nombre_ubicacion']);
      $this->template->setVariable('codigo', $value['codigo']);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('tr',$tr);
    
    $this->template->show();
  }

  function imprimeListadoOcupacion($arreglo) {
    $this->template->loadTemplateFile('ocupacion/views/tmpl/verListadoOcupacion.php' );
    $this->template->setVariable('COMODIN', '');

    //Visualiza Ocupaciones Solicitadas
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('nombre_ubicacion', $value['nombre_ubicacion']);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->parseCurrentBlock("ROW");
    }
    
    $this->template->show();
  }
}
?>