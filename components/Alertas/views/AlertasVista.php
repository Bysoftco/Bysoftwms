<?php
require_once COMPONENTS_PATH . 'Alertas/model/AlertasDatos.php';

class AlertasVista {
  var $template;
  var $datos;

  function AlertasVista() {
    $this->template = new HTML_Template_IT();
    $this->datos = new AlertasDatos();
  }
  
  function mostrarAlertas($arreglo) {
    $this->template->loadTemplateFile( COMPONENTS_PATH . 'Alertas/views/tmpl/listadoAlertas.php' );
    $this->template->setVariable('COMODIN', '' );
    
    $noNacionales = $this->datos->retornarNoNacional();
    
    $perfil = $_SESSION['datos_logueo']['perfil_id']; $usuario = $_SESSION['datos_logueo']['usuario'];
    
    // Verifica si el perfil corresponde al de un tercero
    if($perfil != 23) {
      foreach($noNacionales as $value) {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('tipo_alerta', 'Nacionalización');
        $this->template->setVariable('doc_cliente', $value['doc_cliente']);
        $this->template->setVariable('nom_cliente', $value['nom_cliente']);
        $this->template->setVariable('orden', $value['orden']);
        $this->template->setVariable('doc_tte', $value['doc_tte']);
        $this->template->setVariable('arribo', $value['arribo']);
        $this->template->setVariable('nom_referencia', $value['nom_referencia']);
        $this->template->setVariable('cod_referencia', $value['cod_referencia']);
        $this->template->setVariable('cantidad', number_format($value['sum_cantidad_nonac'],2,",","."));
        $this->template->setVariable('fmm', $value['fmm']);
        $this->template->setVariable('fecha_manifiesto', date("d/m/Y", strtotime($value['fecha_manifiesto'])));
        $this->template->setVariable('fecha_limite', date("d/m/Y", strtotime($value['fecha_limite'])));
        $this->template->setVariable('prorroga', $value['prorroga']);
        $this->template->setVariable('control_final', $value['control_final']);
        $this->template->setVariable('unidad_comercial', $value['unidad_comercial']);
      
        $tiempo_total = $this->dateDiff($value['fecha_manifiesto'], $value['fecha_limite']);
        $tiempo_restante = $this->dateDiff(date('Y/m/d'), $value['fecha_limite']);
      
        if($tiempo_restante <= 0) {
          $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
        } else {
          if($tiempo_total == 0) {
            $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
          } else {
            $porcentaje = (100 * $tiempo_restante) / $tiempo_total;
            if($porcentaje <= 10) {
              $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
            } else if($porcentaje <= 30) {
              $this->template->setVariable('bandera', 'img/estrellas/star_gold.ico');
            } else {
              $this->template->setVariable('bandera', 'img/estrellas/star_green.ico');
            }
          }
        }
        $this->template->parseCurrentBlock("ROW");      
      }
    } else {
      foreach($noNacionales as $value) {
        $this->template->setCurrentBlock("ROW");
        if($usuario == $value['doc_cliente']) {
          $this->template->setVariable('tipo_alerta', 'Nacionalización');
          $this->template->setVariable('doc_cliente', $value['doc_cliente']);
          $this->template->setVariable('nom_cliente', $value['nom_cliente']);
          $this->template->setVariable('orden', $value['orden']);
          $this->template->setVariable('doc_tte', $value['doc_tte']);
          $this->template->setVariable('arribo', $value['arribo']);
          $this->template->setVariable('nom_referencia', $value['nom_referencia']);
          $this->template->setVariable('cod_referencia', $value['cod_referencia']);
          $this->template->setVariable('cantidad', number_format($value['sum_cantidad_nonac'],2,",","."));
          $this->template->setVariable('fmm', $value['fmm']);
          $this->template->setVariable('fecha_manifiesto', date("d/m/Y", strtotime($value['fecha_manifiesto'])));
          $this->template->setVariable('fecha_limite', date("d/m/Y", strtotime($value['fecha_limite'])));
          $this->template->setVariable('prorroga', $value['prorroga']);
          $this->template->setVariable('control_final', $value['control_final']);
          $this->template->setVariable('unidad_comercial', $value['unidad_comercial']);
      
          $tiempo_total = $this->dateDiff($value['fecha_manifiesto'], $value['fecha_limite']);
          $tiempo_restante = $this->dateDiff(date('Y/m/d'), $value['fecha_limite']);
      
          if($tiempo_restante <= 0) {
            $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
          } else {
            if($tiempo_total == 0) {
              $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
            } else {
              $porcentaje = (100 * $tiempo_restante) / $tiempo_total;
              if($porcentaje <= 10) {
                $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
              } else if($porcentaje <= 30) {
                $this->template->setVariable('bandera', 'img/estrellas/star_gold.ico');
              } else {
                $this->template->setVariable('bandera', 'img/estrellas/star_green.ico');
              }
            }
          }      
        }
        $this->template->parseCurrentBlock("ROW");          
      }
    }
    $this->alertaTransformacion();
    $this->template->show();
  }
  
