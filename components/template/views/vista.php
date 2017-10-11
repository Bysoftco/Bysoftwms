<?php
class TemplateVista {
	var $template;
	
	function TemplateVista() {
		$this->template = new HTML_Template_IT();
	}
	
	function armar_header() {
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'template/views/tmpl/header.php' );
		$this->template->setVariable('COMODIN', '' );
		
		if(isset( $_SESSION['datos_logueo']['usuario'] )) {
			$bienvenida='Bienvenid@ ';
			$this->template->setVariable('NOMBRE_USUARIO', $bienvenida.$_SESSION['datos_logueo']['nombre_usuario']);
      $this->template->setVariable('NOMBRE_SEDE', $_SESSION['nombre_sede']);
			$this->template->setVariable('USUARIO', $_SESSION['datos_logueo']['usuario']);
			$this->template->setVariable('MENU', base64_decode($_SESSION['menu']));
		}
		$this->template->show();
	}
	
	function armar_footer(){
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'template/views/tmpl/footer.php' );
		$this->template->setVariable('COMODIN', '' );
		$this->template->show();
	}
}
?>