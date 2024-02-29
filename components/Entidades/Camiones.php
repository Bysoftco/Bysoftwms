<?php
require_once(CLASSES_PATH.'BDControlador.php');

class Camiones extends BDControlador {
  var $codigo;
  var $placa;
  var $conductor_nombre;
  var $conductor_identificacion;
  var $empresa;
  var $descripcion;
  var $activo;
  
  var $table_name = "camiones";
  var $module_directory = 'Entidades';
  var $object_name = "Camiones";
  
  var $campos = array('codigo','placa','conductor_nombre','conductor_identificacion','empresa','descripcion',
    'activo');
  
  function __construct() {
    parent :: Manejador_BD();
  }
}
?>