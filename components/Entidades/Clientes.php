<?php
require_once(CLASSES_PATH.'BDControlador.php');

class Clientes extends BDControlador {
  var $tipo_documento;
  var $numero_documento;
  var $digito_verificacion;
  var $razon_social;
  var $tipo;
  var $direccion;
  var $telefonos_fijos;
  var $telefonos_celulares;
  var $telefonos_faxes;
  var $pagina_web;
  var $actividad_economica;
  var $actividad_sec;
  var $ciudad;
  var $correo_electronico;
  var $tipo_cliente;
  var $contacto;
  var $mailcontacto;
  var $telefonoscontacto;
  var $sede;
  var $regimen;
  var $autoretenedor;
  var $vendedor;
  var $subcentro_costo;
  var $subcentro_alterno;
  var $dias_para_facturar;
  var $tipo_facturacion;
  var $periodicidad;
  var $dias_gracia;
  var $tarifa_asignada;
  var $cir170;
  var $cuenta;
  
  var $table_name = "clientes";
  var $module_directory = 'Entidades';
  var $object_name = "Clientes";
  
  var $campos = array('tipo_documento','numero_documento','digito_verificacion','razon_social','tipo','direccion','telefonos_fijos',
    'telefonos_celulares','telefonos_faxes','pagina_web','actividad_economica','actividad_sec','ciudad','correo_electronico','tipo_cliente',
    'contacto','mailcontacto','telefonoscontacto','sede','regimen','autoretenedor','vendedor','subcentro_costo','subcentro_alterno',
    'dias_para_facturar','tipo_facturacion','periodicidad','dias_gracia','tarifa_asignada','cir170','cuenta');
  
  function __construct() {
    parent :: Manejador_BD();
  }
}
?>