<?php
class SerialesVista{

	function SerialesVista() {
		$this->template = new HTML_Template_IT();
	}

	function listadoSeriales($arreglo) {
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'seriales/views/tmpl/listadoSeriales.php' );
		$this->template->setVariable('COMODIN', '');
		$this->template->setVariable('paginacion', $arreglo['datosSerial']['paginacion']);
		$this->template->setVariable('pagina', $arreglo['pagina']);
		$this->template->setVariable('verAlerta', 'none');
		
		$this->template->setVariable('orden', isset($arreglo['orden'])?$arreglo['orden']:"");
		$this->template->setVariable('id_orden', isset($arreglo['id_orden'])?$arreglo['id_orden']:"");
		$this->template->setVariable('campoBuscar', isset($arreglo['buscar'])?$arreglo['buscar']:"");

		$this->template->setVariable('numordenfull', $arreglo['numordenfull']);
		$this->template->setVariable('numorden', $arreglo['numorden']);
		$this->template->setVariable('codreferencia', $arreglo['codreferencia']);
		
		if(isset($arreglo['alerta_accion'])) {
			$this->template->setVariable('alerta_accion', $arreglo['alerta_accion']);
			$this->template->setVariable('verAlerta', 'block');
		}
		
		$codbagcolor = 1;
		$numregistro = 0;
		$n = $arreglo['pagina'] == 1 ? 0 : ($arreglo['pagina']*7)-7;
		foreach($arreglo['datosSerial']['datos'] as $value) {
			$numregistro = 1; $n++;
			$this->template->setCurrentBlock("ROW");
			if($codbagcolor == 1) {
				$this->template->setVariable('id_tr_estilo','tr_blanco');
				$codbagcolor = 2;
			} else {
				$this->template->setVariable('id_tr_estilo','tr_gris_cla');	
				$codbagcolor = 1;
			}
			$this->template->setVariable('id', $value['id']);
			$this->template->setvariable('n', $n);
			$this->template->setVariable('numorden', $value['numorden']);
			$this->template->setVariable('codreferencia', $value['codreferencia']);
			$this->template->setVariable('fecha', $value['fecha']);
			$this->template->setVariable('serial', $value['serial']);
			$this->template->parseCurrentBlock("ROW");
		}
		if($numregistro == 0) {
			$this->template->setVariable(mensaje, "No hay Seriales para mostrar");
	    $this->template->setVariable(estilo, "ui-state-error");
		}
		$this->template->show();
	}

	function agregarSeriales($arreglo) {
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'seriales/views/tmpl/editarSerial.php' );
		$this->template->setVariable('COMODIN', '');
		$this->template->setVariable('select_metodo', $arreglo['select_metodo']);
		$this->template->setVariable('id', isset($arreglo['id'])?$arreglo['id']:'0');
		$this->template->show();
	}
	
	function buscarTodos($arreglo) {
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'seriales/views/tmpl/buscarTodos.php' );
		$this->template->setVariable('COMODIN', '');
		$this->template->show();
	}
	
	function consultaSerial($arreglo) {
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'seriales/views/tmpl/consultaSerial.php');
		$this->template->setVariable('COMODIN', '');
		$this->template->show();
	}

	function verSerial($arreglo) {
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'seriales/views/tmpl/verSerial.php' );
		$this->template->setVariable('COMODIN', '');
		$this->template->setVariable('serial', $arreglo[serial]);
		$this->template->show();
	}
}
?>