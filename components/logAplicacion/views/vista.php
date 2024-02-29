<?php
class LogAplicacionVista{
	
	var $template;
	
	function LogAplicacionVista(){
		$this->template = new HTML_Template_IT();
	}
	
	function template_inicial($arreglo){
		$plantilla = new HTML_Template_IT();
		$plantilla->loadTemplateFile( COMPONENTS_PATH . 'logAplicacion/views/tmpl/templateArbol.php' );
		$plantilla->setVariable('COMODIN', '' );
		ob_start();
		$this->listarLog($arreglo);
		$contenido = ob_get_contents();
		ob_end_clean();
		$plantilla->setVariable('contenido' , $contenido);
		$plantilla->setVariable('menu'      , $this->filtros($arreglo['filtros']));
		$plantilla->show();
	}
	
	function template_log_menu($arreglo){
		$plantilla = new HTML_Template_IT();
		$plantilla->loadTemplateFile( COMPONENTS_PATH . 'logAplicacion/views/tmpl/templateArbol.php' );
		$plantilla->setVariable('COMODIN', '' );
		ob_start();
		$this->listarLogMenu($arreglo);
		$contenido = ob_get_contents();
		ob_end_clean();
		$plantilla->setVariable('contenido' , $contenido);
		$plantilla->setVariable('menu'      , $this->filtrosMenu($arreglo['filtros']));
		$plantilla->show();
	}
	
	function listarLog($arreglo){
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'logAplicacion/views/tmpl/listadoLog.php' );
		$this->template->setVariable('COMODIN', '' );
		//$this->template->setVariable('paginacion', $arreglo['datos']['paginacion']);
		$this->template->setVariable('num_paginas', $arreglo['datos']['totalPag'] );
		
