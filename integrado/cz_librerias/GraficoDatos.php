<?php
require_once("MYDB.php"); 
	
class Grafico extends MYDB {
	function Grafico() {} 
		
	function cantidad($arregloDatos) {
		$sql = "
					 " ;
 
		$this->query($sql); 
		if($this->_lastError) {
			//$this->logger->warn('SelectCOLEGIO'.htmlentities($sql, ENT_QUOTES) );
			//$this->logger->warn($this->_lastError->getMessage());
			return FALSE;
		}
		return TRUE;	
	}
}  
?>