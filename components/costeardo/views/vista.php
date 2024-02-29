<?php
require_once COMPONENTS_PATH . 'costeardo/model/costeardo.php';

class CosteardoVista {
  var $datosV;
	
  function CosteardoVista() {
    $this->template = new HTML_Template_IT();
    $this->datosV = new CosteardoModelo();
  }
	
  function listadoCosteardo($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'costeardo/views/tmpl/listadoCosteardo.php' );
    $this->template->setVariable('COMODIN', '');
    $this->template->setVariable('paginacion', $arreglo['datos']['paginacion']);
    $this->template->setVariable('pagina', $arreglo['pagina']);
    $this->template->setVariable('verAlerta', 'none');
	
    $this->template->setVariable('orden', isset($arreglo['orden'])?$arreglo['orden']:"");
    $this->template->setVariable('id_orden', isset($arreglo['id_orden'])?$arreglo['id_orden']:"");
    $this->template->setVariable('campoBuscar', isset($arreglo['buscar'])?$arreglo['buscar']:"");
    	
    if(isset($arreglo['alerta_accion'])) {
      $this->template->setVariable('alerta_accion', $arreglo['alerta_accion']);
      $this->template->setVariable('verAlerta', 'block');
    }

    //Configura datos del filtro
    $this->template->setVariable('nitc',isset($arreglo['nitc'])?$arreglo['nitc']:0);
    $this->template->setVariable('fechadesdec',isset($arreglo['fechadesdec'])?$arreglo['fechadesdec']:'');
    $this->template->setVariable('fechahastac',isset($arreglo['fechahastac'])?$arreglo['fechahastac']:'');
    $this->template->setVariable('doasignadoc',isset($arreglo['doasignadoc'])?$arreglo['doasignadoc']:0);

    $numRegistro = count($arreglo['datos']['datos']);
    if($numRegistro == 0) {
      $this->template->setVariable('mensaje', "&nbsp;No hay Costos DO para mostrar");
      $this->template->setVariable('estilo', "ui-state-error");
    } else {
      $codbagcolor = 1;
      foreach($arreglo['datos']['datos'] as $value) {
        $this->template->setCurrentBlock("ROW");
        if($codbagcolor == 1) {
          $this->template->setVariable('id_tr_estilo','tr_blanco');
          $codbagcolor = 2;
        } else {
          $this->template->setVariable('id_tr_estilo','tr_gris_cla');	
          $codbagcolor = 1;
        }
        $this->template->setVariable('do_asignado', $value['do_asignado']);
				$this->template->setVariable('sede', $value['sede']);
        $this->template->setVariable('fecha', $value['fecha']);
				$this->template->setVariable('razon_social', $value['razon_social']);
				$this->template->setVariable('documento', $value['doc_tte']);
				$this->template->setVariable('ubicacion', $value['bodega_nombre']);
				$this->template->setVariable('ingreso_total', number_format($value['ingreso_total'],2));
        $this->template->setVariable('gasto_total', number_format($value['gasto_total'],2));
				$this->template->setVariable('utilidad', number_format($value['ingreso_total'] - $value['gasto_total'],2));
        //Verifica el valor de la Utilidad
        if(($value['ingreso_total'] - $value['gasto_total'])<0) $this->template->setVariable('color', 'red');
        else $this->template->setVariable('color', '');
        $this->template->parseCurrentBlock("ROW");
      }
    }
    $this->template->show();
  }

	function filtroCosteardo($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'costeardo/views/tmpl/filtroCosteardo.php' );
    $this->template->setVariable('COMODIN', '');
    $this->template->show();
	}
}
?>