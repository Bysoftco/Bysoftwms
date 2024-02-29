<?php
require_once(DB.'BDControlador.php');

class Anexos extends BDControlador {
  var $db;

  function Anexos() {
    $this->db = $_SESSION['conexion'];
    $this->estilo_error = "ui-state-error";
    $this->estilo_ok = "ui-state-highlight";
  } 

  function findOrdenDoc($arregloDatos) {
    $sql = "SELECT DISTINCT doc_tte, do_asignado, por_cuenta FROM do_asignados WHERE doc_tte LIKE '$arregloDatos[q]%'";

    //echo $sql;

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      return TRUE;
    }
  }

  function listar($arregloDatos) {
    $sql = "SELECT fecha_foto, nombre_foto FROM ordenes_anexos WHERE  orden = '$arregloDatos[orden]'";

    $resultado = $this->db->query($sql);
    if(!is_null($resultado)) {
      echo $sql;
      return TRUE;
    }
  }
}  
?>