<?php 

class FacturacionVista{
	
	function FacturacionVista(){
		$this->template = new HTML_Template_IT();
	}
	
	function listadoFacturas($arreglo){
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'Facturacion/views/tmpl/listadoFacturas.php' );
		$this->template->setVariable('COMODIN', '' );
		$this->template->setVariable('paginacion' , $arreglo['infoDatos']['paginacion'] );
		
		foreach($arreglo['infoDatos']['datos'] as $key => $value){
			$this->template->setCurrentBlock("ROW");
			if($codbagcolor==1){
			$this->template->setVariable('id_tr_estilo','tr_blanco');
			$codbagcolor=2;
			}else{
			$this->template->setVariable('id_tr_estilo','tr_gris_cla');	
			$codbagcolor=1;
			}
			foreach($value as $key2 => $value2){
				$this->template->setVariable($key2, $value2 );
			}
			$this->template->parseCurrentBlock("ROW");
		}
		$this->template->show();
	}
}

?>