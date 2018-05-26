<?php
require_once("MovimientoDatos.php");
require_once("MovimientoPresentacion.php");
require_once("ReporteExcel.php");

class MovimientoLogica {
  var $datos;
  var $pantalla;

  function MovimientoLogica() {
    $this->datos =& new Movimiento();
    $this->pantalla =& new MovimientoPresentacion($this->datos);
  }

  // Método que abre el formulario para hacer reapertura de Ordenes
  function maestroReapertura($arregloDatos) {
    $arregloDatos['titulo'] = $this->tituloReapertura($arregloDatos);
    $this->pantalla-> maestroReapertura($arregloDatos);
	}
        
  // Función que crea o mantiene el título en reaperturas
  function tituloReapertura($arregloDatos) {
    $unDato = new Orden();

    $titulo = '';
    if(!empty($arregloDatos['por_cuenta_filtro'])) {
      $arregloDatos[por_cuenta] = $arregloDatos[por_cuenta_filtro];
      $unDato->existeCliente($arregloDatos);
      $unDato->fetch();
      $titulo = $unDato->razon_social;
    }

    if(!empty($arregloDatos[ubicacion_filtro])) {
      $titulo .= " ubicación ".$unDato->dato('do_bodegas','codigo',$arregloDatos[ubicacion_filtro]);
    }

    if(!empty($arregloDatos[estado_filtro])) {
      $titulo .= " estado ".$unDato->dato('do_estados','codigo',$arregloDatos[estado_filtro]);
    }

    if(!empty($arregloDatos[fecha_inicio]) and !empty($arregloDatos[fecha_fin])) {
      $titulo .= " desde ".$arregloDatos[fecha_inicio]." hasta ".$arregloDatos[fecha_fin];
    }
    
    if(!empty($arregloDatos[doc_filtro])) {
      $titulo .= " documento ".$arregloDatos[doc_filtro];
    }

    if(!empty($arregloDatos[do_filtro])) {
      $titulo .= " Do ".$arregloDatos[do_filtro];
    }

    return ucwords(strtolower($titulo));
  }
}  	
?>