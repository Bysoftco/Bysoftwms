<?php
require_once("MYDB.php");// Se debe Apuntar a una tabla cualquiera  

class Subpartida extends MYDB {
  function Subpartida() {
    $this->estilo_error = " ui-state-error";
    $this->estilo_ok = " ui-state-highlight";
  }
  

   function getListado($arregloDatos)
    {
      $fecha=date('Y-m-d:a');
      $sede=$_SESSION['sede'];
      
      $sql="select * from subpartidas  ";
       if(!empty($arregloDatos[subpartida])){
	   		$sql.=" where subpartida like '%$arregloDatos[subpartida]%'";		
	   }
	   if(!empty($arregloDatos[descripcion]) and empty($arregloDatos[subpartida]) ){
	   		$sql.=" where descripcion like '%$arregloDatos[descripcion]%'";		
	   }
       $this->query($sql);
       //echo $sql;
		if ($this->_lastError) 
        {
          	echo "<div class=error align=center> :( Error al listar Subpartidas <br>$sql</div>.";  
            return FALSE;
        }
 
       
    }
	
	 function getUnaSubpartida($arregloDatos)
    {
      $fecha=date('Y-m-d:a');
      $sede=$_SESSION['sede'];
      
      $sql="select * from subpartidas  ";
       if(!empty($arregloDatos[subpartida])){
	   		$sql.=" where subpartida like '%$arregloDatos[subpartida]%'";		
	   }
	   
       $this->query($sql);
		if ($this->_lastError) 
        {
          	echo "<div class=error align=center> :( Error al listar Subpartidas <br>$sql</div>.";  
            return FALSE;
        }
 
       
    }
	
	 function updateSubpartida($arregloDatos)
    {
      $fecha=date('Y-m-d:a');
      $sede=$_SESSION['sede'];
      $subpartida=trim($arregloDatos[subpartida]);
      $sql="update  subpartidas set arancel=$arregloDatos[arancel], descripcion='$arregloDatos[descripcion]' where TRIM(subpartida) = '$subpartida'";
      $this->query($sql);
       //echo $sql;
	   
	   $arregloDatos[mensaje]='se edito correctamente';
	   
		if ($this->_lastError) 
        {
          	$arregloDatos[mensaje]='Error al editar';
            return FALSE;
        }
 
       
    }
	function findCliente($arregloDatos) {
		$sql = "SELECT subpartida,descripcion
						FROM subpartidas  WHERE (descripcion LIKE '%$arregloDatos[q]%') OR (subpartida  LIKE '%$arregloDatos[q]%')";

		$this->query($sql);
		if($this->_lastError) {
			echo $sql;
			return TRUE;
		}
	}
	function getFormaNueva($arregloDatos) {
		
	}
	
	function addSubpartida($arregloDatos){
		$sql = "INSERT INTO subpartidas (subpartida, descripcion, arancel, detalle, obs)
				VALUES ('$arregloDatos[subpartida]', '$arregloDatos[descripcion]', $arregloDatos[arancel], '$arregloDatos[detalle]', '$arregloDatos[obs]')";
		
		$this->query($sql);
		if($this->_lastError) {
			echo $sql;
			return TRUE;
		}
		
	}
  }
?>