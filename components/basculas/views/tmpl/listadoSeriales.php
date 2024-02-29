{COMODIN}
<div class="div_barra" style="width:579px;">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td align="right" height="30px" style="padding-left: 20px;">
          Orden: <b>{numordenfull}</b>
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
              <img src="img/acciones/agregar.png" title="Cargar" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
            <div class="borde_circular" onclick="javascript:eliminartodos()">
                <a href="javascript:void(0)">
                    <img src="img/acciones/borrarseriales.png" title="Eliminar Todos" width="25" height="25" border="0" />
                </a>
            </div>
        </td>
        <td align="center">
          <div class="popupsBuscar borde_circular">
            <a href="">
              <img src="img/acciones/buscarserial.png" title="Buscar Todos" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
					<div class="popupsVer borde_circular noActivado">
						<a href="">
							<img src="img/acciones/ver.png" title="Ver" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
        <td align="center">
            <div class="borde_circular noActivado" onclick="javascript:eliminar()">
                <a href="javascript:void(0)">
                    <img src="img/acciones/eliminar.png" title="Eliminar" width="25" height="25" border="0" />
                </a>
            </div>
        </td>
      </tr>
    </table>
  </div>
</div>
<div id="alerta_accion" style="display: {verAlerta}">{alerta_accion}</div>
<div style="height: 43px"></div>
<table align="right" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="right" height="30px">
			<a href="javascript:buscarCoincidencias()"><img src="img/acciones/buscar.png" title="Buscar" width="17" height="20" border="0" /></a>
		</td>
		<td>
			<input type="text" name="campoBuscar" id="campoBuscar" value="{campoBuscar}" />
		</td>
	</tr>
</table>
<table align="center" style="width:100%" cellpadding="0" cellspacing="0" id="tabla_seriales">
	<tr>
		<th colspan="4">SERIALES</th>
	</tr>
	<tr>
		<th style="width:10%;">No.</th>
		<th style="width:20%;" class="cursor_ordenar" onclick="javascript:ordenar('fecha')" title="Ordenar por Fecha">
			Fecha
			<span id="spanfecha" class="noOrden"></span>
		</th>
		<th style="width:40%;" class="cursor_ordenar" onclick="javascript:ordenar('serial')" title="Ordenar por Serial">
			Serial
			<span id="spanserial" class="noOrden"></span>
		</th>
		<th style="width:40%;">Seleccionar</th>
	</tr>
  <!-- BEGIN ROW  -->
	<tr id="{id_tr_estilo}">
		<td style="text-align:center;">{n}</td>
		<td style="text-align:center;">{fecha}</td>
		<td style="text-align:center;">{serial}</td>
		<td align="center">
    	<input type="radio" name="seleccion" onclick="javascript:activado('{serial}', '{id}')" />
    </td>
	</tr>
	<!-- END ROW  -->
</table>
<p><span id="msgbox" style="display:block;" class="{estilo}"> {mensaje} </span></p>
<br />{paginacion}

<input type="hidden" name="serialActivado" id="serialActivado" />
<input type="hidden" name="idActivado" id="idActivado" />

<form name="form_filtros" id="form_filtros" action="index_blank.php">
	<input type="hidden" name="pagina" id="pagina" value="{pagina}" />
	<input type="hidden" name="buscar" id="buscar" value="{buscar}" />
	<input type="hidden" name="orden" id="orden" value="{orden}" />
	<input type="hidden" name="id_orden" id="id_orden" value="{id_orden}" />
	<input type="hidden" name="numorden" id="numorden" value="{numorden}" />
	<input type="hidden" name="codreferencia" id="codreferencia" value="{codreferencia}" />
	<input type="hidden" name="numordenfull" id="numordenfull" value="{numordenfull}" />
</form>

