<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');

class Database {
  var $_dbId;
	var $_result = NULL;

	function Database($_config) {
		if ((!$this->_dbId = mysql_connect($_config['DB_HOST'], $_config['DB_USER'], $_config['DB_PASS']))) {
			echo 'error Servidor'; die();
		}

		if ((!$vari = mysql_select_db($_config['DB_NAME'],  $this->_dbId))) {
			echo 'error Base de datos'; die();
		}
	}
	 
	function query($query) {
		if(is_string($query) and !empty($query)) {
			if ((!$this->_result = mysql_query($query, $this->_dbId))){
				echo 'Error performing query ' . $query . ' Message : ' . mysql_error();
			}
		}
	}

	function fetch() {
		if ((!$row = mysql_fetch_object($this->_result))) {
			mysql_free_result($this->_result);
			return FALSE;
		}
		return $row;
	}
	 
	function getInsertID() {
		if ($this->_dbId !== NUlL) {
			return mysql_insert_id($this->_dbId);
		}
		return NULL;
	}

	function countRows() {
		if ($this->_result !== NULL) {
			return mysql_num_rows($this->_result);
		}
		return 0;
	}

	function getArray() {
		$arreglo=array();
		$contador=0;
		while($row = $this->fetch()) {
			foreach($row as $key => $value){
				$arreglo[$contador][$key] = $value;
			}
			++$contador;
		}
		return $arreglo;
	}
	 
	function close() {
		@mysql_close($this->_dbId);
	}
}
?>