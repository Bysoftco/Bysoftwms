<?php
require_once("AnexosDatos.php");
require_once("AnexosPresentacion.php");

class AnexosLogica {
  var $datos;
  var $pantalla;

  function AnexosLogica() {
    $this->datos =& new Anexos();
    $this->pantalla =& new AnexosPresentacion($this->datos);
  }

  function fotoAnexos($arregloDatos) {
    $arregloDatos['titulo'] = "ANEXOS - FOTOS";
    $this->pantalla->fotoAnexos($arregloDatos);
  }

  function verFotos($arregloDatos) {
    //var_dump($arregloDatos); Ver el contenido de arregloDatos
    $arregloDatos[mostrar] = 1;
    $arregloDatos[plantilla] = 'anexosListado.html';
    $arregloDatos[thisFunction] = 'listar'; 
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function findOrdenDoc($arregloDatos) {
    $unaConsulta = new Anexos();

    $arregloDatos[idCliente] = $_SESSION['datos_logueo']['usuario'];
    $unaConsulta->findOrdenDoc($arregloDatos);
    $arregloDatos[q] = strtolower($_GET["q"]);
    
    while($unaConsulta->fetch()) {
      $nombre = trim($unaConsulta->do_asignado) . " :: " . trim($unaConsulta->doc_tte);
      $orden = $unaConsulta->do_asignado;
      echo "$nombre|$unaConsulta->doc_tte|$orden\n";
    }
    if($unaConsulta->N == 0) {
      echo "No hay Resultados|0\n";
    }
  }
}		

?>