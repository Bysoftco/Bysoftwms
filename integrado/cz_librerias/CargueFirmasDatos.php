<?php

require_once("MYDB.php"); 

class CargueFirmas extends MYDB {

    function CargueFirmas() {
        $this->estilo_error = "ui-state-error";
        $this->estilo_ok = "ui-state-highlight";

        $this->mensaje_error = " error al guardar el registro";
        $this->mensaje_ok = " Se guardo correctamente el registro1x";
    }

    function filtro($arregloDatos) {
        
    }

   
   function guardaRuta(&$arregloDatos) {
   		 $sede = $_SESSION['sede']; 
		 $fecha = FECHA;
    	 $sql = "INSERT INTO firmas(sede,ruta,fecha_de_creacion)
				 VALUES('$sede','firmas/$arregloDatos[nombre_archivo]','$fecha')";
				
		$arregloDatos[sql]=$sql;		
		$this->query($sql);
		if ($this->_lastError) {
			$arregloDatos[mensaje]="Error al guardar la ruta de la firma";
			$arregloDatos[estilo] = $this->estilo_error;    
        }
		 $arregloDatos[estilo] = $this->estilo_ok; 	
		 $arregloDatos[mensaje]=" La firma se cargo correctamente"; 
   }
     
      
    

    /*     * FIN* */
}

?>