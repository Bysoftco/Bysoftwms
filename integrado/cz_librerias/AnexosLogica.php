<?php
require_once("AnexosDatos.php");
require_once("AnexosPresentacion.php");

class AnexosLogica {
  var $datos;
  var $pantalla;

  function AnexosLogica() {
    $this->datos = new Anexos();
    $this->pantalla = new AnexosPresentacion($this->datos);
  }

  function fotoAnexos($arregloDatos) {
    $arregloDatos['titulo'] = "ANEXOS - FOTOS";
    $arregloDatos['mostrar'] = 0;
    $this->pantalla->fotoAnexos($arregloDatos);
  }

  function verFotos($arregloDatos) {
    $arregloDatos['mostrar'] = 1;
    $arregloDatos['plantilla'] = 'anexosListado.html';
    $arregloDatos['thisFunction'] = 'listar'; 
    $this->pantalla->setFuncion($arregloDatos,$this->datos);
  }

  function findOrdenDoc($arregloDatos) {
    $unaConsulta = new Anexos();

    $arregloDatos['idCliente'] = $_SESSION['datos_logueo']['usuario'];
    $arregloDatos['q'] = strtolower($_GET["q"]);
    $unaConsulta->findOrdenDoc($arregloDatos);
    $rows = 0;
    while($obj=$unaConsulta->db->fetch()) {
      $nombre = trim($obj->do_asignado)." :: ".trim($obj->doc_tte);
      $orden = $obj->do_asignado;
      echo "$nombre|$obj->doc_tte|$orden\n";
      $rows++;
    }
    if($rows == 0) { echo "No hay Resultados|0\n"; }
  }
}
?>