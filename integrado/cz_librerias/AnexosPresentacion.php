<?php
require_once ("HTML/Template/IT.php");
require_once ("AnexosDatos.php");
require_once ("AnexosLogica.php");

class AnexosPresentacion {
  var $datos;
  var $plantilla;

  function AnexosPresentacion(&$datos) {
    $this->datos = $datos;
    $this->plantilla = new HTML_Template_IT();
  } 

  function mantenerDatos($arregloCampos,&$plantilla) {
    $plantilla = $plantilla;

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
    $formularioPlantilla = new HTML_Template_IT(PLANTILLAS);
    
    $formularioPlantilla->loadTemplateFile($arregloDatos['plantilla'],true,true);
    $formularioPlantilla->setVariable('comodin',' ');
    $this->mantenerDatos($arregloDatos,$formularioPlantilla);
    $metodo = $arregloDatos['thisFunction'];
    $this->$metodo($arregloDatos,$unAplicaciones,$formularioPlantilla);
    
    if($arregloDatos['mostrar']) $formularioPlantilla->show();
    else return $formularioPlantilla->get();
  }

  //Arma cada Formulario o función en pantalla
  function setFuncion($arregloDatos,$unDatos) {
    $unDatos = new Anexos();
    $metodo = $arregloDatos['thisFunction'];

    $r = $unDatos->$metodo($arregloDatos);
    $unaPlantilla = new HTML_Template_IT();
    
    $unaPlantilla->loadTemplateFile(PLANTILLAS.$arregloDatos['plantilla'],true,true);
    $this->mantenerDatos($arregloDatos,$unaPlantilla);
    $arregloDatos['n'] = 0;
    while($obj = $unDatos->db->fetch()) {
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
    if($arregloDatos['n'] == 0) {
      $unaPlantilla->setVariable('mensaje','&nbsp;No hay fotos registradas&nbsp;');
      $unaPlantilla->setVariable('estilo','ui-state-error');
      $unaPlantilla->setVariable('ver','block');
    } else { $unaPlantilla->setVariable('ver','none'); }
    $unaPlantilla->setVariable('num_registros',$arregloDatos['n']);
    if($arregloDatos['mostrar']) $unaPlantilla->show();
    else return $unaPlantilla->get();
  }

  function fotoAnexos($arregloDatos) {
    $arregloDatos['idCliente'] = $_SESSION['datos_logueo']['usuario'];
    $this->plantilla->loadTemplateFile(PLANTILLAS.'anexosFotos.html',true,true);
    $this->mantenerDatos($arregloDatos,$this->plantilla);
    $this->plantilla->setVariable('comodin','');
    
    $arregloDatos['plantilla'] = 'anexosReporteFiltroc.html';  
    $arregloDatos['thisFunction'] = 'filtroc';
    $arregloDatos['mostrar'] = 0;
    $htmfiltroc=$this->cargaPlantilla($arregloDatos);
    $arregloDatos['mostrar'] = 1;
    $this->plantilla->setVariable('mostrar',$arregloDatos['mostrar']);
    $this->plantilla->setVariable('filtroAnexos',$htmfiltroc);

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