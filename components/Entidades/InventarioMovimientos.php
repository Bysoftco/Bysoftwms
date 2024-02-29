<?php
/**
 * Description of InventarioMovimientos
 *
 * @author Teresa
 * @author Fredy Salom <fsalom@bysoft.us>
 */

require_once(CLASSES_PATH.'BDControlador.php');

class InventarioMovimientos extends BDControlador {
  var $inventario_entrada;
  var $fecha;
  var $tipo_movimiento = 0;
  var $cod_maestro;
  var $num_levante = '';
  var $peso_naci = 0;
  var $peso_nonac = 0;
  var $cantidad_naci = 0;
  var $cantidad_nonac = 0;
  var $cif = 0;
  var $fob_nonac = 0;
  var $estado_mcia = 1;
  var $flg_control = 1;
  
  var $table_name = "inventario_movimientos";
  var $module_directory = 'Entidades';
  var $object_name = "InventarioMovimientos";
  
  var $campos = array('inventario_entrada', 'fecha', 'tipo_movimiento', 'cod_maestro', 'num_levante', 'peso_naci', 
    'peso_nonac', 'cantidad_naci', 'cantidad_nonac', 'cif', 'fob_nonac', 'estado_mcia', 'flg_control');
  
  function __construct() {
    parent :: Manejador_BD();
  }
}
?>