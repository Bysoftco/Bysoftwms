<?php 
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'costeardo/model/costeardo.php';
require_once COMPONENTS_PATH . 'costeardo/views/vista.php';

class costeardo {
  var $vista;
  var $datos;

  function costeardo() {
    $this->vista = new CosteardoVista();
    $this->datos = new CosteardoModelo();
  }
	
  function listadoCosteardo($arreglo) {
    if(!isset($arreglo['pagina']) || empty($arreglo['pagina'])) {
      $arreglo['pagina'] = 1;
    }
    $arreglo['datos'] = $this->datos->listadoCosteardo($arreglo);
    $this->vista->listadoCosteardo($arreglo);
  }

	function filtroCosteardo($arreglo) {
		$this->vista->filtroCosteardo($arreglo);
	}
	
  function actualizaCosteardo($arreglo) {
		$arreglo['datosCostear'] = $this->datos->listadoDetallec($arreglo);

		$numregistro = count($arreglo['datosCostear']['datos']);
		if($numregistro == 0) {
		  $arreglo['totalingreso'] = $arreglo['totalgasto'] = 0;
		} else {
      $totalingreso = $totalgasto = 0;
			foreach($arreglo['datosCostear']['datos'] as $value) {
        $totalingreso += $value['ingreso']; //Acumula los Ingresos
        $totalgasto += $value['gasto']; //Acumula los Gastos
			}
      $arreglo['totalingreso'] = $totalingreso;
      $arreglo['totalgasto'] = $totalgasto;
		}
    $this->datos->actualizaCosteardo($arreglo);
    $this->listadoCosteardo($arreglo);
  }
  
  function findCliente($arreglo) {
    $arreglo['q'] = strtolower($_GET["q"]);
    $unaConsulta = $this->datos->findCliente($arreglo);
    $Existe = count($unaConsulta); 

    foreach($unaConsulta as $value) {
			$nombre = trim($value['razon_social']);
      $nit = $value['numero_documento'];
			echo "$nombre|$nit\n";
    }
    if($Existe == 0) echo "No hay Resultados|0\n";
  }
}
?>