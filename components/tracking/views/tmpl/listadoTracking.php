{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>
          ADMINISTRACI&Oacute;N DE TRACKING
        </td>
        <td align="right" height="30px" style="padding-left: 20px;">
          <a href="javascript:buscarCoincidencias()"><img src="img/acciones/buscar.png" title="Buscar" width="17" height="20" border="0" /></a>
        </td>
        <td>
          <input type="text" name="campoBuscar" id="campoBuscar" value="{campoBuscar}" />
        </td>
      </tr>
    </table>
  </div>
  <div id="contenedor_opciones" align="left">
    <table border="0">
      <tr>
				<td align="center">
					<div class="popupsAgregar borde_circular noSeleccion">
						<a href="" title="Agregar Tracking">
							<img src="img/acciones/tracking.png" title="Agregar" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
				<td align="center">
					<div class="popupsVer borde_circular noSeleccion">
						<a href="" title="Ver Tracking">
							<img src="img/acciones/ver.png" title="Ver" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
        <td align="center">
          <div class="borde_circular noElimina">
            <a href="javascript:eliminarTracking()">
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
<div style="height: 2px"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
  <tr>
    <th colspan="10">TRACKING</th>
  </tr>
  <tr>
    <th class="cursor_ordenar" onclick="javascript:ordenar('id')" title="Ordenar por N&uacute;mero de Tracking">
      Tracking
      <span id="spanid" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('fecha')" title="Ordenar por Fecha">
      Fecha
      <span id="spanfecha" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('razon_social')" title="Ordenar por Cliente">
      Cliente
      <span id="spanrazon_social" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('remite')" title="Ordenar por Remitente">
      Remite
      <span id="spanremite" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('destino')" title="Ordenar por Destino">
      Destino
      <span id="spandestino" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('doc_tte')" title="Ordenar por Documento de Transporte">
      Documento TTE
      <span id="spandoc_tte" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('asunto')" title="Ordenar por Asunto">
      Asunto
      <span id="spanasunto" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('creador')" title="Ordenar por Creador">
      Creador
      <span id="spancreador" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('forma')" title="Ordenar por Forma">
      Forma
      <span id="spanforma" class="noOrden"></span>
    </th>
    <th width="10px">Seleccionar</th>
  </tr>
  <!-- BEGIN ROW  -->
  <tr id="{id_tr_estilo}">
    <td style="text-align: center;">{id}</td>
    <td style="text-align: center;">{fecha}</td>
    <td style="text-align: center; text-transform:uppercase;">{razon_social}</td>    
    <td style="text-align: center; text-transform:lowercase;">{remite}</td>
    <td style="text-align: center; text-transform:lowercase;">{destino}</td>
    <td style="text-align: center;">{doc_tte}</td>
    <td style="text-align: center;">{asunto}</td>
    <td style="text-align: center;">{creador}</td>
    <td style="text-align: center;">{forma}</td>
    <td align="center">
      <input type="radio" name="seleccion"
        onclick="javascript:seleccionado('{id}','{sede}','{do_asignado}','{doc_tte}','{por_cuenta}','{destino}','{creador}')" />
    </td>  
  </tr>
  <!-- END ROW  -->
</table>
<p><span id="msgbox" style="display: block;" class="{estilo}">{mensaje}</span></p>
<br />{paginacion}

<input type="hidden" name="noSeleccionado" id="noSeleccionado" />

<form name="form_filtros" id="form_filtros" action="index_blank.php">
  <input type="hidden" name="pagina" id="pagina" value="{pagina}" />
  <input type="hidden" name="buscar" id="buscar" value="{buscar}" />
  <input type="hidden" name="orden" id="orden" value="{orden}" />
  <input type="hidden" name="id_orden" id="id_orden" value="{id_orden}" />
  <input type="hidden" name="perfil" id="perfil" value="{perfil}" />
  <input type="hidden" name="sede" id="sede" value="" />
  <input type="hidden" name="do_asignado" id="do_asignado" value="" />
  <input type="hidden" name="doc_tte" id="doc_tte" value="" />
  <input type="hidden" name="por_cuenta" id="por_cuenta" value="" />
  <input type="hidden" name="destino" id="destino" value="" />
  <input type="hidden" name="creador" id="creador" value="" />
  <input type="hidden" name="nitt" id="nitt" value="{nitt}" />
  <input type="hidden" name="fechadesdet" id="fechadesdet" value="{fechadesdet}" />
  <input type="hidden" name="fechahastat" id="fechahastat" value="{fechahastat}" />
  <input type="hidden" name="doasignadot" id="doasignadot" value="{doasignadot}" />
  <input type="hidden" name="docttet" id="docttet" value="{docttet}" />
  <input type="hidden" name="emaildestino" id="emaildestino" value="{emaildestino}" />
  <input type="hidden" name="perfil" id="perfil" value="{perfil}" />
</form>

<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  $('.noSeleccion').css('display', 'none');
  $('.noElimina').css('display', 'none');
	
  ordenadoActual();

  function paginar(pagina) {
    $('#pagina').attr('value', pagina);
    filtrar();
  }

  function seleccionado(id,sede,do_asignado,doc_tte,por_cuenta,destino,creador) {
    $('.noSeleccion').css('display', 'block');
    $('#noSeleccionado').attr('value', id);
    $('#sede').attr('value', sede);
    $('#do_asignado').attr('value', do_asignado);
    $('#doc_tte').attr('value', doc_tte);
    $('#por_cuenta').attr('value', por_cuenta);
    $('#destino').attr('value', destino);
    $('#creador').attr('value', creador);

    //Valida Perfil Administrador
    if($("#perfil").attr('value') == 1) $('.noElimina').css('display', 'block');
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

  function filtrar() {
    var busqueda = trim($('#campoBuscar').attr('value'));
    $('#buscar').attr('value', busqueda);
    $.ajax({
      url: 'index_blank.php?component=tracking&method=listadoTracking',
      data: $('#form_filtros').serialize(),
      success: function(msm) {
        $('#componente_central').html(msm);
      }
    });
  }

  function buscarCoincidencias() {
    $('#pagina').attr('value', 1);
    filtrar();
  }
  
  $('.popupsAgregar a').wowwindow({
    draggable: true,
    width: 750,
    height: 500,
    overlay: {
      clickToClose: false,
      background: '#000000'
    },
    onclose: function() { $('.formError').remove(); },
    before: function() {
      $.ajax({
        url:'index_blank.php?component=tracking&method=agregarTracking',
        data: {
          id: $('#noSeleccionado').attr('value'),
          sede: $("#sede").val(),
          do_asignado: $("#do_asignado").val(),
          doc_tte: $("#doc_tte").val(),
          por_cuenta: $("#por_cuenta").val(),
          destino: $("#destino").val(),
          creador: $("#creador").val()
        },
        async:true,
        type: "POST",
        success: function(msm) {
          $('#wowwindow-inner').html(msm);
        }
      });
    }
  });

	$('.popupsVer a').wowwindow({
		draggable: true,
		width: 750,
    height: 430,
		overlay: {
			clickToClose: false,
			background: '#000000'
		},
		onclose: function() { $('.formError').remove(); },
		before: function() {
			$.ajax({
				url:'index_blank.php?component=tracking&method=verTracking',
				async:true,
				type: "POST",
        data: {
          id: $('#noSeleccionado').attr('value'),
          sede: $('#sede').attr('value')
        },
				success: function(msm) {
					$('#wowwindow-inner').html(msm);
				}
			});
		}
	});

  function eliminarTracking() {
    var id = $('#noSeleccionado').attr('value', id);
    
    if(confirm('\u00bf Realmente desea eliminar el Tracking '+id+' ?')) {
      $.ajax({
        url: 'index_blank.php?component=tracking&method=eliminarTracking',
        async: true,
        type: "POST",
        data: {
          id: id
        },
        success: function(msm) {
          $('#componente_central').html(msm);
        }
      });
    }      
  }
</script>