<?php
require_once("MYDB.php");// Se debe Apuntar a una tabla cualquiera  
//require_once("LevanteDatos.php");// Se debe Apuntar a una tabla cualquiera 

class Indicadores extends MYDB {
  function Indicadores() {
    $this->estilo_error = " ui-state-error";
    $this->estilo_ok = " ui-state-highlight";
  }
  
  function indicadorIngresos($arregloDatos) {
  //var_dump($arregloDatos);
      $sede = $_SESSION['sede'];
 	  $arregloDatos[tituloGrafico]="Indicador FacturaciFacturaci&oacute;n Top 10 de clientes ";
	  
    if(empty($arregloDatos[anios])){
		$arregloDatos[anios]=date("Y");
	}	
	
    $sql = " SELECT 
	SUM(enero) AS enero,
	SUM(febrero) AS febrero,
	SUM(marzo) AS marzo,
	SUM(abril) AS abril,
	SUM(mayo) AS mayo,
	SUM(junio) AS junio,
	SUM(julio) AS julio,
	SUM(agosto) AS agosto,
	SUM(septiembre) AS septiembre,
	SUM(octubre) AS octubre,
	SUM(noviembre) AS noviembre,
	SUM(diciembre) AS diciembre
FROM
(

SELECT 
MONTH(fecha_arribo) AS mes,
peso_bruto AS peso,

CASE MONTH(fecha_manifiesto)  
  WHEN 1 THEN peso_bruto  
 END AS enero,
 CASE MONTH(fecha_manifiesto)  
  WHEN 2 THEN peso_bruto  
 END AS febrero,
  CASE MONTH(fecha_manifiesto)  
  WHEN 3 THEN peso_bruto  
 END AS marzo,
 CASE MONTH(fecha_manifiesto)  
  WHEN 4 THEN peso_bruto  
 END AS abril,
  CASE MONTH(fecha_manifiesto)  
  WHEN 5 THEN peso_bruto  
 END AS mayo,
 CASE MONTH(fecha_manifiesto)  
  WHEN 6 THEN peso_bruto  
 END AS junio,
 CASE MONTH(fecha_manifiesto)  
  WHEN 7 THEN peso_bruto  
 END AS julio,
 CASE MONTH(fecha_manifiesto)  
  WHEN 8 THEN peso_bruto  
 END AS agosto,
 CASE MONTH(fecha_manifiesto)  
  WHEN 9 THEN peso_bruto  
 END AS septiembre,
  CASE MONTH(fecha_manifiesto)  
  WHEN 10 THEN peso_bruto  
 END AS octubre,
 CASE MONTH(fecha_manifiesto)  
  WHEN 11 THEN peso_bruto  
 END AS noviembre,
  CASE MONTH(fecha_manifiesto)  
  WHEN 12 THEN peso_bruto  
 END AS diciembre
 
FROM arribos
WHERE 
YEAR(fecha_manifiesto)='$arregloDatos[anios]' 

) AS ingresos";
			 
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
  
    
  	 function indicadorSalidas($arregloDatos) {
	 
    	$sede = $_SESSION['sede'];
 	  		$arregloDatos[tituloGrafico]="Indicador FacturaciFacturaci&oacute;n Top 10 de clientes ";
    
    	$sql = " SELECT 
	ABS(SUM(enero)) AS enero,
	ABS(SUM(febrero)) AS febrero,
	ABS(SUM(marzo)) AS marzo,
	ABS(SUM(abril)) AS abril,
	ABS(SUM(mayo)) AS mayo,
	ABS(SUM(junio)) AS junio,
	ABS(SUM(agosto)) AS agosto,
	ABS(SUM(septiembre)) AS septiembre,
	ABS(SUM(octubre)) AS octubre,
	ABS(SUM(noviembre)) AS noviembre,
	ABS(SUM(diciembre)) AS diciembre
FROM
(

	SELECT 
	MONTH(fecha) AS fecha,

	CASE MONTH(fecha)  
	WHEN 1 THEN peso_naci+peso_nonac  
	END AS enero,
 
	CASE MONTH(fecha)  
	WHEN 2 THEN peso_naci+peso_nonac  
	END AS febrero,
	
	CASE MONTH(fecha)  
	WHEN 3 THEN peso_naci+peso_nonac  
	END AS marzo,
	
	CASE MONTH(fecha)  
	WHEN 4 THEN peso_naci+peso_nonac  
	END AS abril,
	
	CASE MONTH(fecha)  
	WHEN 5 THEN peso_naci+peso_nonac  
	END AS mayo,
	
	CASE MONTH(fecha)  
	WHEN 6 THEN peso_naci+peso_nonac  
	END AS junio,
	
	CASE MONTH(fecha)  
	WHEN 7 THEN peso_naci+peso_nonac  
	END AS julio,
	
	CASE MONTH(fecha)  
	WHEN 8 THEN peso_naci+peso_nonac  
	END AS agosto,
	
	CASE MONTH(fecha)  
	WHEN 9 THEN peso_naci+peso_nonac  
	END AS septiembre,
	
	CASE MONTH(fecha)  
	WHEN 10 THEN peso_naci+peso_nonac  
	END AS octubre,
	
	CASE MONTH(fecha)  
	WHEN 11 THEN peso_naci+peso_nonac  
	END AS noviembre,
	
	CASE MONTH(fecha)  
	WHEN 12 THEN peso_naci+peso_nonac  
	END AS diciembre
	
 
	FROM inventario_movimientos
	WHERE tipo_movimiento= 3
	AND YEAR(fecha)='$arregloDatos[anios]' 
	 
)AS retiros";
			 
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