<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'logAplicacion/model/logAplicacion.php';
require_once COMPONENTS_PATH . 'logAplicacion/views/vista.php';

class logAplicacion{
	
	var $vista;
	var $datos;
	
	function logAplicacion(){
		$this->vista = new LogAplicacionVista();
		$this->datos = new LogAplicacionModelo();
	}
	
	function listadoSimple($arreglo){
		if(!isset($arreglo['page']) || empty($arreglo['page'])){
		    $arreglo['page'] = 1;
		}
		$arreglo['datos'] = $this->datos->listarLog($arreglo);
		$this->vista->listarLog($arreglo);
	}
	
	function listadoSimpleMenu($arreglo){
		if(!isset($arreglo['page']) || empty($arreglo['page'])){
		    $arreglo['page'] = 1;
		}
		$arreglo['datos'] = $this->datos->listarLogMenu($arreglo);
		$this->vista->listarLogMenu($arreglo);
	}
	
	function listarLog($arreglo){
		if(!isset($arreglo['page']) || empty($arreglo['page'])){
		    $arreglo['page'] = 1;
		}
		$arreglo['datos'] = $this->datos->listarLog($arreglo);
		$arreglo['filtros'] = $this->filtros($arreglo);
		$this->vista->template_inicial($arreglo);
	}
	
	function filtros($arreglo){
		$lista_usuarios = $this->datos->build_list('sig_usuarios','id','sigusu_id', 'WHERE eliminado = 0 ORDER BY sigusu_id ');
    	$filtros['select_usuarios'] = $this->datos->armSelect($lista_usuarios ,'Seleccione Usuario...', isset($arreglo['filtroUsuario'])?$arreglo['filtroUsuario']:'');

    	$lista_modulos = $this->datos->build_list('sig_log','siglog_modulo','siglog_modulo', ' GROUP BY siglog_modulo ORDER BY siglog_modulo ');
    	$filtros['select_modulos'] = $this->datos->armSelect($lista_modulos ,'Seleccione M�dulo...', isset($arreglo['filtroModulo'])?$arreglo['filtroModulo']:'');
    	
    	$lista_funciones = $this->datos->build_list('sig_log','siglog_funcion','siglog_funcion', ' GROUP BY siglog_funcion ORDER BY siglog_funcion ');
    	$filtros['select_funciones'] = $this->datos->armSelect($lista_funciones ,'Seleccione Funci�n...', isset($arreglo['filtroFuncion'])?$arreglo['filtroFuncion']:'');
    	
    	return $filtros;
	}
	
	function filtrosMenu($arreglo){
		$lista_usuarios = $this->datos->build_list('sig_usuarios','id','sigusu_id', 'WHERE eliminado = 0 ORDER BY sigusu_id ');
    	$filtros['select_usuarios'] = $this->datos->armSelect($lista_usuarios ,'Seleccione Usuario...', isset($arreglo['filtroUsuario'])?$arreglo['filtroUsuario']:'');

    	$lista_items = $this->datos->build_list('sig_log_menu','siglog_accion','siglog_accion', ' GROUP BY siglog_accion ORDER BY siglog_accion ');
    	$filtros['select_items'] = $this->datos->armSelect($lista_items ,'Seleccione Item...', isset($arreglo['filtroItem'])?$arreglo['filtroItem']:'');
    	
    	return $filtros;
	}
	
	function verSQL($arreglo){
		$sql=$this->datos->seleccionarSQL($arreglo);
		echo base64_decode($sql->siglog_sql);
	}
	
	function logMenu($arreglo){
		$this->datos->logMenu($arreglo);
	}
	
	function listarLogMenu($arreglo){
		if(!isset($arreglo['page']) || empty($arreglo['page'])){
		    $arreglo['page'] = 1;
		}
		$arreglo['datos'] = $this->datos->listarLogMenu($arreglo);
		$arreglo['filtros'] = $this->filtrosMenu($arreglo);
		$this->vista->template_log_menu($arreglo);
	}
	
	function generarExcel($arreglo){
		header("Content-type: application/force-download");
		header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera 
		header("Content-type: application/x-msexcel");                    // This should work for the rest
		header("Content-disposition: attachment; filename=LogAplicativo.xls; charset=iso-8859-1");
		header("Content-Transfer-Encoding: binary");
		
		$datosExcel=$this->datos->consultaGenerar($arreglo);
		$tabla='<table border="1">';
		$tabla.='<tr style="font-weight: bold;">';
		foreach($datosExcel[0] as $key => $value){
			$tabla.='<td>'.$key.'</td>';
		}
		$tabla.='</tr>';
		foreach($datosExcel as $key => $value){
			$tabla.='<tr>';
			foreach($value as $key2 => $value2){
				$tabla.='<td>'.$value2.'</td>';
			}
			$tabla.='</tr>';
		}
		$tabla.='</table>';
		echo $tabla;
	}
	
	function generarExcelLog($arreglo){
		header("Content-type: application/force-download");
		header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera 
		header("Content-type: application/x-msexcel");                    // This should work for the rest
		header("Content-disposition: attachment; filename=LogAplicativo.xls; charset=iso-8859-1");
		header("Content-Transfer-Encoding: binary");
		
		$datosExcel=$this->datos->consultaLog($arreglo);
		$tabla='<table border="1">';
		$tabla.='<tr style="font-weight: bold;">';
		foreach($datosExcel[0] as $key => $value){
			$tabla.='<td>'.$key.'</td>';
		}
		$tabla.='</tr>';
		foreach($datosExcel as $key => $value){
			$tabla.='<tr>';
			foreach($value as $key2 => $value2){
				if($key2=='siglog_sql'){$value2=base64_decode($value2);}
				$tabla.='<td>'.$value2.'</td>';
			}
			$tabla.='</tr>';
		}
		$tabla.='</table>';
		echo $tabla;
	}
}
?>
