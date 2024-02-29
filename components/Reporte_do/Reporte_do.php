<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH.'Reporte_do/model/Reporte_doDatos.php');
require_once(COMPONENTS_PATH.'Reporte_do/views/Reporte_doVista.php');
require_once(COMPONENTS_PATH.'Reporte_do/views/tmpl/reporteExcel.php');

class Reporte_do {
  var $vista;
  var $datos;

  function Reporte_do() {
    $this->vista = new Reporte_doVista();
    $this->datos = new Reporte_doDatos();
  }

  function filtroReporte($arreglo) {
    $this->vista->filtroReporte($arreglo);
  }

  function mostrarListadoOrdenes($arreglo) {
    $this->vista->mostrarListadoOrdenes($arreglo);
  }

  function verDetalleDo($arreglo){
    $this->vista->verDetalleDo($arreglo);
  }
  
  function exportarExcel($arreglo) {
    $arreglo['datos'] = $this->datos->retornarInfoOrdenes($arreglo);
    
    $datosExcel = new reporteExcel();
    $datosExcel->generarExcel($arreglo);
  }
}
?>