<?php
/**
 * Description of KitsDetalle
 *
 * @author Teresa
 */
require_once(CLASSES_PATH.'BDControlador.php');
class KitsDetalle extends BDControlador{
    
    var $id_kit;
    var $codigo_referencia;
    var $cantidad_en_kit;
    
    var $table_name = "kits_detalle";
    var $module_directory= 'Entidades';
    var $object_name = "KitsDetalle";
    
    var $campos = array('id_kit','codigo_referencia','cantidad_en_kit');
    
    function __construct(){
        parent :: Manejador_BD();
    }
}

?>
