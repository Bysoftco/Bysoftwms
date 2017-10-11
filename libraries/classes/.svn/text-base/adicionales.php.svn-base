<?php

	function build_list($table,$code,$name_camp)
	 {
	 	global $db;
        $sql = "SELECT $code,$name_camp
				FROM $table";	
         
    	$db->query($sql);
		$list = $db->GetArray(); 
		$array = array();
		
		foreach ($list as $key=>$index) 
	    {
		 foreach($index as $keyAux=>$value)
		  {
		   $je = $keyAux;
		   $$je = $value;
		  }
		   $array[$$code] = $$name_camp;
	    }
		return $array;
     }	

	function armSelect($array,  $title = '-',$seleccion='NA' ,$maxCaracteres = 50)
     {
       $returnValue = "<OPTION VALUE=\"\" SELECTED>$title</OPTION> \n";
       foreach($array as $key => $value)
        {
          $selected  = ($seleccion == $key)? ' SELECTED' : '';
	      $returnValue.= "<OPTION VALUE=\""
		  . $key
		  . "\"$selected>"
		  . htmlentities( ucwords( substr($value, 0, $maxCaracteres)), ENT_QUOTES). "</OPTION>\n";
        }
       return $returnValue;
     }
     
/**
 * Recupera del POST los datos para poblar el objeto
 *
 * @param ID $proyecto_id proyecto actual que se esta trabajando 
 * @param OBJECT $objeto_domesa objeto que se va a poblar
*/	
function recuperar_Post(&$objeto_domesa, $campos = array()){
	if (count($campos) > 0){
		$variables_objeto = $campos;
	}else{
		$variables_objeto = get_class_vars(get_class($objeto_domesa));	
	}
	foreach($variables_objeto as $variable => $valor){
		if(isset($_POST[$variable])){
			$objeto_domesa->$variable = utf8_decode($_POST[$variable]);
		}else{			
			if(!empty($_POST[$variable])){
				$objeto_domesa->$variable = '';
			}else{
				$objeto_domesa->$variable = $objeto_domesa->$variable;
			}
		}
	}
}

function module_language_return($lenguage, $module){
	$return_value = array();
	if(file_exists("modules/$module/lenguage/$lenguage.php")){
		include("modules/$module/lenguage/$lenguage.php");
		$return_value = $module_strings;
	}
	return $return_value;
}

function __P($datos, $var_dump = null){
	print "<pre>";
	if($var_dump != null){
		var_dump($datos);
	}else{
		print_r($datos);
	}
	print "</pre>";
}

function set_flash($tipo = 'notice', $mensaje = ''){
    clean_flash();
    $_SESSION['flash']['tipo'] = $tipo;
    $_SESSION['flash']['mensaje'] = $mensaje;
}

function get_flash($tipo = 'notice', $mensaje = ''){
    return (isset($_SESSION['flash']) && count($_SESSION['flash']) > 0)?$_SESSION['flash']:false;
}

function clean_flash(){
    unset($_SESSION['flash']);
}

?>