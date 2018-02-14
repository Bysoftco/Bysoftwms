<?php
ini_set('include_path', PATH .'./integrado/cz_librerias/pear/');
require_once("PEAR.php");
require_once("DB/DataObject/Cast.php");
require_once("HTML/Template/IT.php");

function init() {
  global $params; 
  global $paramsLdap; 
  global $cadenaBusqueda; 
  global $baseBusqueda; 
  global $servidorLDAP; 
   // setlocale (LC_NUMERIC, ""); //Línea para permitir el uso de decimales en el sitio. permite comas,  LOCAL 
  setlocale(LC_ALL, 'es_ES'); 
  //setlocale (LC_NUMERIC, ""); //Línea para permitir el uso de decimales en el sitio. permite comas, 
  $config = parse_ini_file(CONFIG_FILE, true);
  $options = null;
  foreach ($config as $class => $values) {
    $options = &PEAR::getStaticProperty($class, 'options');
    $options = $values;
  } 
  // Arreglo de parametros para autenticación de usuarios
  $params = array('dsn'         => $config['DB_DataObject']['database'],
                  'table' 	  => 'USUARIO',
                  'usernamecol' => 'NOMBREUSUARIO',
                  'passwordcol' => 'CONTRASENA'
            );

  require_once 'DB/DataObject.php';
  $unDataObject = new DB_DataObject();
}

function tiposCliente() {
  $tipos= array('1' => 'Consignatario',
                '2' => 'Intermediario'
          );
  return  $tipos;	  
}
	
function tiposBodega() {
  $tipos= array('1' => 'Bodega Zona',
                '2' => 'Bodega proceso'
          );
  return  $tipos;	  
}
	
function etiquetas() {
  $tipos= array('0' => 'No Etiquetar',
                '1' => 'Etiqueta 1',
                '2' => 'Etiqueta 2',
                '3' => 'Etiqueta 3',
                '4' => 'Etiqueta 4',
                '5' => 'Etiqueta 5'
          );
  return  $tipos;	  
}
	
function unidades() {
  $unidad = array('1' => 'Kilo',
                  '2' => 'Unidad'
            );
  return  $unidad;	  
}
	
function armaLista($arreglo,  $titulo = '-',$seleccion='NA' ,$maxCaracteres = 50) {
  $seleccionado = ($seleccion=='NA')? '' : ' SELECTED ';
	
	$returnValue = "<OPTION VALUE=\"\" SELECTED>$titulo</OPTION> \n";
	foreach($arreglo as $key => $value) {
    $selected  = ($seleccion == $key)? '$seleccionado ' : '';
		$returnValue.= "<OPTION VALUE=\""
			. $key
			. "\"$selected>"
			. htmlentities(
				ucwords(
					substr($value, 0, $maxCaracteres)
				)
				, ENT_QUOTES
			)
			. "</OPTION>\n";
	}
	
	return $returnValue;
}

//Cuando se requiere que un elemento de la lista quede seleccionado

function armaSelect($arreglo,  $titulo = '-',$seleccion='NA' ,$maxCaracteres = 50) {
  $returnValue = "<OPTION VALUE=\"\" SELECTED>$titulo</OPTION> \n";
	foreach($arreglo as $key => $value) {           
    $selected  = ($seleccion == $key)? ' SELECTED' : '';   
		$returnValue.= "<OPTION VALUE=\""
			. $key
			. "\"$selected>"
			. htmlentities(
				ucwords(
					substr($value, 0, $maxCaracteres)
				)
				, ENT_QUOTES
			)
			. "</OPTION>\n";
	}
	return $returnValue;
}

function armaSelectSinTitulo($arreglo,  $titulo = '-',$seleccion='NA' ,$maxCaracteres = 50) {
  foreach($arreglo as $key => $value) {
    $selected  = ($seleccion == $key)? ' SELECTED' : '';    
		$returnValue.= "<OPTION VALUE=\""
			. $key
			. "\"$selected>"
			. htmlentities(
				ucwords(
					substr($value, 0, $maxCaracteres)
				)
				, ENT_QUOTES
			)
			. "</OPTION>\n";
	}
	return $returnValue;
}

function formatoFecha($fecha) {
	$fecha 	= split('-',$fecha);
	$fecha 	=$fecha[0].$fecha[1].$fecha[2];
	return $fecha;
}

function fechaddmmaaaa($fecha) {
	$fecha 	= split('-',$fecha);
	//$fecha 	=$fecha[0].$fecha[1].$fecha[2];
	$fecha=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
	return $fecha;
}

function lista($tabla,$codigo,$nombre) {	
  $unaLista= new Remesa();
  $sql = "SELECT $codigo,$nombre FROM $tabla";	

  $unaLista->query($sql);
  $arreglo = array();
  while($unaLista->fetch()) {
    $arreglo[$unaLista->$codigo] = $unaLista->$nombre;
  }
  	
  return array_flip($arreglo);      
}

function cargarArregloBD($tabla, $campoIndice, $campoValor, $order = '', $condicion = '') {
	require_once($tabla . '.php');
	$DOTabla = 'DataObject' . $tabla;
	$unArreglo = new $DOTabla();
	
	$unArreglo->selectAdd();
	$unArreglo->selectAdd($campoIndice);
	$unArreglo->selectAdd($campoValor);
	if (!empty($condicion)) {
		$unArreglo->whereAdd($condicion);
	}
	$order = (empty($order))?$campoIndice:$order;
	$unArreglo->orderBy($order);
	$unArreglo->find();
	
	$arreglo = array();
	while($unArreglo->fetch()){
		$arreglo[$unArreglo->$campoIndice] = $unArreglo->$campoValor;
	}
	return array_flip($arreglo);
}
?>