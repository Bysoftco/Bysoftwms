<?php
require_once 'DB/DataObject.php';

class MYDB extends DB_DataObject {
  var $__table = 'remesas';                        
		
  function __clone() { return $this; }
  function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('MYDB',$k,$v); }
}
?>