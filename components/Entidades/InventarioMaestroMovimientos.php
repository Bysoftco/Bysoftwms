<?php
/**
 * Description of InventarioMaestroMovimientos
 *
 * @author Teresa
 * @author Fredy Salom  <fsalom@bysoft.us>
 */

require_once(CLASSES_PATH.'BDControlador.php');

class InventarioMaestroMovimientos extends BDControlador {
  var $fecha;
  var $id_camion = 0;
  var $tip_movimiento = 0;
  var $tipo_retiro = 0;
  var $destinatario = "";
  var $direccion = "";
  var $fmm = "";
  var $producto = 0;
  var $doc_tte = '';
  var $orden = '';
  var $unidad = 0;
  var $cantidad = 0;
  var $cantidad_nac = 0;
  var $cantidad_ext = 0;
  var $peso = 0;
  var $valor = 0;
  var $ciudad = "";
  var $obs = "";
  var $cierre = 0;
  var $pedido = "";
  
  var $table_name = "inventario_maestro_movimientos";
  var $module_directory = 'Entidades';
  var $object_name = "InventarioMaestroMovimientos";
  
  var $campos = array('codigo', 'fecha', 'id_camion', 'destinatario', 'direccion', 'fmm',
                      'producto', 'doc_tte', 'orden', 'unidad', 'cantidad', 'cantidad_nac',
                      'cantidad_ext', 'peso', 'valor', 'ciudad', 'obs', 'cierre');
  
  function __construct() {
    $this->fecha=date('Y-m-d H:i');
    parent :: Manejador_BD();
  }
}
?>