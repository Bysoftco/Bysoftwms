{COMODIN}
<div class="ruta">LOG DE APLICATIVO</div>
<table align="center" border='0' width="90%" cellpadding="0" cellspacing="0">
	<tr>
		<td><input type="button" class="button small yellow2" name="exportar_excel" 
		     id="exportar_excel" value="Exportar" onclick="generarExcel()" />
		</td>
	</tr>
</table>
<table align="center" border='1' width="90%" cellpadding="0"
	cellspacing="0" id="tabla_rda">
	<tr class="titulo encabezadoTabla">
		<td colspan="7" height="30px" align="center">ACTIVIDADES SOBRE EL
			APLICATIVO</td>
	</tr>
	<tr align="center" class="titulo encabezadoTabla">
		<td class="espacio"
			onclick="javascript:ordenar('su.sigusu_id', 'spanUsuario')">Usuario <span
			id="spanUsuario" class="noOrden"></span></td>
		<td class="espacio"
		    onclick="javascript:ordenar('su.sigusu_nombres', 'spanNombreUsuario')">Nombre Usuario <span
		    id="spanNombreUsuario" class="noOrden"></span></td>
		<td class="espacio"
			onclick="javascript:ordenar('su.sigusu_apellidos', 'spanApellidoUsuario')">Apellido Usuario <span
		    id="spanApellidoUsuario" class="noOrden"></span></td>
		<td class="espacio"
			onclick="javascript:ordenar('sl.siglog_fecha_edicion', 'spanFecha')">Fecha Acción <span
		    id="spanFecha" class="noOrden"></span></td>
		<td class="espacio"
			onclick="javascript:ordenar('sl.siglog_modulo', 'spanModulo')">Módulo Afectado <span
		    id="spanModulo" class="noOrden"></span></td>
		<td class="espacio"
			onclick="javascript:ordenar('sl.siglog_funcion', 'spanFuncion')">Función <span
		    id="spanFuncion" class="noOrden"></span></td>
		<td class="espacio">Acciones</td>
	</tr>
	<!-- BEGIN ROW  -->
	<tr id="{id_tr_estilo}">
		<td class="espacio">{usuario}</td>
		<td class="espacio">{nombre_usuario}</td>
		<td class="espacio">{apellido_usuario}</td>
		<td class="espacio">{fecha}</td>
		<td class="espacio">{modulo}</td>
		<td class="espacio">{funcion}</td>
		<td class="espacio" align="center"><span class="popups"><a href="" onclick="javascript:verSQL({id})"><img
				src="img/acciones/page_edit.png" title="Ver SQL" width="17"
				height="17" border="0" /></a></span></td>
	</tr>
	<!-- END ROW  -->
</table><br />
<center>
	<table border="0">
		<tr>
			<td height="80px" style="display: none">
				{paginacion}
			</td>
		</tr>
		<tr>
			<td class="tam_paginacion">
				Página: <input type="text" name="pag_actual" id="pag_actual" size="5" value="{page_temporal}" /> de <b>{num_paginas} </b>
			</td>
			<td>
				<span class="regresar"><a href="javascript:paginar()"> - IR - </a></span>
			</td>
		</tr>
	</table>
</center>

<form action="index_blank.php" method="post" id="FormularioExportacion">
	<input type="hidden" id="component" name="component" value="logAplicacion" /> 
	<input type="hidden" id="method" name="method" value="generarExcelLog" />
</form>

<form name="form_filtros" id="form_filtros" action="index_blank.php">
	<input type="hidden" name="page" id="page" value="{page}" />
	<input type="hidden" name="page_temporal" id="page_temporal" value="{page_temporal}" />
	<input type="hidden" name="id_page" id="id_page" value="{id_page}" />
	<input type="hidden" name="orden" id="orden" value="{orden}" />
	
	<input type="hidden" name="filtroUsuario" id="filtroUsuario" value="{filtroUsuario}" />
	<input type="hidden" name="filtroModulo" id="filtroModulo" value="{filtroModulo}" />
	<input type="hidden" name="filtroFuncion" id="filtroFuncion" value="{filtroFuncion}" />
	<input type="hidden" name="filtroDesde" id="filtroDesde" value="{filtroDesde}" />
	<input type="hidden" name="filtroHasta" id="filtroHasta" value="{filtroHasta}" />
</form>

<script>
$(document).ready(function(){
	$('.popups a').wowwindow({
        draggable: true,
        width:450,
        height:400,
        overlay: {clickToClose: false,
        	      background: '#000000'},
		onclose: function() {$('.formError').remove();}
    }); 
});

$(function() {
	$( "#datepickerDesde" ).datepicker({
		monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		dayNamesMin: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
		dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		dateFormat: 'yy-mm-dd',
		altFormat: "yy-mm-dd",
		onSelect: function(textoFecha, objDatepicker){
			var fecha_mostrar=$('#datepickerDesde').attr('value');
			$('#filtroDesde').attr('value', fecha_mostrar);
			filtrar();
		}
	});

	
	$( "#datepickerHasta" ).datepicker({
		monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		dayNamesMin: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
		dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		dateFormat: 'yy-mm-dd',
		altFormat: "yy-mm-dd",
		onSelect: function(textoFecha, objDatepicker){
			var fecha_mostrar=$('#datepickerHasta').attr('value');
			$('#filtroHasta').attr('value', fecha_mostrar);
			filtrar();
		}
	});
});


$('.noOrden').html('<img src="img/buttons/menu_abajo.gif" border="0" width="12px" height="12px" />');
var ordenadoPor=$('#id_page').attr('value');
$('#'+ordenadoPor).html('<img src="img/buttons/img_move_down.gif" border="0" width="12px" height="12px" />');

/*function paginar(pagina){
    $('#page').attr('value', pagina);
    filtrar();
}*/

function paginar(){
	var nueva_pagina = $('#pag_actual').attr('value');
    $('#page').attr('value', nueva_pagina);
    filtrar();
}

function ordenar(orden, id_page){
	$('#orden').attr('value', orden);
	$('#id_page').attr('value', id_page);
	filtrar();
}

function filtrarSelect(idFiltrar, valor){
	$('#'+idFiltrar).attr('value', valor);
	filtrar();
}

function verSQL(id){
	$.ajax({
		url:'index_blank.php?component=logAplicacion&method=verSQL&id='+id,
		type: "POST",
		success: function(msm){
			$('#wowwindow-inner').html(msm);
        }
	});
}

function limpiarFiltro(){
    $('.selectFiltro').attr('value','');
    $('#filtroUsuario').attr('value','');
	$('#filtroModulo').attr('value','');
	$('#filtroFuncion').attr('value','');
	$('#datepickerDesde').attr('value','');
	$('#datepickerHasta').attr('value','');
	$('#filtroDesde').attr('value','');
	$('#filtroHasta').attr('value','');
	filtrar();
}

function filtrar(){
	$.ajax({
		  url: 'index_blank.php?component=logAplicacion&method=listadoSimple',
		  data: $('#form_filtros').serialize(),
		  success: function(msm){
		   $('#contenido_arbol').html(msm);
		  }
	});
}

function generarExcel(){
	$("#FormularioExportacion").submit();
}
</script>
