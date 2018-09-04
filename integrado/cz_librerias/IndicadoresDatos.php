<?php
require_once("MYDB.php");// Se debe Apuntar a una tabla cualquiera  
//require_once("LevanteDatos.php");// Se debe Apuntar a una tabla cualquiera 

class Indicadores extends MYDB {
  function Indicadores() {
    $this->estilo_error = " ui-state-error";
    $this->estilo_ok = " ui-state-highlight";
  }
  
  function indicadorCliente($arregloDatos) {
    $sede = $_SESSION['sede'];
 
    
    $sql = " SELECT * 
			FROM do_asignados 
			WHERE codigo > 1088";
			 
   // if($arregloDatos[excel]){ return $sql; }
   
    $this->query($sql);
	
    if($this->_lastError) {
      echo "error".$sql;
      $this->mensaje = "error al consultar Inventario1 ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }   
  }
    

}
?>