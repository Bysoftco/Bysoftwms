<?php
require_once("MYDB.php");// Se debe Apuntar a una tabla cualquiera  

class Transportador extends MYDB {
  function Transportador() {
    $this->estilo_error = " ui-state-error";
    $this->estilo_ok = " ui-state-highlight";
  }
  

   function getListado($arregloDatos)
    {
      $fecha=date('Y-m-d:a');
      $sede=$_SESSION['sede'];
      
      $sql="select * from transportador  ";
       if(!empty($arregloDatos[nombre])){
	   		$sql.=" where nombre like '%$arregloDatos[nombre]%'";		
	   }
	   
       $this->query($sql);
       //echo $sql;
		if ($this->_lastError) 
        {
          	echo "<div class=error align=center> :( Error al listar Transportador <br>$sql</div>.";  
            return FALSE;
        }
 
       
    }
	
	 function getUnTransportador($arregloDatos)
    {
      $fecha=date('Y-m-d:a');
      $sede=$_SESSION['sede'];
      
      $sql="select * from transportador  ";
       if(!empty($arregloDatos[codigo])){
	   		$sql.=" where codigo like '%$arregloDatos[codigo]%'";		
	   }
       $this->query($sql);
      // echo $sql;
		if ($this->_lastError) 
        {
          	echo "<div class=error align=center> :( Error al listar Transportador <br>$sql</div>.";  
            return FALSE;
        }
 
       
    }
	
	 function updateTransportador($arregloDatos)
    {
      $fecha=date('Y-m-d:a');
      $sede=$_SESSION['sede'];
      $codigo=trim($arregloDatos[codigo]);
      $sql="update transportador set codigo='$arregloDatos[codigo]', nombre='$arregloDatos[nombre]', contacto='$arregloDatos[contacto]',
	  		telefono='$arregloDatos[telefono]', tipo_transporte='$arregloDatos[tipo_transporte]' where TRIM(codigo) = '$codigo'";
      $this->query($sql);
       //echo $sql;
		if ($this->_lastError) 
        {
          	echo "<div class=error align=center> :( Error al listar Transportador <br>$sql</div>.";  
            return FALSE;
        }
 
       
    }
	function findCliente($arregloDatos) {
		$sql = "SELECT nombre
						FROM transportador  WHERE (nombre LIKE '%$arregloDatos[q]%')";

		$this->query($sql);
		if($this->_lastError) {
			echo $sql;
			return TRUE;
		}
	}
	function getFormaNueva($arregloDatos) {
		
	}
	
	function addTransportador($arregloDatos){
		$sql = "INSERT INTO transportador (codigo, nombre, contacto, telefono, tipo_transporte)
				VALUES ('$arregloDatos[codigo]' ,'$arregloDatos[nombre]', '$arregloDatos[contacto]', '$arregloDatos[telefono]', '$arregloDatos[tipo_transporte]')";
		
		$this->query($sql);
		if($this->_lastError) {
			echo $sql;
			return TRUE;
		}
		
	}
  }
?>