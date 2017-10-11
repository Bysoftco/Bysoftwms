{COMODIN}
<div class="div_barra">
	<div id="titulo_ruta">
    <table align="right" cellpadding="5" cellspacing="0" border="0">
      <tr>
        <td>
		      ADMINISTRACI&Oacute;N DE PERFILES
        </td>
      </tr>
    </table>
	</div>
	<div id="contenedor_opciones" align="left">
		<table border="0">
			<tr>
				<td align="center">
					<div class="popupsAgregar borde_circular">
						<a href="">
							<img src="img/acciones/agregar.png" title="Agregar" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
				<td align="center">
					<div class="popupsVer borde_circular noSeleccion">
						<a href="">
							<img src="img/acciones/ver.png" title="Ver" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
				<td align="center">
					<div class="popupsEditar borde_circular noSeleccion">
						<a href="">
							<img src="img/acciones/edit.png" title="Editar" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
				<td align="center">
					<div class="borde_circular noSeleccion" onclick="javascript:activarDesactivar()">
						<a href="javascript:void(0)">
							<img src="img/acciones/bloquear.png" title="Activar / Desactivar" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
				<td align="center">
					<div class="borde_circular noSeleccion" onclick="javascript:eliminarPerfil()">
						<a href="javascript:void(0)">
							<img src="img/acciones/eliminar.png" title="Eliminar" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div style="padding-top: 43px;"></div>
<div id="alerta_accion" style="display: {verAlerta}">{alerta_accion}</div>
<div style="height: 5px"></div>

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
		<th colspan="5">PERFILES</th>
	</tr>
	<tr>
		<th class="cursor_ordenar" onclick="javascript:ordenar('nombre')" title="Ordenar por Nombre">
			Nombre
			<span id="spannombre" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('descripcion')" title="Ordenar por Descripcion">
			Descripci&oacute;n
			<span id="spandescripcion" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('fecha_creacion')" title="Ordenar por Fecha Creaci&oacute;n">
			Fecha Creaci&oacute;n
			<span id="spanfecha_creacion" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('estado')" title="Ordenar por Estado">
			Estado
			<span id="spanestado" class="noOrden"></span>
		</th>
		<th width="10px">Seleccionar</th>
	</tr>
	<!-- BEGIN ROW  -->
	<tr id="{id_tr_estilo}">
		<td>{nombre}</td>
		<td>{descripcion}</td>
		<td align="center">{fecha_creacion}</td>
		<td align="center">{estado}</td>
		<td align="center"><input type="radio" name="seleccion" onclick="javascript:seleccionado('{nombre}', '{id}', '{idestado}')" /></td>
	</tr>
	<!-- END ROW  -->
</table><br />
{paginacion}

<input type="hidden" name="nombreSeleccionado" id="nombreSeleccionado" />
<input type="hidden" name="idSeleccionado" id="idSeleccionado" />
<input type="hidden" name="idEstado" id="idEstado" />

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

function seleccionado(nombre, id, idestado){
	$('#nombreSeleccionado').attr('value', nombre);
	$('#idSeleccionado').attr('value', id);
	$('#idEstado').attr('value', idestado);
	$('.noSeleccion').css('display', 'block');
}

$('.popupsAgregar a').wowwindow({
    draggable: true,
    width:800,
    overlay: {clickToClose: false,
    	      background: '#000000'},
	onclose: function() {$('.formError').remove();},
	before: function(){
		$.ajax({
			url:'index_blank.php?component=perfiles&method=agregarPerfil&id=0',
			async:true,
			type: "POST",
			success: function(msm){
				$('#wowwindow-inner').html(msm);
	        }
		});
	}
});

$('.popupsVer a').wowwindow({
    draggable: true,
    width:800,
    overlay: {clickToClose: false,
	      background: '#000000' },
	onclose: function() {$('.formError').remove();},
    before: function(){
    	$.ajax({
			url:'index_blank.php?component=perfiles&method=verPerfiles',
			async:true,
			data:'id='+$('#idSeleccionado').attr('value'),
			success: function(msm){
				$('#wowwindow-inner').html(msm);
	        }
		});
	}
});

$('.popupsEditar a').wowwindow({
    draggable: true,
    width:800,
    overlay: {clickToClose: false,
	      background: '#000000' },
	onclose: function() {$('.formError').remove();},
    before: function(){
    	$.ajax({
			url:'index_blank.php?component=perfiles&method=editarPerfiles',
			async:true,
			data:'id='+$('#idSeleccionado').attr('value'),
			success: function(msm){
				$('#wowwindow-inner').html(msm);
	        }
		});
	}
});

function editarPerfil(id_perfil){
	$.ajax({
		url:'index_blank.php?component=perfiles&method=editarPerfiles',
		type: "POST",
		data:'id='+id_perfil,
		success: function(msm){
			$('#wowwindow-inner').html(msm);
        }
	});
}

function eliminarPerfil(){
	var nombre = $('#nombreSeleccionado').attr('value');
	var id = $('#idSeleccionado').attr('value');
	if(confirm('¿Realmente desea eliminar el perfil '+nombre+' ?')){
		$.ajax({
		  type: "POST",
		  url: 'index_blank.php',
		  data: 'component=perfiles&method=cambiarEstadoPerfil&id='+id+'&estado=E',
		  success: function(msm){
		   $('#componente_central').html(msm);
		  }
		});
	}
}

function activarDesactivar(){
	var nombre = $('#nombreSeleccionado').attr('value');
	var id = $('#idSeleccionado').attr('value');
	var estado = $('#idEstado').attr('value');
	if($('#idEstado').attr('value')=='A')
		{estado='I';}
	else
		{estado='A';}
	if(confirm('¿Realmente desea cambiar el estado del perfil "'+nombre+'" ?')){
		$.ajax({
		  type: "POST",
		  url: 'index_blank.php',
		  data: 'component=perfiles&method=cambiarEstadoPerfil&id='+id+'&estado='+estado,
		  success: function(msm){
		  	$('#componente_central').html(msm);
		  }
		});
	}
}

function paginar(pagina){
	$('#pagina').attr('value', pagina);
    filtrar();
}

function ordenadoActual(){
	$('.noOrden').html('');
	var orden_actual = $('#orden').attr('value');
	var id_actual = $('#id_orden').attr('value');
	if(id_actual=='ASC'){
		$('#span'+orden_actual).html('<span style="font-size: 15px">&uarr;</span>');
	}
	else if(id_actual=='DESC'){
		$('#span'+orden_actual).html('<span style="font-size: 15px">&darr;</span>');
	}
	else{
		$('#span'+orden_actual).html('');
	}
}

function buscarCoincidencias(){
	$('#pagina').attr('value', 1);
    filtrar();
}

function filtrar(){
	var busqueda = trim($('#campoBuscar').attr('value'));
	$('#buscar').attr('value', busqueda);
	$.ajax({
	  url: 'index_blank.php?component=perfiles&method=listadoPerfiles',
	  data: $('#form_filtros').serialize(),
	  success: function(msm){
	   $('#componente_central').html(msm);
	  }
	});
}
</script>