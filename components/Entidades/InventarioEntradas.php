<?php
/**
 * Description of InventarioEntradas
 *
 * @author Teresa
 * @author Fredy Salom  <fsalom@bysoft.us>
 */

require_once(CLASSES_PATH.'BDControlador.php');

class InventarioEntradas extends BDControlador {
  var $arribo = 0;
  var $orden = 0;
  var $tipo_mov = 0;
  var $fecha;
  var $referencia = '';
  var $cantidad = 0;
  var $peso = 0;
  var $valor = 0;
  var $fmm = "";
  var $modelo = "";
  var $embalaje = 0;
  var $un_empaque = 1;
  var $posicion = 1;
  var $cant_declaraciones = 0;
  var $observacion = "";
  
  var $table_name = "inventario_entradas";
  var $module_directory = 'Entidades';
  var $object_name = "InventarioEntradas";
  
  var $campos = array('arribo', 'orden', 'tipo_mov', 'fecha', 'referencia', 'cantidad', 'peso', 'valor', 'fmm', 'modelo', 'embalaje',
    'un_empaque', 'posicion', 'cant_declaraciones', 'observacion');
  
  function __construct() {
    $this->fecha=date('Y-m-d');
    parent :: Manejador_BD();
  }
}
?>