<?php
/*
  Versión 1.0
  Autor Fredy Arevalo
  Fecha September  11 de 2007
  Actualizada: Fredy Salom - Marzo 03, 2023
  Descripción:
  Clase encargada de Construir la Interfaz Gráfica Para el Módulo de Transportador
  */
require_once ("HTML/Template/IT.php");
require_once("TransportadorDatos.php");

class TransportadorPresentacion {
  var $datos;
  var $plantilla;
  var $tot_peso_nac;
  var $p_naci_t;
  var $peso;
  var $p_retiro;
  var $cif_t;
	var $can_nac;
	var $can_ext;
	var $total_cif;
	var $total_fob;

  function TransportadorPresentacion(&$datos) {
    $this->datos = $datos;
    $this->plantilla = new HTML_Template_IT(PLANTILLAS);
  } 

  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla = $plantilla;

    if(is_array($arregloCampos)) {
      foreach($arregloCampos as $key => $value) {
        $plantilla->setVariable($key , $value);
      }
    }
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla) {
    foreach($datos as $key => $value) {
      $plantilla->setVariable($key , $value);
    }
  }

  function getLista($arregloDatos,$seleccion,&$plantilla) {
    $unaLista = new Transportador();
    $lista = $unaLista->lista($arregloDatos['tabla'],$arregloDatos['condicion'],$arregloDatos['campoCondicion']);
    $lista = $unaLista->armSelect($lista,'[seleccione]',$seleccion);

    $plantilla->setVariable($arregloDatos['labelLista'], $lista);
  }

  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Transportador();
    $formularioPlantilla = new HTML_Template_IT(PLANTILLAS);

    $formularioPlantilla->loadTemplateFile($arregloDatos['plantilla'],false,false);
    $formularioPlantilla->setVariable('comodin'	,' ');
    $this->mantenerDatos($arregloDatos,$formularioPlantilla);

    $metodo = $arregloDatos['thisFunction'];
    $this->$metodo($arregloDatos,$this->datos,$formularioPlantilla);

    if($arregloDatos['mostrar']) {
      $formularioPlantilla->show();
    } else {
      return $formularioPlantilla->get();
    }
  }

  //Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos,$unDatos) {
    $unDatos = new Transportador();
    $metodo = $arregloDatos['thisFunction'];

    $r = $unDatos->$metodo($arregloDatos);

    $unaPlantilla = new HTML_Template_IT(PLANTILLAS);
    $unaPlantilla->loadTemplateFile($arregloDatos['plantilla'],true,true);
    $unaPlantilla->setVariable('comodin',' ');

		if(!empty($arregloDatos['mensaje'])) {
      $unaPlantilla->setVariable('mensaje',$arregloDatos['mensaje']);
      $unaPlantilla->setVariable('estilo'	,$arregloDatos['estilo']);
    }

    $arregloDatos['n'] = 0;
    $rows = $unDatos->db->countRows();
    while($obj=$unDatos->db->fetch()) {
      $odd = $arregloDatos['n'] % 2 ? 'odd' : '';
      $arregloDatos['n'] = $arregloDatos['n'] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos,$obj,$unaPlantilla);
      $metodo = $arregloDatos['thisFunction'];
      $this->$metodo($arregloDatos,$obj,$unaPlantilla);
      $unaPlantilla->setVariable('n', $arregloDatos['n']);
      $unaPlantilla->setVariable('odd', $odd);
      $unaPlantilla->parseCurrentBlock();
    }
    if($rows == 0 and empty($unDatos->mensaje_error)) {
      $unaPlantilla->setVariable('mensaje','No hay registros para listar'.$arregloDatos['mensaje']);
      $unaPlantilla->setVariable('estilo','ui-state-error');
      $unaPlantilla->setVariable('mostrarCuerpo','none');
    }

		$unaPlantilla->setVariable('num_registros',$rows);

    if($arregloDatos['mostrar']) {
      $unaPlantilla->show();
    } else {
      $unaPlantilla->setVariable('cuenta',$this->cuenta);
      return $unaPlantilla->get();
    }
	}

  function maestro($arregloDatos) {
    if($arregloDatos['tipo_retiro_label'] == "Matriz") {
      $this->plantilla->setVariable('tipo_retiro_label', 'Matriz');
    }
    $this->plantilla->loadTemplateFile('transportadorMaestro.html',true,true);
    $this->plantilla->setVariable('comodin',' ');

    $arregloDatos['tab_index'] = 2;
    $this->mantenerDatos($arregloDatos,$this->plantilla);

    $arregloDatos['mostar'] = "0";
    $arregloDatos['plantilla'] = 'transportadorToolbar.html';
    $arregloDatos['thisFunction'] = 'getToolbar';
    $this->plantilla->setVariable('toolbarLevante',$this->cargaPlantilla($arregloDatos));

    if(empty($arregloDatos['por_cuenta_filtro'])) {
      $this->plantilla->setVariable('abre_ventana', 1);
      $arregloDatos['thisFunction'] = 'filtro';
      $arregloDatos['plantilla'] = 'transportadorFiltro.html';
      $arregloDatos['mostrar'] = 0;
      $htmlFiltro = $this->cargaPlantilla($arregloDatos);
      $this->plantilla->setVariable('filtroFiltro', $htmlFiltro);	
    } else {
      $this->plantilla->setVariable('abre_ventana', 0); 
      // el método controlarTransaccion de la Logica envia la plantilla y el método para pintar el TAB de mercancia
      $arregloDatos['mostrar'] = 0;
      $arregloDatos['plantilla'] = $arregloDatos['plantillaMercanciaCuerpo'];
      $arregloDatos['thisFunction'] = $arregloDatos['metodoMercanciaCuerpo'];
      $htmlMercancia = $this->setFuncion($arregloDatos,$this->datos);
      $this->plantilla->setVariable('htmlMercancia',$htmlMercancia);
    }
    
    $this->plantilla->show();
  } 

  function getToolbar($arregloDatos,&$datos,&$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }

  function getListado($arregloDatos,$unDatos,$plantilla) { }     

  function filtro($arregloDatos,$unDatos,$plantilla) { }

  function getUnTransportador($arregloDatos,$unDatos) { }

  function getFormaNueva($arregloDatos) { }
} 
?>