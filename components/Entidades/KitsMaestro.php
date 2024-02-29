<?php
/**
 * Description of KitsMaestro
 *
 * @author Teresa
 */

require_once(CLASSES_PATH.'BDControlador.php');
class KitsMaestro  extends BDControlador{
    
    var $id;
    var $id_referencia;
    var $codigo_kit;
    var $nombre_kit;
    var $descripcion;
    var $fecha_creacion;
    var $cliente;
    var $eliminado=0;
    
    var $table_name = "kits_maestro";
    var $module_directory= 'Entidades';
    var $object_name = "KitsMaestro";
    
    var $campos = array('id', 'id_referencia', 'codigo_kit', 'nombre_kit', 'descripcion', 'fecha_creacion', 'cliente');
    
    function __construct(){
        parent :: Manejador_BD();
    }
    
}

?>
