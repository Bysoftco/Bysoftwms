<?php
require_once("MYDB.php");// Se debe Apuntar a una tabla cualquiera  

class Ubicacion extends MYDB {
  function Ubicacion() {
    $this->estilo_error = " ui-state-error";
    $this->estilo_ok = " ui-state-highlight";
  }
  

   function getListado($arregloDatos)
    {
      $fecha=date('Y-m-d:a');
      $sede=$_SESSION['sede'];
      
      $sql="select * from posiciones  ";
       if(!empty($arregloDatos[nombre])){
	   		$sql.=" where nombre like '%$arregloDatos[nombre]%'";		
	   }
	   if(!empty($arregloDatos[codigo]) and empty($arregloDatos[nombre]) ){
	   		$sql.=" where codigo like '%$arregloDatos[codigo]%'";		
	   }
       $this->query($sql);
       //echo $sql;
		if ($this->_lastError) 
        {
          	echo "<div class=error align=center> :( Error al listar Posiciones <br>$sql</div>.";  
            return FALSE;
        }
 
       
    }
	
	 function getUnaUbicacion($arregloDatos)
    {
      $fecha=date('Y-m-d:a');
      $sede=$_SESSION['sede'];
      
      $sql="select * from posiciones  ";
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
	
	 function updateUbicacion($arregloDatos)
    {
      $fecha=date('Y-m-d:a');
      $sede=$_SESSION['sede'];
      $codigo=trim($arregloDatos[codigo]);
      $sql="update posiciones set codigo='$arregloDatos[codigo]', nombre='$arregloDatos[nombre]', tipo='$arregloDatos[tipo]',
	  		descripcion='$arregloDatos[descripcion]' where TRIM(codigo) = '$codigo'";
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
						FROM posiciones  WHERE (nombre LIKE '%$arregloDatos[q]%')";

		$this->query($sql);
		if($this->_lastError) {
			echo $sql;
			return TRUE;
		}
	}
	function getFormaNueva($arregloDatos) {
		
	}
	
	function addUbicacion($arregloDatos){
		$sql = "INSERT INTO posiciones (codigo, tipo, nombre, descripcion,sede)
				VALUES ('$arregloDatos[codigo]' ,'$arregloDatos[tipo]', '$arregloDatos[nombre]', '$arregloDatos[descripcion]', '$arregloDatos[sede]')";
		
		$this->query($sql);
		if($this->_lastError) {
			echo $sql;
			return TRUE;
		}
	}
	function deleteUbicacion($arregloDatos) {
    	$sql = "DELETE FROM posiciones WHERE codigo = '$arregloDatos[codigo]'";

    	$this->query($sql);
    	if($this->_lastError) {
     		 $this->mensaje = "error al borrar ubicacion";
      		$this->estilo = $this->estilo_error;
      		return TRUE;
    	}
  	}
  }
?>