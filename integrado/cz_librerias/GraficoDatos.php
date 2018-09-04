<?php

	require_once("MYDB.php"); 
	
	class Grafico extends  MYDB  {

		function Grafico() {
		
    	} 
		
		function cantidadCarpetasGrado($arregloDatos) {
			$sql  = "
					select tareas.descripcion as datos,
					count(distinct num_radicacion) as valores,
					dc.tarea
					from 		detalle_documento dc,tareas
					where    	estado not  in(0)
					and  dc.tarea=tareas.codigo_tarea
					and tareas.Codigo_tarea not in(4)
					group by tarea,tareas.descripcion
					
					order by dc.tarea " ;
 
			$this->query($sql); 
			if ($this->_lastError) {
				//$this->logger->warn('SelectCOLEGIO'.htmlentities($sql, ENT_QUOTES) );
				//$this->logger->warn($this->_lastError->getMessage());
				return FALSE;
			}
			return TRUE;	
		   		 	
		}
		
			
		
 	}  
?>