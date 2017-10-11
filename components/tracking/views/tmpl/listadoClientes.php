{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>
          ADMINISTRACI&Oacute;N DE CLIENTES
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
          <div class="borde_circular">
            <a href="javascript:agregarClientes()">
              <img src="img/acciones/agregar.png" title="Agregar" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
          <div class="borde_circular noSeleccion">
            <a href="javascript:verCliente()">
              <img src="img/acciones/ver.png" title="Ver" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
          <div class="borde_circular noSeleccion">
            <a href="javascript:editarCliente()">
              <img src="img/acciones/edit.png" title="Editar" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
            <div class="borde_circular noSeleccion" onclick="javascript:eliminar()">
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
<div style="height: 10px"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
  <tr>
    <th colspan="8">CLIENTES</th>
  </tr>
  <tr>
    <th class="cursor_ordenar" onclick="javascript:ordenar('td.nombre')" title="Ordenar por Tipo documento">
      Tipo Documento
      <span id="spantdnombre" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('c.numero_documento')" title="Ordenar por No. Documento">
      No. Documento
      <span id="spancnumero_documento" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('c.digito_verificacion')" title="Ordenar por D&iacute;gito">
      D&iacute;gito de Verificaci&oacute;n
      <span id="spancdigito_verificacion" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('c.razon_social')" title="Ordenar por Raz&oacute;n Social">
      Raz&oacute;n Social
      <span id="spancrazon_social" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('tc.nombre')" title="Ordenar por Tipo">
      Tipo
      <span id="spantcnombre" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('ae.nombre')" title="Ordenar por Actividad">
      Actividad Econ&oacute;mica
      <span id="spanaenombre" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('r.nombre')" title="Ordenar por R&eacute;gimen">
      R&eacute;gimen
      <span id="spanrnombre" class="noOrden"></span>
    </th>
    <th width="10px">Seleccionar</th>
  </tr>
  <!-- BEGIN ROW  -->
  <tr id="{id_tr_estilo}">
    <td>{tipo_documento}</td>
    <td>{no_documento}</td>
    <td>{dig_verificacion}</td>
    <td>{razon_social}</td>
    <td>{tipo}</td>
    <td>{actividad_economica}</td>
    <td>{regimen}</td>
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

  function paginar(pagina) {
    $('#pagina').attr('value', pagina);
    filtrar();
  }

  function seleccionado(documento) {
    $('#noSeleccionado').attr('value', documento);
    $('.noSeleccion').css('display', 'block');
  }

  function ordenadoActual() {
    $('.noOrden').html('');
	var orden_actual = $('#orden').attr('value');
	var id_actual = $('#id_orden').attr('value');
	var cSpan = orden_actual.replace('.','');
	if(id_actual=='ASC') {
      $('#span'+cSpan).html('<span style="font-size: 15px">&uarr;</span>');
    } else if(id_actual=='DESC') {
      $('#span'+cSpan).html('<span style="font-size: 15px">&darr;</span>');
	} else {
      $('#span'+cSpan).html('');
	}
  }

  function filtrar() {
    var busqueda = trim($('#campoBuscar').attr('value'));
	$('#buscar').attr('value', busqueda);
	$.ajax({
	  url: 'index_blank.php?component=clientes&method=listadoClientes',
	  data: $('#form_filtros').serialize(),
	  success: function(msm){
	   $('#componente_central').html(msm);
	  }
	});
  }

  function buscarCoincidencias() {
	$('#pagina').attr('value', 1);
    filtrar();
  }

  function agregarClientes() {
	$.ajax({
      url:'index_blank.php?component=clientes&method=agregarCliente',
      async:true,
      type: "POST",
      success: function(msm){
        $('#componente_central').html(msm);
      }
	});
  }

  function verCliente() {
	$.ajax({
      url:'index_blank.php?component=clientes&method=verCliente',
      async:true,
      type: "POST",
      data:'documento='+$('#noSeleccionado').attr('value'),
      success: function(msm){
        $('#componente_central').html(msm);
      }
	});
  }

  function editarCliente() {
	$.ajax({
      url:'index_blank.php?component=clientes&method=editarCliente',
      async:true,
      type: "POST",
      data:'documento='+$('#noSeleccionado').attr('value'),
      success: function(msm){
        $('#componente_central').html(msm);
      }
	});
  }
  
  function eliminar(){
        if(confirm("Realmente desea eliminar el Cliente?")){
            $.ajax({
                url: 'index_blank.php?component=clientes&method=eliminarCliente',
                async: true,
                type: "POST",
                data:'documento='+$('#noSeleccionado').attr('value'),
                success: function(msm){
                    $('#componente_central').html(msm);
                }
            });
        }
    }
</script>