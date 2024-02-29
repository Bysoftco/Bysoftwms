<?php
class LoginVista {
	var $template;
	
	function LoginVista() {
		$this->template = new HTML_Template_IT();
	}
	
	function logueo_aplicacion($arreglo) {
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'login/views/tmpl/login.php' );
		$this->template->setVariable('COMODIN', '' );
    $this->template->setVariable('select_sedes', $arreglo['select_sedes']);
    
		$this->template->show();
	}
}
?>