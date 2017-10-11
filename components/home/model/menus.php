<?php
class MenusModelo{

	function MenusModelo(){
	}
	
	function armar_menu_principal(){
		$this->crearArbol('id','nombre','id_padre',0,'-');
	}
	
	function crearArbol($id_field, $show_data, $link_field, $parent, $prefix){
		global $db;
	
	    $sql='select * from menus where mostrar=1 AND '.$link_field.'='.$parent;
	    $db->query($sql);
	    
	    $arreglo = $db->getArray();
	    foreach($arreglo as $value)
	    {
	    	echo($prefix.'  <a href="'.$value['enlace'].'">'.$value[$show_data].'</a><br>');
	    	$this->crearArbol($id_field,$show_data,$link_field,$value[$id_field],$prefix.$prefix);
	    } 
	}
}
?>