<?php
require_once ("HTML/Template/IT.php");
require_once ("CargueFirmasDatos.php");

class CargueFirmasPresentacion {
  var $datos;
  var $plantilla;
  var $total;

  function CargueFirmasPresentacion(&$datos) {
    $this->datos = $datos;
    $this->plantilla = new HTML_Template_IT();
    $this->mensaje_color;
  }

  //Función que coloca los datos que vienen del formulario
  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla = $plantilla;
    if (is_array($arregloCampos)) {
      foreach ($arregloCampos as $key => $value) {
        $plantilla->setVariable($key, $value);
      }
    }
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla) {
		foreach ($datos as $key => $value) {
			$plantilla->setVariable($key, $value);
    }
  }

  function getLista($arregloDatos,$seleccion,&$plantilla) {
		$unaLista = new Cheques();
    $lista = $unaLista->lista($arregloDatos['tabla'],$arregloDatos['condicion'],$arregloDatos['campoCondicion']);
    $lista = $unaLista->armSelect($lista,$seleccion,'[seleccione]');

    $plantilla->setVariable($arregloDatos['labelLista'],$lista);
  }

  function cargaPlantilla($arregloDatos) {
		$unAplicaciones = new CargueFirmas();
    $formularioPlantilla = new HTML_Template_IT(PLANTILLAS);

    $formularioPlantilla->loadTemplateFile($arregloDatos['plantilla'],false,false);
    $formularioPlantilla->setVariable('comodin',' ');
    $this->mantenerDatos($arregloDatos,$formularioPlantilla);

    $metodo = $arregloDatos['thisFunction'];
    $this->$metodo($arregloDatos,$this->datos,$formularioPlantilla);

    if ($arregloDatos['mostrar']) {
        $formularioPlantilla->show(); //ajax
    } else {
        return $formularioPlantilla->get(); // php
    }
  }

  // Arma cada Formulario o funcion en pantalla
  function setFuncion($arregloDatos, $unDatos) {
    $unDatos = new CargueFirmas();
    $metodo = $arregloDatos['thisFunction'];

    $unDatos->$metodo($arregloDatos);
    $this->plantilla->setVariable('mensaje',$arregloDatos['mensaje']);
    $this->plantilla->setVariable('estilo',$arregloDatos['estilo']);

    $unaPlantilla = new HTML_Template_IT(PLANTILLAS);
    $unaPlantilla->loadTemplateFile($arregloDatos['plantilla'],true,true);
    $unaPlantilla->setVariable('comodin'," ");
    $this->mantenerDatos($arregloDatos, $unaPlantilla);
    if ($arregloDatos['thisFunctionAux']) {
      $metodo2 = $arregloDatos['thisFunctionAux'];
      $this->$metodo2($arregloDatos,$unDatos,$unaPlantilla);
    }

    $rows = $unDatos->db->countRows();
    $n = 0;
    while($obj=$unDatos->db->fetch()) {
      $odd = $arregloDatos['n'] % 2 ? 'odd' : '';
      $n = $n + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos,$obj,$unaPlantilla);
      $metodo = $arregloDatos['thisFunction'];
      $this->$metodo($arregloDatos, $unDatos, $unaPlantilla);
      $unaPlantilla->setVariable('n', $n);
      $unaPlantilla->setVariable('odd', $odd);
      $unaPlantilla->parseCurrentBlock();
    }
    if ($rows == 0) {
      $unaPlantilla->setVariable('mensaje','No hay registros para listar ');
      $unaPlantilla->setVariable('estilo','ui-state-error');
    }

    $unaPlantilla->setVariable('num_registros',$rows);

    if ($arregloDatos['mostrar']) {
      $unaPlantilla->show();
    } else {
      return $unaPlantilla->get();
    }
  }

  function filtro($arregloDatos,$datos,$formularioPlantilla) { }

  function maestroFirmas($arregloDatos) {
		$this->plantilla->loadTemplateFile(PLANTILLAS.'cargueFirmasMaestro.html',false,false);

    $arregloDatos['mostrar'] = 0;
    $arregloDatos['abrirv'] = '1';
    $this->mantenerDatos($arregloDatos,$this->plantilla);
    $arregloDatos['plantilla'] = 'cargarFoto.html';
    $arregloDatos['thisFunction'] = 'filtroCarga';

    $htmlFormulario = $this->cargaPlantilla($arregloDatos);
    $this->plantilla->setVariable('entrada',$htmlFormulario);

    $this->plantilla->show();
  }

  function filtroCarga($arregloDatos,$datos,$plantilla) {
    $this->mantenerDatos($arregloDatos,$plantilla);
  }
}
?>