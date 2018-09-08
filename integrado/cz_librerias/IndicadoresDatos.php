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
 
    
    $sql = " SELECT MAX(razon_social) AS datos,
			 total AS valores
			FROM facturas_maestro,clientes
			WHERE facturas_maestro.cliente=clientes.numero_documento
			AND fecha_factura >= '$arregloDatos[fecha_inicio]' AND fecha_factura <= '$arregloDatos[fecha_fin]'
			GROUP BY cliente
			ORDER BY total DESC
 			LIMIT 10";
			 
   // if($arregloDatos[excel]){ return $sql; }
   //echo  $sql;
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