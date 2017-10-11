{COMODIN}
<div class="div_barra">
	<div id="titulo_ruta">
    <table align="right" cellpadding="5" cellspacing="0" border="0">
      <tr>
        <td>ADMINISTRACI&Oacute;N DE USUARIOS</td>
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
					<div class="borde_circular noSeleccion" onclick="javascript:eliminarUsuario()">
						<a href="javascript:void(0)">
							<img src="img/acciones/eliminar.png" title="Eliminar" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div style="padding-top:43px;"></div>
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
		<th colspan="9">USUARIOS</th>
	</tr>
	<tr>
		<th class="cursor_ordenar" onclick="javascript:ordenar('us.usuario')" title="Ordenar por Usuario">
			Usuario
			<span id="spanususuario" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('pe.nombre')" title="Ordenar por Perfil">
			Perfil
			<span id="spanpenombre" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('us.nombre_usuario')" title="Ordenar por Nombres">
			Nombres
			<span id="spanusnombre_usuario" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('us.apellido_usuario')" title="Ordenar por Apellidos">
			Apellidos
			<span id="spanusapellido_usuario" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('us.mail_usuario')" title="Ordenar por Correo">
			Correo Electr&oacute;nico
			<span id="spanusmail_usuario" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('us.fecha_creacion')" title="Ordenar por Fecha Creaci&oacute;n">
			Fecha Creaci&oacute;n
			<span id="spanusfecha_creacion" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('se.nombre')" title="Ordenar por Sede">
			Sede
			<span id="spansenombre" class="noOrden"></span>
		</th>
		<th class="cursor_ordenar" onclick="javascript:ordenar('us.estado')" title="Ordenar por Estado">
			Estado
			<span id="spanusestado" class="noOrden"></span>
		</th>
		<th width="10px">Seleccionar</th>
	</tr>
	<!-- BEGIN ROW  -->
	<tr id="{id_tr_estilo}">
		<td>{usuario}</td>
		<td>{perfil}</td>
		<td>{nombres}</td>
		<td>{apellidos}</td>
		<td>{email}</td>
		<td align="center">{fecha_creacion}</td>
		<td align="center">{sede}</td>
		<td align="center">{estado}</td>
		<td align="center"><input type="radio" name="seleccion" onclick="javascript:seleccionado('{usuario}', '{id}', '{idestado}')" /></td>
	</tr>
	<!-- END ROW  -->
</table>
<br />{paginacion}

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

function paginar(pagina){
	$('#pagina').attr('value', pagina);
    filtrar();
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

$('.popupsAgregar a').wowwindow({
    draggable: true,
    width:800,
    overlay: {clickToClose: false,
    	      background: '#000000'},
	onclose: function() {$('.formError').remove();},
	before: function(){
		$.ajax({
			url:'index_blank.php?component=usuarios&method=agregarUsuario',
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
    	      background: '#000000'},
	onclose: function() {$('.formError').remove();},
	before: function(){
		$.ajax({
			url:'index_blank.php?component=usuarios&method=verUsuarios',
			async:true,
			type: "POST",
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
			url:'index_blank.php?component=usuarios&method=editarUsuario',
			async:true,
			data:'id='+$('#idSeleccionado').attr('value'),
			success: function(msm){
				$('#wowwindow-inner').html(msm);
	        }
		});
	}
});

function buscarCoincidencias(){
	$('#pagina').attr('value', 1);
    filtrar();
}

function filtrar(){
	var busqueda = trim($('#campoBuscar').attr('value'));
	$('#buscar').attr('value', busqueda);
	$.ajax({
	  url: 'index_blank.php?component=usuarios&method=listadoUsuarios',
	  data: $('#form_filtros').serialize(),
	  success: function(msm){
	   $('#componente_central').html(msm);
	  }
	});
}

function activarDesactivar(){
	var nombre = $('#nombreSeleccionado').attr('value');
	var id = $('#idSeleccionado').attr('value');
	var estado = $('#idEstado').attr('value');
	if($('#idEstado').attr('value')=='A')
		{estado='I';}
	else
		{estado='A';}
	if(confirm('¿Realmente desea cambiar el estado del Usuario "'+nombre+'" ?')){
		$.ajax({
		  type: "POST",
		  url: 'index_blank.php',
		  data: 'component=usuarios&method=cambiarEstadoUsuario&id='+id+'&estado='+estado,
		  success: function(msm){
		  	$('#componente_central').html(msm);
		  }
		});
	}
}

function eliminarUsuario(){
	var nombre = $('#nombreSeleccionado').attr('value');
	var id = $('#idSeleccionado').attr('value');
	if(confirm('¿Realmente desea eliminar el usuario '+nombre+' ?')){
		$.ajax({
		  type: "POST",
		  url: 'index_blank.php',
		  data: 'component=usuarios&method=cambiarEstadoUsuario&id='+id+'&estado=E',
		  success: function(msm){
		   $('#componente_central').html(msm);
		  }
		});
	}
}

</script>