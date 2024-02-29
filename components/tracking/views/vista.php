<?php
require_once COMPONENTS_PATH.'tracking/model/tracking.php';

class TrackingVista {
  var $datosV;
	
  function TrackingVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datosV = new TrackingModelo();
  }
	
  function listadoTracking($arreglo) {
    $this->template->loadTemplateFile('tracking/views/tmpl/listadoTracking.php' );
    $this->template->setVariable('COMODIN','');
    $this->template->setVariable('paginacion',$arreglo['datos']['paginacion']);
    $this->template->setVariable('pagina',$arreglo['pagina']);
    $this->template->setVariable('verAlerta','none');
	
    $this->template->setVariable('perfil',$arreglo['perfil']);
        	
    if(isset($arreglo['alerta_accion'])) {
      $this->template->setVariable('alerta_accion',$arreglo['alerta_accion']);
      $this->template->setVariable('verAlerta','block');
    }

    //Configura datos de filtro
    $this->template->setVariable('nitt',$arreglo['nitt']);
    $this->template->setVariable('fechadesdet',$arreglo['fechadesdet']);
    $this->template->setVariable('fechahastat',$arreglo['fechahastat']);
    $this->template->setVariable('doasignadot',$arreglo['doasignadot']);
    $this->template->setVariable('docttet',$arreglo['docttet']);
    $this->template->setVariable('emaildestino',$arreglo['emaildestino']);
    $numRegistro = count($arreglo['datos']['datos']);
    if($numRegistro == 0) {
      $this->template->setVariable('mensaje',"&nbsp;No hay Tracking para mostrar");
      $this->template->setVariable('estilo',"ui-state-error");
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
        $this->template->setVariable('id',$value['id']);
        $this->template->setVariable('sede',$value['sede']);
        $this->template->setVariable('do_asignado',$value['do_asignado']);
        $this->template->setVariable('doc_tte',$value['doc_tte']);
        $this->template->setVariable('por_cuenta',$value['por_cuenta']);
        $this->template->setVariable('fecha',date('Y-m-d h:i',strtotime($value['fecha'])));
				$this->template->setVariable('razon_social',$value['razon_social']);
        $this->template->setVariable('remite',$value['remite']);
        $this->template->setVariable('destino',$value['destino']);
        $this->template->setVariable('asunto',$value['asunto']);
        $this->template->setVariable('adjuntos',$value['adjuntos']);
        $this->template->setVariable('mensajex',$value['mensaje']);
        $this->template->setVariable('creador',$value['creador']);
        $this->template->setVariable('forma',$value['forma']);
        $this->template->parseCurrentBlock("ROW");
      }
    }
    
    $this->template->show();
  }

	function filtroTracking($arreglo) {
    $this->template->loadTemplateFile('tracking/views/tmpl/filtroTracking.php');
    $this->template->setVariable('COMODIN','');
    $this->template->show();
	}
  
  function agregarTracking($arreglo) {
    $this->template->loadTemplateFile('tracking/views/tmpl/editarTracking.php');
    $this->template->setVariable('COMODIN','');

    $this->template->setVariable('id',$arreglo['datosTracking']['id']);
    $this->template->setVariable('sede',$arreglo['datosTracking']['sede']);
    $this->template->setVariable('do_asignado',$arreglo['datosTracking']['do_asignado']);
    $this->template->setVariable('por_cuenta',$arreglo['datosTracking']['por_cuenta']);
    $this->template->setVariable('razon_social',$arreglo['datosTracking']['razon_social']);
    $this->template->setVariable('fecha',$arreglo['datosTracking']['fecha']);
    $this->template->setVariable('remite',$arreglo['datosTracking']['remite']);
    $this->template->setVariable('destino',$arreglo['datosTracking']['destino']);
    $this->template->setVariable('asunto','DO: '.$arreglo['datosTracking']['sigla'].'-'.$arreglo['datosTracking']['do_asignado'].' - Documento Transporte: '.$arreglo['datosTracking']['doc_tte']);
    $this->template->setVariable('adjunto','Este correo no contiene documentos adjuntos.');
    $this->template->setVariable('creador',$arreglo['datosTracking']['creador']);
    $this->template->setVariable('creado',$arreglo['datosTracking']['creador']);
    $this->template->setVariable('forma',$arreglo['datosTracking']['forma']);

    $this->template->show();      
  }
  
  function verTracking($arreglo) {
    $this->template->loadTemplateFile('tracking/views/tmpl/verTracking.php' );
    $this->template->setVariable('COMODIN','');
    
    $this->template->setVariable('id',$arreglo['datosTracking']['id']);
    $this->template->setVariable('sede',$arreglo['datosTracking']['sede']);
    $this->template->setVariable('fecha',$arreglo['datosTracking']['fecha']);
    $this->template->setVariable('razon_social',$arreglo['datosTracking']['razon_social']);
    $this->template->setVariable('remite',$arreglo['datosTracking']['remite']);
    $this->template->setVariable('destino',$arreglo['datosTracking']['destino']);
    $this->template->setVariable('asunto',$arreglo['datosTracking']['asunto']);
    $this->template->setVariable('adjunto',!empty($arreglo['datosTracking']['adjuntos'])?$arreglo['datosTracking']['adjuntos']:'Este correo no contiene documentos adjuntos.');
    $this->template->setVariable('mensaje',$arreglo['datosTracking']['mensaje']);
    $this->template->setVariable('creador',$arreglo['datosTracking']['creador']);
    $this->template->setVariable('creado',$arreglo['datosTracking']['creador']);
    $this->template->setVariable('forma',$arreglo['datosTracking']['forma']);
	
    $this->template->show();
  }
  
	function mostrarMensaje($arreglo) {
		if($arreglo['info']) {
			$msg = "Se ha enviado un correo a la cuenta: ".$arreglo['destino']." satisfactoriamente.";
		} else {
			$msg = "Error al enviar el correo electr\u00f3nico, por favor revisar el servidor de correo saliente.";
		}
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}
}
?>