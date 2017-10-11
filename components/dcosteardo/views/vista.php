<?php
class DCosteardoVista{

	function DCosteardoVista() {
		$this->template = new HTML_Template_IT();
	}

	function listadoDetallec($arreglo) {
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'dcosteardo/views/tmpl/listadoDetallec.php' );
		$this->template->setVariable('COMODIN', '');
		$this->template->setVariable('paginacion', $arreglo['datosCostear']['paginacion']);
		$this->template->setVariable('pagina', $arreglo['pagina']);
		$this->template->setVariable('verAlerta', 'none');
		
		$this->template->setVariable('orden', isset($arreglo['orden'])?$arreglo['orden']:"");
		$this->template->setVariable('id_orden', isset($arreglo['id_orden'])?$arreglo['id_orden']:"");
    
    $this->template->setVariable('do_asignadoc', $arreglo['do_asignado']);
    $this->template->setVariable('doc_ttec', $arreglo['doc_tte']);
    
		if(isset($arreglo['alerta_accion'])) {
			$this->template->setVariable('alerta_accion', $arreglo['alerta_accion']);
			$this->template->setVariable('verAlerta', 'block');
		}
		
		$codbagcolor = 1;
    $numregistro = count($arreglo['datosCostear']['datos']);
		if($numregistro == 0) {
		  $this->template->setVariable('nr',0); // Inicializa consecutivo de registro
			$this->template->setVariable(mensaje, "No hay Detalle de Costos para mostrar");
	    $this->template->setVariable(estilo, "ui-state-error");
      $totalingreso = $totalgasto = 0;
      $this->template->setVariable('muestraImprimir', 'none');
		} else {
      $this->template->setVariable('muestraImprimir', 'block');
			//$n = $arreglo['pagina'] == 1 ? 0 : ($arreglo['pagina']*10)-10;
      $totalingreso = $totalgasto = 0;
			foreach($arreglo['datosCostear']['datos'] as $value) {
				$this->template->setCurrentBlock("ROW");
				if($codbagcolor == 1) {
					$this->template->setVariable('id_tr_estilo','tr_blanco');
					$codbagcolor = 2;
				} else {
					$this->template->setVariable('id_tr_estilo','tr_gris_cla');	
					$codbagcolor = 1;
				}
        $this->template->setVariable('nr', $value['numdetalle']); // Inicializa consecutivo de registro
        $this->template->setVariable('numdetallec', $value['numdetalle']);
        $this->template->setVariable('codservicio', $value['codservicio']);
				$this->template->setVariable('nomservicio', $value['nomservicio']);
				$this->template->setVariable('fecha', $value['fecha']);
				$this->template->setVariable('ingreso', number_format($value['ingreso'],2));
				$this->template->setVariable('gasto', number_format($value['gasto'],2));
        $totalingreso += $value['ingreso']; //Acumula los Ingresos
        $totalgasto += $value['gasto']; //Acumula los Gastos
				$this->template->parseCurrentBlock("ROW");
			}
      $utilidad = $totalingreso - $totalgasto;
		}

    $this->template->setVariable('totalingreso', number_format($totalingreso,2));
    $this->template->setVariable('totalgasto', number_format($totalgasto,2));
    $this->template->setVariable('utilidad', number_format($utilidad,2));
    
		$this->template->show();
	}

	function agregarDetallec($arreglo) {
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'dcosteardo/views/tmpl/editarDetallec.php' );
		$this->template->setVariable('COMODIN', '');
    
    $this->template->setVariable('numdetalle', isset($arreglo['datosDetallec']['numdetalle'])?$arreglo['datosDetallec']['numdetalle']:$arreglo[numdetalle]);
    $this->template->setVariable('do_asignado', isset($arreglo['datosDetallec']['do_asignado'])?$arreglo['datosDetallec']['do_asignado']:$arreglo[do_asignado]);
    $this->template->setVariable('codservicio', isset($arreglo['datosDetallec']['codservicio'])?$arreglo['datosDetallec']['codservicio']:0);
    $this->template->setVariable('nomservicio', isset($arreglo['datosDetallec']['codservicio'])?$arreglo['datosDetallec']['nomservicio']:'');
    $this->template->setVariable('fecha', isset($arreglo['datosDetallec']['fecha'])?$arreglo['datosDetallec']['fecha']:date('Y-m-d'));
    $this->template->setVariable('naturaleza', isset($arreglo['datosDetallec']['naturaleza'])?$arreglo['datosDetallec']['naturaleza']:'');
    $this->template->setVariable('ingreso', isset($arreglo['datosDetallec']['ingreso'])?$arreglo['datosDetallec']['ingreso']:0);
    if($arreglo['datosDetallec']['ingreso'] != 0) $this->template->setVariable('valordetalle', $arreglo['datosDetallec']['ingreso']); 
    $this->template->setVariable('gasto', isset($arreglo['datosDetallec']['gasto'])?$arreglo['datosDetallec']['gasto']:0);
    if($arreglo['datosDetallec']['gasto'] != 0) $this->template->setVariable('valordetalle', $arreglo['datosDetallec']['gasto']);
    if(isset($arreglo['datosDetallec']['numdetalle'])) $this->template->setVariable('actualiza', 1);
    else $this->template->setVariable('actualiza', 0);

		$this->template->show();
	}
}
?>