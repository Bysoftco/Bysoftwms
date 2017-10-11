{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="font-size:10px; padding-top:5px;">
          LISTADO COSTOS X DO
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
</div>
<div style="padding-top: 43px;"></div>
<div id="alerta_accion" style="display: {verAlerta}">{alerta_accion}</div>
<div style="height: 2px"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
  <tr>
    <th colspan="9">LISTADO COSTOS X DO</th>
  </tr>
  <tr>
    <th class="cursor_ordenar" onclick="javascript:ordenar('do_asignado')" title="Ordenar por DO" width="10%">
      DO
      <span id="spando_asignado" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('razon_social')" title="Ordenar por Cliente" width="20%">
      Cliente
      <span id="spanrazon_social" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('fecha')" title="Ordenar por Fecha" width="10%">
      Fecha
      <span id="spanfecha" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('doc_tte')" title="Ordenar por Documento de Transporte" width="10%">
      Documento
      <span id="spandoc_tte" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('bodega_nombre')" title="Ordenar por Ubicaci&oacute;n" width="15%">
      Ubicaci&oacute;n
      <span id="spanbodega_nombre" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('ingreso_total')" title="Ordenar por Ingreso Total" width="10%">
      Ingreso Total
      <span id="spaningreso_total" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('gasto_total')" title="Ordenar por Gasto Total" width="10%">
      Gasto Total
      <span id="spangasto_total" class="noOrden"></span>
    </th>
    <th width="10%">Utilidad</th>
    <th width="5%">Acci&oacute;n
    	<input name="n" type="hidden" id="n" value="{num_registros}" />
    </th>
  </tr>
  <!-- BEGIN ROW  -->
  <tr id="{id_tr_estilo}">
    <td style="text-align: center; font-size:11px;">{do_asignado}</td>
    <td style="text-align: center; text-transform:uppercase; font-size:11px;">{razon_social}</td>    
    <td style="text-align: center; font-size:11px;">{fecha}</td>
    <td style="text-align: center; font-size:11px;">{documento}</td>
    <td style="text-align: center; font-size:11px;">{ubicacion}</td>
    <td style="text-align: right; font-size:11px;">{ingreso_total}</td>
    <td style="text-align: right; font-size:11px;">{gasto_total}</td>
    <td style="text-align: right; font-size:11px; color: {color};">{utilidad}</td>
    <td style="text-align: center; font-size:11px;">
      <a href="javascript: verDetalle({do_asignado},'{documento}');" title="Costear Do {do_asignado}">
      	<img src="integrado/imagenes/layer--pencil.png" width="16" height="16" border="0" /> 
      </a>
    </td>
  </tr>
  <!-- END ROW  -->
</table>
<p><span id="msgbox" style="display: block;" class="{estilo}">{mensaje}</span></p>
<br />{paginacion}

<input type="hidden" name="noSeleccionado" id="noSeleccionado" />
<div id="vdetallec" title="Detalle de Costos del DO"></div>
<form name="form_filtrosc" id="form_filtrosc" action="index_blank.php">
  <input type="hidden" name="pagina" id="pagina" value="{pagina}" />
  <input type="hidden" name="buscar" id="buscar" value="{buscar}" />
  <input type="hidden" name="orden" id="orden" value="{orden}" />
  <input type="hidden" name="id_orden" id="id_orden" value="{id_orden}" />
	<input type="hidden" name="id" id="id" value="{id}" />
  <input type="hidden" name="sede" id="sede" value="{sede}" />
  <input type="hidden" name="color" id="color" value="{color}" />
  <input type="hidden" name="nitc" id="nitc" value="{nitc}" />
  <input type="hidden" name="fechadesdec" id="fechadesdec" value="{fechadesdec}" />
  <input type="hidden" name="fechahastac" id="fechahastac" value="{fechahastac}" />
  <input type="hidden" name="doasignadoc" id="doasignadoc" value="{doasignadoc}" />
</form>

<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
	
  ordenadoActual();

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

  function filtrar() {
    var busqueda = trim($('#campoBuscar').attr('value'));
    $('#buscar').attr('value', busqueda);
    $.ajax({
      url: 'index_blank.php?component=costeardo&method=listadoCosteardo',
      data: $('#form_filtrosc').serialize(),
      success: function(msm) {
        $('#componente_central').html(msm);
      }
    });
  }

  function buscarCoincidencias() {
    $('#pagina').attr('value', 1);
    filtrar();
  }
	
	function verDetalle(do_asignado,doc_tte) {
		//Dibuja Ventana con el Detalle de los Costos del DO
		$("#vdetallec").dialog({
      autoOpen: false,
      resizable: false,
      height: 400,
      width: 700,
			modal: true,
			buttons: {
        "Actualizar": function() {
          //Parámetros de envío de información
          $.ajax({
            url: 'index_blank.php?component=costeardo&method=actualizaCosteardo',
            type: "POST",
            async: false,
            data: {
              do_asignado: do_asignado,
            },
            success: function(msm) {
              $("#vdetallec").dialog("close");
              $('#componente_central').html(msm);
              $("#vdetallec").dialog("destroy");
            }
          });
        }
      },
		});
		// Muestra la Ventana Detalle de Costos DO
		$("#vdetallec").dialog("open");
		$("#vdetallec").load("./index_blank.php", {
				component:'dcosteardo',
				method:'listadoDetallec',
				do_asignado: do_asignado,
        doc_tte: doc_tte
			}, function(datos) {});
	}	
</script>