<?php
/**
 * Description of Referencias
 *
 * @author Teresa
 * @author Fredy Salom <fsalom@bysoft.us>
 */

require_once(CLASSES_PATH.'BDControlador.php');

class Referencias extends BDControlador {
  var $codigo;
  var $codigo_ref;
  var $ref_prove;
  var $nombre;
  var $observaciones;
  var $cliente;
  var $parte_numero;
  var $unidad;
  var $unidad_venta;
  var $presentacion_venta;
  var $fecha_expira;
  var $min_stock;
  var $lote_cosecha;
  var $alto;
  var $largo;
  var $ancho;
  var $serial;
  var $tipo;
  
  var $table_name = "referencias";
  var $module_directory = 'Entidades';
  var $object_name = "Referencias";
  
  var $campos = array('codigo','codigo_ref','ref_prove','nombre','observaciones','cliente','parte_numero','unidad','unidad_venta',
    'presentacion_venta','fecha_expira','min_stock','lote_cosecha','alto','largo','ancho','serial','tipo');
  
  function __construct() {
    parent :: Manejador_BD();
  }
}
?>