<script>
	Nifty("div.borde_circular","transparent");
	Nifty("div.div_barra","top transparent");
	$('.noActivado').css('display', 'none');
		
	ordenadoActual();
	
	function activado(serial, id) {
		$('#serialActivado').attr('value', serial);
		$('#idActivado').attr('value', id);
		$('.noActivado').css('display', 'block');
	}
	
	function paginar(pagina) {
		$('#pagina').attr('value', pagina);
    filtrar();
	}

	function ordenadoActual() {
		$('.noOrden').html('');
		var orden_actual = $('#orden').attr('value');
		var id_actual = $('#id_orden').attr('value');
		var cSpan = orden_actual.replace('.','');

		if(id_actual == 'ASC') {
			$('#span'+cSpan).html('<span style="font-size: 15px">&uarr;</span>');
		} else if(id_actual == 'DESC') {
			$('#span'+cSpan).html('<span style="font-size: 15px">&darr;</span>');
		} else {
			$('#span'+cSpan).html('');
		}
	}
	
	function buscarCoincidencias() {
		$('#pagina').attr('value', 1);
   	filtrar();
	}
	
	function filtrar() {
		var busqueda = trim($('#campoBuscar').attr('value'));

		$('#buscar').attr('value', busqueda);
		$.ajax({
	  	url: 'index_blank.php?component=seriales&method=listadoSeriales',
	  	data: $('#form_filtros').serialize(),
	  	success: function(msm) {
				$('div#ventana_seriales').html(msm);
	  	}
		});
	}
	
	$('.popupsAgregar a').wowwindow({
		draggable: true,
		width: 600,
		height: 312,
		overlay: {
			clickToClose: false,
			background: '#000000'
		},
		onclose: function() {
			$('.formError').remove();
		},
		before: function() {
			var numorden = $("#numorden").attr("value");
			var codreferencia = $("#codreferencia").attr("value");
			var numordenfull = $("#numordenfull").attr("value");
			
			$.ajax({
				url: 'index_blank.php?component=seriales&method=agregarSeriales',
				data: 'numorden='+numorden+'&codreferencia='+codreferencia+'&numordenfull='+numordenfull,
				async: true,
				type: "POST",
				success: function(msm) {
					$('#wowwindow-inner').html(msm);
				}
			});
		}
	});
	
	$('.popupsBuscar a').wowwindow({
		draggable: true,
		width: 450,
		height: 300,
		overlay: {
			clickToClose: false,
			background: '#000000'
		},
		onclose: function() {
			$('.formError').remove();
		},
		before: function() {
			var numorden = $("#numorden").attr("value");
			var codreferencia = $("#codreferencia").attr("value");
			var numordenfull = $("#numordenfull").attr("value");
			
			$.ajax({
				url: 'index_blank.php?component=seriales&method=buscarTodos',
				data: 'numorden='+numorden+'&codreferencia='+codreferencia+'&numordenfull='+numordenfull,
				async: true,
				type: "POST",
				success: function(msm) {
					$('#wowwindow-inner').html(msm);
				}
			});
		}
	});
	
	$('.popupsVer a').wowwindow({
    draggable: true,
    width: 350,
		height: 150,
    overlay: {
			clickToClose: false,
    	background: '#000000'
		},
		onclose: function() {
			$('.formError').remove();
		},
		before: function() {
			$.ajax({
				url: 'index_blank.php?component=seriales&method=verSerial',
				async: true,
				type: "POST",
				data: 'serial='+$('#serialActivado').attr('value'),
				success: function(msm) {
					$('#wowwindow-inner').html(msm);
				}
			});
		}
	});
	
	function eliminartodos() {
		var id = $('#idActivado').attr('value');
		var numorden = $('#numorden').attr('value');
		var codreferencia = $('#codreferencia').attr('value');
		var numordenfull = $('#numordenfull').attr('value');

		if(confirm('\u00bfRealmente desea eliminar todos los seriales de la Orden '+numordenfull+'?')) {
			$.ajax({
				url: 'index_blank.php?component=seriales&method=eliminarSeriales',
				async: true,
				type: "POST",
				data: 'id='+id+'&numorden='+numorden+'&codreferencia='+codreferencia+'&numordenfull='+numordenfull,
				success: function(msm) {
					$('div#ventana_seriales').html(msm);
				}
			});
		}			
	}
	
	function eliminar() {
		var serial = $('#serialActivado').attr('value');
		var id = $('#idActivado').attr('value');
		var numorden = $('#numorden').attr('value');
		var codreferencia = $('#codreferencia').attr('value');
		var numordenfull = $('#numordenfull').attr('value');

		if(confirm('\u00bfRealmente desea eliminar el serial '+serial+'?')) {
			$.ajax({
				url: 'index_blank.php?component=seriales&method=eliminarSerial',
				async: true,
				type: "POST",
				data: 'id='+id+'&numorden='+numorden+'&codreferencia='+codreferencia+'&numordenfull='+numordenfull,
				success: function(msm) {
					$('div#ventana_seriales').html(msm);
				}
			});
		}			
	}
</script>