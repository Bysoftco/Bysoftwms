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
 	  $arregloDatos[tituloGrafico]="Indicador FacturaciFacturaci&oacute;n Top 10 de clientes ";
    
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
  
    function indicadorUnCliente(&$arregloDatos) {
    $sede = $_SESSION['sede'];
    $arregloDatos[tituloGrafico]="Indicador Facturaci&oacute;n por mes del cliente $arregloDatos[cliente] ";
    
    $sql = " SELECT 
			 
		CASE MONTH(fecha_factura) WHEN '1' THEN 'Enero'
		WHEN '2' THEN 'Febrero'
		WHEN '3' THEN 'Marzo'
		WHEN '4' THEN 'Abril'
		WHEN '5' THEN 'Mayo'
		WHEN '6' THEN 'Junio'
		WHEN '7' THEN 'Julio'
		WHEN '8' THEN 'Agosto'
		WHEN '9' THEN 'Septiembre'
		WHEN '10' THEN 'Octubre'
		WHEN '11' THEN 'Noviembre'
		WHEN '12' THEN 'Diciembre'
		END AS datos,
			 
			 
		SUM(total) AS valores
		FROM facturas_maestro,clientes
		WHERE facturas_maestro.cliente=clientes.numero_documento
		AND YEAR(fecha_factura) >=$arregloDatos[anios] 
		AND facturas_maestro.cliente='$arregloDatos[cliente]'
		
		GROUP BY MONTH(fecha_factura)
		HAVING  SUM(total) >0
		ORDER BY MONTH(fecha_factura) ASC
   		LIMIT 12
 			";
			 
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