		if(isset($arreglo['page']) && !empty($arreglo['page'])){
			$this->template->setVariable('page_temporal'      ,$arreglo['page']);
		}
		if(isset($arreglo['filtroUsuario']) && !empty($arreglo['filtroUsuario'])){
			$this->template->setVariable('filtroUsuario'      ,$arreglo['filtroUsuario']);
		}
		if(isset($arreglo['filtroModulo']) && !empty($arreglo['filtroModulo'])){
			$this->template->setVariable('filtroModulo'      ,$arreglo['filtroModulo']);
		}
		if(isset($arreglo['filtroFuncion']) && !empty($arreglo['filtroFuncion'])){
			$this->template->setVariable('filtroFuncion'      ,$arreglo['filtroFuncion']);
		}
		if(isset($arreglo['filtroDesde']) && !empty($arreglo['filtroDesde'])){
			$this->template->setVariable('filtroDesde'      ,$arreglo['filtroDesde']);
		}
		if(isset($arreglo['filtroHasta']) && !empty($arreglo['filtroHasta'])){
			$this->template->setVariable('filtroHasta'      ,$arreglo['filtroHasta']);
		}
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])){
			$this->template->setVariable('orden'      ,$arreglo['orden']);
			$this->template->setVariable('id_page'      ,$arreglo['id_page']);
		}
		$codbagcolor=1;
		foreach($arreglo['datos']['datos'] as $value){
			$this->template->setCurrentBlock("ROW");
			if($codbagcolor==1){
			$this->template->setVariable('id_tr_estilo','tr_bla');
			$codbagcolor=2;
			}else{
			$this->template->setVariable('id_tr_estilo','tr_ros_cla');	
			$codbagcolor=1;
			}
			$this->template->setVariable('id',           	  $value['siglog_id'] );
			$this->template->setVariable('usuario',           $value['usuario'] );
			$this->template->setVariable('nombre_usuario',    $value['nombre_usuario'] );
			$this->template->setVariable('apellido_usuario',  $value['apellido_usuario'] );
			$this->template->setVariable('fecha',             $value['siglog_fecha_edicion'] );
			$this->template->setVariable('modulo',            $value['siglog_modulo'] );
			$this->template->setVariable('funcion',           $value['siglog_funcion'] );
			$this->template->parseCurrentBlock("ROW");
		}
		$this->template->show();
	}
	
	function listarLogMenu($arreglo){
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'logAplicacion/views/tmpl/listadoLogMenu.php' );
		$this->template->setVariable('COMODIN', '' );
		//$this->template->setVariable('paginacion', $arreglo['datos']['paginacion']);
		$this->template->setVariable('num_paginas', $arreglo['datos']['totalPag'] );
		
		if(isset($arreglo['page']) && !empty($arreglo['page'])){
			$this->template->setVariable('page_temporal'      ,$arreglo['page']);
		}
		if(isset($arreglo['filtroUsuario']) && !empty($arreglo['filtroUsuario'])){
			$this->template->setVariable('filtroUsuario'      ,$arreglo['filtroUsuario']);
		}
		if(isset($arreglo['filtroItem']) && !empty($arreglo['filtroItem'])){
			$this->template->setVariable('filtroItem'      ,$arreglo['filtroItem']);
		}
		if(isset($arreglo['filtroDesde']) && !empty($arreglo['filtroDesde'])){
			$this->template->setVariable('filtroDesde'      ,$arreglo['filtroDesde']);
		}
		if(isset($arreglo['filtroHasta']) && !empty($arreglo['filtroHasta'])){
			$this->template->setVariable('filtroHasta'      ,$arreglo['filtroHasta']);
		}
		if(isset($arreglo['orden']) && !empty($arreglo['orden'])){
			$this->template->setVariable('orden'      ,$arreglo['orden']);
			$this->template->setVariable('id_page'      ,$arreglo['id_page']);
		}
		
		$codbagcolor=1;
		foreach($arreglo['datos']['datos'] as $value){
			$this->template->setCurrentBlock("ROW");
			if($codbagcolor==1){
			$this->template->setVariable('id_tr_estilo','tr_bla');
			$codbagcolor=2;
			}else{
			$this->template->setVariable('id_tr_estilo','tr_ros_cla');	
			$codbagcolor=1;
			}
			$this->template->setVariable('id',           	  $value['siglog_id'] );
			$this->template->setVariable('usuario',           $value['usuario'] );
			$this->template->setVariable('nombre_usuario',    $value['nombre_usuario'] );
			$this->template->setVariable('apellido_usuario',  $value['apellido_usuario'] );
			$this->template->setVariable('fecha',             $value['siglog_fecha_accion'] );
			$this->template->setVariable('item',              $value['siglog_accion'] );
			$this->template->parseCurrentBlock("ROW");
		}
		$this->template->show();
	}
	
	function filtros($filtros){
		$filtro='<center>
					<table>
						<tr height="40px">
							<td colspan="2"><select class="selectFiltro" onChange="filtrarSelect(\'filtroUsuario\', this.value)">'.$filtros['select_usuarios'].'</select></td>
						</tr>
						<tr height="40px">
							<td colspan="2"><select class="selectFiltro" onChange="filtrarSelect(\'filtroModulo\', this.value)">'.$filtros['select_modulos'].'</select></td>
						</tr>
						<tr height="40px">
							<td colspan="2"><select class="selectFiltro" onChange="filtrarSelect(\'filtroFuncion\', this.value)">'.$filtros['select_funciones'].'</select></td>
						</tr>
						<tr>
							<td>
								<span style="color:#ffffff; font-size: 16px;">
									Desde : 
								</span><br /><input type="text" id="datepickerDesde" size="12" style="height:20px;">
							</td>
							<td>
								<span style="color:#ffffff; font-size: 16px;">
									Hasta : 
								</span><br /><input type="text" id="datepickerHasta" size="12" style="height:20px;">
							</td>
						</tr>
						<tr height="40px">
							<td colspan="2"><br /><input type="button" value="LIMPIAR FILTRO" class="button small yellow2" onclick="limpiarFiltro()" /></td>
						</tr>
					</table>
				</center>';
		return $filtro;
	}
	
	function filtrosMenu($filtros){
		$filtro='<center>
					<table>
						<tr height="40px">
							<td colspan="2"><select class="selectFiltro" onChange="filtrarSelect(\'filtroUsuario\', this.value)">'.$filtros['select_usuarios'].'</select></td>
						</tr>
						<tr height="40px">
							<td colspan="2"><select class="selectFiltro" onChange="filtrarSelect(\'filtroItem\', this.value)">'.$filtros['select_items'].'</select></td>
						</tr>
						<tr>
							<td>
								<span style="color:#ffffff; font-size: 16px;">
									Desde : 
								</span><br /><input type="text" id="datepickerDesde" size="12" style="height:20px;">
							</td>
							<td>
								<span style="color:#ffffff; font-size: 16px;">
									Hasta : 
								</span><br /><input type="text" id="datepickerHasta" size="12" style="height:20px;">
							</td>
						</tr>
						<tr>
							<td  colspan="2"><br /><input type="button" value="LIMPIAR FILTRO" class="button small yellow2" onclick="limpiarFiltro()" /></td>
						</tr>
					</table>
				</center>';
		return $filtro;
	}
	
	function calendario($arreglo){
		$this->template->loadTemplateFile( COMPONENTS_PATH . 'logAplicacion/views/tmpl/calendario.php' );
		$this->template->setVariable('COMODIN', '' );
		$this->template->show();
	}
}
?>