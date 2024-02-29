<?php
class DCosteardoVista {

	function DCosteardoVista() {
		$this->template = new HTML_Template_IT(COMPONENTS_PATH);
	}

	function listadoDetallec($arreglo) {
		$this->template->loadTemplateFile('dcosteardo/views/tmpl/listadoDetallec.php');
		$this->template->setVariable('COMODIN','');
		$this->template->setVariable('paginacion',$arreglo['datosCostear']['paginacion']);
		$this->template->setVariable('pagina',$arreglo['pagina']);
		$this->template->setVariable('verAlerta','none');
		
		$this->template->setVariable('orden',isset($arreglo['orden'])?$arreglo['orden']:0);
		$this->template->setVariable('id_orden',isset($arreglo['id_orden'])?$arreglo['id_orden']:0);
    
    $this->template->setVariable('do_asignadoc',$arreglo['do_asignado']);
    $this->template->setVariable('doc_ttec',$arreglo['doc_tte']);
    
		if(isset($arreglo['alerta_accion'])) {
			$this->template->setVariable('alerta_accion',$arreglo['alerta_accion']);
			$this->template->setVariable('verAlerta','block');
		}
		
		$codbagcolor = 1;
    $numregistro = count($arreglo['datosCostear']['datos']);
    $this->template->setVariable('nr',$numregistro); // Inicializa consecutivo de registro
    $totaling = $totalgas = 0;
		if($numregistro == 0) {
			$this->template->setVariable('mensaje', "No hay Detalle de Costos para mostrar");
	    $this->template->setVariable('estilo', "ui-state-error");
      $this->template->setVariable('muestraImprimir', 'none');
		} else {
      $this->template->setVariable('muestraImprimir', 'block');
      $nregistro = 1;
			foreach($arreglo['datosCostear']['datos'] as $value) {
				$this->template->setCurrentBlock("ROW");
				if($codbagcolor == 1) {
					$this->template->setVariable('id_tr_estilo','tr_blanco');
					$codbagcolor = 2;
				} else {
					$this->template->setVariable('id_tr_estilo','tr_gris_cla');	
					$codbagcolor = 1;
				}
        $this->template->setVariable('numdetallec',$nregistro);
        $this->template->setVariable('codservicio',$value['codservicio']);
				$this->template->setVariable('nomservicio', $value['nomservicio']);
				$this->template->setVariable('fecha',$value['fecha']);
				$this->template->setVariable('ingreso',number_format($value['ingreso'],2));
				$this->template->setVariable('gasto',number_format($value['gasto'],2));
        $totaling += $value['ingreso']; //Acumula los Ingresos
        $totalgas += $value['gasto']; //Acumula los Gastos
        $nregistro++;
				$this->template->parseCurrentBlock("ROW");
			}
		}
    $this->template->setCurrentBlock("TOTALES");
   	$utilidad = $totaling - $totalgas;
  	$this->template->setVariable('totalingreso',number_format($totaling,2));
  	$this->template->setVariable('totalgasto',number_format($totalgas,2));
  	$this->template->setVariable('utilidad',number_format($utilidad,2));
    $this->template->parseCurrentBlock("TOTALES");
    
		$this->template->show();
	}

	function agregarDetallec($arreglo) {
		$this->template->loadTemplateFile('dcosteardo/views/tmpl/editarDetallec.php');
		$this->template->setVariable('COMODIN','');
    
    $this->template->setVariable('numdetalle',isset($arreglo['datosDetallec']['numdetalle'])?$arreglo['datosDetallec']['numdetalle']:$arreglo['numdetalle']);
    $this->template->setVariable('do_asignado',isset($arreglo['datosDetallec']['do_asignado'])?$arreglo['datosDetallec']['do_asignado']:$arreglo['do_asignado']);
    $this->template->setVariable('doc_tte',isset($arreglo['datosDetallec']['doc_tte'])?$arreglo['datosDetallec']['doc_tte']:$arreglo['doc_tte']);
    $this->template->setVariable('codservicio',isset($arreglo['datosDetallec']['codservicio'])?$arreglo['datosDetallec']['codservicio']:0);
    $this->template->setVariable('nomservicio',isset($arreglo['datosDetallec']['codservicio'])?$arreglo['datosDetallec']['nomservicio']:'');
    $this->template->setVariable('fecha',isset($arreglo['datosDetallec']['fecha'])?$arreglo['datosDetallec']['fecha']:date('Y-m-d'));
    $this->template->setVariable('naturaleza',isset($arreglo['datosDetallec']['naturaleza'])?$arreglo['datosDetallec']['naturaleza']:'');
    if($arreglo['datosDetallec']['naturaleza']=='D') {
    	$this->template->setVariable('valordetalle',$arreglo['datosDetallec']['ingreso']);
    } else {
    	$this->template->setVariable('valordetalle', $arreglo['datosDetallec']['gasto']);
    }
    $this->template->setVariable('ingreso',isset($arreglo['datosDetallec']['ingreso'])?$arreglo['datosDetallec']['ingreso']:0);
    $this->template->setVariable('gasto',isset($arreglo['datosDetallec']['gasto'])?$arreglo['datosDetallec']['gasto']:0);
    if(isset($arreglo['datosDetallec']['numdetalle'])) {
    	$this->template->setVariable('actualiza',1);
    } else {
    	$this->template->setVariable('actualiza',0);
    } 

		$this->template->show();
	}
}
?>