  function alertaTransformacion() {
    $procParcial = $this->datos->retornarprocParcial();
    $perfil = $_SESSION['datos_logueo']['perfil_id']; $usuario = $_SESSION['datos_logueo']['usuario'];
    
    // Verifica si el perfil corresponde al de un tercero
    if($perfil != 23) {
      foreach($procParcial as $value) {
        $this->template->setCurrentBlock("ROW");
        $this->template->setVariable('tipo_alerta', 'Procesamiento Parcial');
        $this->template->setVariable('doc_cliente', $value['por_cuenta']);
        //$this->template->setVariable('nom_cliente',       $value['nom_cliente'] );
        $this->template->setVariable('orden', $value['orden']);
        $this->template->setVariable('doc_tte', $value['doc_tte']);
        $this->template->setVariable('arribo', $value['arribo']);
        $this->template->setVariable('nom_referencia', $value['nombre_referencia']);
        $this->template->setVariable('cod_referencia', $value['codigo_referencia']);
        $this->template->setVariable('cantidad', number_format($value['cantidad'],2,",","."));
        $this->template->setVariable('fmm', $value['fmm']);
        $this->template->setVariable('fecha_manifiesto', date("d/m/Y", strtotime($value['fecha_manifiesto'])));
      
        $prorroga = $this->datos->retornarProrroga($value['orden'], 14);
        $fecha_limite = $value['fecha_limite'];
        $this->template->setVariable('prorroga', "NO");
        if(!empty($prorroga)) {
          $this->template->setVariable('prorroga', "SI");
          if($fecha_limite<$prorroga->fecha) {
            $fecha_limite = $prorroga->fecha;
          }
        }
      
        $this->template->setVariable('fecha_limite', date("d/m/Y", strtotime($fecha_limite)));
        $control = $this->datos->retornarProrroga($value['orden']);
        if(!empty($control)) {
          $this->template->setVariable('control_final', $control->nombre_control);
        }
      
        $tiempo_total = $this->dateDiff($value['fecha_manifiesto'], $fecha_limite);
        $tiempo_restante = $this->dateDiff(date('Y/m/d'), $value['fecha_limite']);
        if(date('Y/m/d') > $value['fecha_limite']) { }
        if($tiempo_restante <= 0) {
          $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
        } else {
          if($tiempo_total == 0) {
            $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
          } else {
            $porcentaje = (100 * $tiempo_restante) / $tiempo_total;
            if($porcentaje <= 10) {
              $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
            } else if($porcentaje <= 30) {
              $this->template->setVariable('bandera', 'img/estrellas/star_gold.ico');
            } else {
              $this->template->setVariable('bandera', 'img/estrellas/star_green.ico');
            }
          }
        }
        $this->template->parseCurrentBlock("ROW");
      }
    } else {
      foreach($procParcial as $value) {
        $this->template->setCurrentBlock("ROW");
        if($usuario == $value['por_cuenta']) {
          $this->template->setVariable('tipo_alerta', 'Procesamiento Parcial');
          $this->template->setVariable('doc_cliente', $value['por_cuenta']);
          //$this->template->setVariable('nom_cliente',       $value['nom_cliente'] );
          $this->template->setVariable('orden', $value['orden']);
          $this->template->setVariable('doc_tte', $value['doc_tte']);
          $this->template->setVariable('arribo', $value['arribo']);
          $this->template->setVariable('nom_referencia', $value['nombre_referencia']);
          $this->template->setVariable('cod_referencia', $value['codigo_referencia']);
          $this->template->setVariable('cantidad', number_format($value['cantidad'],2,",","."));
          $this->template->setVariable('fmm', $value['fmm']);
          $this->template->setVariable('fecha_manifiesto', date("d/m/Y", strtotime($value['fecha_manifiesto'])));
      
          $prorroga = $this->datos->retornarProrroga($value['orden'], 14);
          $fecha_limite = $value['fecha_limite'];
          $this->template->setVariable('prorroga', "NO");
          if(!empty($prorroga)) {
            $this->template->setVariable('prorroga', "SI");
            if($fecha_limite<$prorroga->fecha) {
              $fecha_limite = $prorroga->fecha;
            }
          }
      
          $this->template->setVariable('fecha_limite', date("d/m/Y", strtotime($fecha_limite)));
          $control = $this->datos->retornarProrroga($value['orden']);
          if(!empty($control)) {
            $this->template->setVariable('control_final', $control->nombre_control);
          }
      
          $tiempo_total = $this->dateDiff($value['fecha_manifiesto'], $fecha_limite);
          $tiempo_restante = $this->dateDiff(date('Y/m/d'), $value['fecha_limite']);
          if(date('Y/m/d') > $value['fecha_limite']) { }
          if($tiempo_restante <= 0) {
            $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
          } else {
            if($tiempo_total == 0) {
              $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
            } else {
              $porcentaje = (100 * $tiempo_restante) / $tiempo_total;
              if($porcentaje <= 10) {
                $this->template->setVariable('bandera', 'img/estrellas/star_red.ico');
                } else if($porcentaje <= 30) {
                $this->template->setVariable('bandera', 'img/estrellas/star_gold.ico');
              } else {
                $this->template->setVariable('bandera', 'img/estrellas/star_green.ico');
              }
            }
          }
        }
        $this->template->parseCurrentBlock("ROW");
      }      
    }
  }
  
  function dateDiff($start, $end) { 
    $start_ts = strtotime($start); 
    $end_ts = strtotime($end); 
    $diff = $end_ts - $start_ts; 
    return round($diff / 86400); 
  } 
}
?>