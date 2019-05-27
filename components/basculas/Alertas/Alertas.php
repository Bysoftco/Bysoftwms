<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'Alertas/views/AlertasVista.php';

class Alertas {
  var $vista;
  var $datos;

  function Alertas() {
    $this->vista = new AlertasVista();
  }
  
  
  function mostrarAlertas($arreglo) {
    $this->vista->mostrarAlertas($arreglo);
  }
}
?>