<?php
require_once ("HTML/Template/IT.php");
require_once ("AnexosDatos.php");
require_once ("AnexosLogica.php");

class AnexosPresentacion {
  var $datos;
  var $plantilla;

  function AnexosPresentacion(&$datos) {
    $this->datos =& $datos;
    $this->plantilla = new HTML_Template_IT();
  } 

  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla =& $plantilla;

    if(is_array($arregloCampos)) {
      foreach($arregloCampos as $key => $value) {
        $plantilla->setVariable($key, $value);
      }
    }
  }

  //Función que coloca los datos que vienen de la BD
  function setDatos($arregloDatos,&$datos,&$plantilla) {
    foreach($datos as $key => $value) {
      $plantilla->setVariable($key, $value);
    }
  }

  function cargaPlantilla($arregloDatos) {
    $unAplicaciones = new Anexos();
    $formularioPlantilla = new HTML_Template_IT();
    
    $formularioPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],true,true);
    $formularioPlantilla->setVariable('comodin',' ');
    $this->mantenerDatos($arregloDatos,$formularioPlantilla);
    $this->$arregloDatos[thisFunction]($arregloDatos,$this->datos,$formularioPlantilla);
    
    if($arregloDatos[mostrar]) $formularioPlantilla->show();
    else return $formularioPlantilla->get();
  }

  //Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos,$unDatos) {
    $unDatos = new Anexos();

    $r = $unDatos->$arregloDatos[thisFunction]($arregloDatos);
    $unaPlantilla = new HTML_Template_IT();
    
    $unaPlantilla->loadTemplateFile(PLANTILLAS . $arregloDatos[plantilla],true,true);
    if(!empty($arregloDatos[mensaje])) {
      $unaPlantilla->setVariable('mensaje', $arregloDatos[mensaje]);
      $unaPlantilla->setVariable('estilo', $arregloDatos[estilo]);
    }
    $this->mantenerDatos($arregloDatos,$unaPlantilla);
    $arregloDatos[n] = 0;
    while($unDatos->fetch()) {
      $odd = ($arregloDatos[n] % 2) ? 'odd' : '';
      $arregloDatos[n] = $arregloDatos[n] + 1;
      $unaPlantilla->setCurrentBlock('ROW');
      $this->setDatos($arregloDatos,$unDatos,$unaPlantilla);
      $this->$arregloDatos[thisFunction]($arregloDatos,$unDatos,$unaPlantilla);
      $unaPlantilla->setVariable('n', $arregloDatos[n]);
      $unaPlantilla->setVariable('odd', $odd);
      $unaPlantilla->parseCurrentBlock();
    }
    if($unDatos->N == 0 and empty($unDatos->mensaje)) {
      $unaPlantilla->setVariable('mensaje', '&nbsp;No hay fotos registradas');
      $unaPlantilla->setVariable('estilo', 'ui-state-error');
    }
    $unaPlantilla->setVariable('num_registros', $unDatos->N);
    if($arregloDatos[mostrar]) $unaPlantilla->show();
    else return $unaPlantilla->get();
  }

  function verFotos($arregloDatos) {
    $this->plantilla->loadTemplateFile(PLANTILLAS . 'anexosListado.html',false,false);
    $this->mantenerDatos($arregloDatos,$this->plantilla);
    $this->plantilla->setVariable('comodin', '');
    
    $this->plantilla->show();
  }

  function fotoAnexos($arregloDatos) {
    $arregloDatos[idCliente] = $_SESSION['datos_logueo']['usuario'];
    $this->plantilla->loadTemplateFile(PLANTILLAS . 'anexosFotos.html',true,true);
    $this->mantenerDatos($arregloDatos,$this->plantilla);
    $this->plantilla->setVariable('comodin', '');
    
    $arregloDatos[mostrar] = 0;
    
    if(!empty($arregloDatos[filtroc])) {    
      $arregloDatos[plantilla] = 'anexosListado.html';
      $arregloDatos[thisFunction] = 'verAnexosFotos';
      $htmListado = $this->setFuncion($arregloDatos,$unDatos);
      $this->plantilla->setVariable('htmListado', $htmListado);
    } else {	 
      $arregloDatos[thisFunction] = 'filtroc';
      $arregloDatos[plantilla] = 'anexosReportefiltroc.html';  
      $htmfiltroc=$this->cargaPlantilla($arregloDatos);
      $this->plantilla->setVariable('filtrocEntradaConsulta', $htmfiltroc);
    }

    $this->plantilla->show();
  }

  function verAnexosFotos($arregloDatos,&$datos,&$plantilla) {
    $plantilla->setVariable('img_editar', 'layer--pencil.png');
  }

  function filtroc($arregloDatos,&$datos,&$plantilla) {
  }

  function listar($arregloDatos,&$datos,&$plantilla) {
  }
}
?>