{COMODIN}
<div class="div_barra">
	<div id="titulo_ruta">
		ADMINISTRACI&Oacute;N DE FACTURAS
	</div>
	<div id="contenedor_opciones" align="left">
		<table border="0">
			<tr>
				<td align="center">
					<div class="borde_circular">
						<a href="javascript:crearFactura()">
							<img src="img/acciones/agregar.png" title="Crear Factura" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
				<td align="center">
					<div class="borde_circular noSeleccion">
						<a href="javascript:verFactura()">
							<img src="img/acciones/ver.png" title="Ver Factura" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
				<td align="center">
					<div class="borde_circular noSeleccion">
						<a href="javascript:editarFactura()">
							<img src="img/acciones/edit.png" title="Editar Factura" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="alerta_accion" style="display: {verAlerta}">{alerta_accion}</div>
<div style="height: 15px"></div>

<table align="right" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="right" height="30px">
			<a href="javascript:buscarCoincidencias()"><img src="img/acciones/buscar.png" title="Buscar" width="17" height="20" border="0" /></a>
		</td>
		<td>
			<input type="text" name="campoBuscar" id="campoBuscar" value="{campoBuscar}" />
		</td>
	</tr>
</table><br /><br />

<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
	<tr>
		<th colspan="7">FACTURAS</th>
	</tr>
	<tr>
		<th class="cursor_ordenar" onclick="javascript:ordenar('fm.factura')" title="Ordenar por Consecutivo">
			Consecutivo
			<span id="spanfmfactura" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('fm.numero_oficial')" title="Ordenar por Factura">
			Factura
			<span id="spanfmnumero_oficial" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('cl.razon_social')" title="Ordenar por Cliente">
			Cliente
			<span id="spanclrazon_social" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('fm.orden')" title="Ordenar por Número DO">
			Número DO
			<span id="spanfmorden" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('fm.fecha_factura')" title="Ordenar por Fecha Factura">
			Fecha Factura
			<span id="spanfmfecha_factura" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('fm.total')" title="Ordenar por Valor">
			Valor
			<span id="spanfmtotal" class="noOrden"></span>
		</th>
		<th width="10px">Seleccionar</th>
	</tr>
	<!-- BEGIN ROW  -->
	<tr id="{id_tr_estilo}">
		<td>{factura}</td>
		<td>{numero_oficial}</td>
		<td>{razon_social}</td>
		<td>{orden}</td>
		<td>{fecha_factura}</td>
		<td>{total}</td>
		<td align="center"><input type="radio" name="seleccion" onclick="javascript:seleccionado('{no_documento}')" /></td>
	</tr>
	<!-- END ROW  -->
</table>
<br />{paginacion}

<input type="hidden" name="noSeleccionado" id="noSeleccionado" />

<form name="form_filtros" id="form_filtros" action="index_blank.php">
	<input type="hidden" name="pagina" id="pagina" value="{pagina}" />
	<input type="hidden" name="buscar" id="buscar" value="{buscar}" />
	<input type="hidden" name="orden" id="orden" value="{orden}" />
	<input type="hidden" name="id_orden" id="id_orden" value="{id_orden}" />
</form>

<script>

Nifty("div.borde_circular","transparent");
Nifty("div.div_barra","top transparent");
$('.noSeleccion').css('display', 'none');

ordenadoActual();

function paginar(pagina){
	$('#pagina').attr('value', pagina);
    filtrar();
}

function seleccionado(documento){
	$('#noSeleccionado').attr('value', documento);
	$('.noSeleccion').css('display', 'block');
}

function ordenadoActual(){
	$('.noOrden').html('');
	var orden_actual = $('#orden').attr('value');
	var id_actual = $('#id_orden').attr('value');
	var cSpan = orden_actual.replace('.','');
	if(id_actual=='ASC'){
		$('#span'+cSpan).html('<span style="font-size: 15px">&uarr;</span>');
	}
	else if(id_actual=='DESC'){
		$('#span'+cSpan).html('<span style="font-size: 15px">&darr;</span>');
	}
	else{
		$('#span'+cSpan).html('');
	}
}

</script>