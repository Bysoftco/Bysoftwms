<?php
require_once("MYDB.php");

class Anexos extends MYDB {
  function Anexos() { 
    $this->estilo_error = "ui-state-error";
    $this->estilo_ok = "ui-state-highlight";
  } 

  function findOrdenDoc($arregloDatos) {
    $sql = "SELECT DISTINCT doc_tte, do_asignado, por_cuenta FROM do_asignados
            WHERE doc_tte LIKE '$arregloDatos[q]%' AND por_cuenta='$arregloDatos[idCliente]'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }

  function listar($arregloDatos) {
    $sql = "SELECT fecha_foto, nombre_foto FROM ordenes_anexos WHERE  orden = '$arregloDatos[orden]'";

    $this->query($sql);
    if($this->_lastError) {
      echo $sql;
      return TRUE;
    }
  }
}  
?>