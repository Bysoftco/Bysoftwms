{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>
          LISTADO PARA ETIQUETAR ALISTAMIENTOS
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
<form id="listadoAlistamientos" name="listadoAlistamientos">
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
  <tr>
    <th colspan="7">ALISTAMIENTOS</th>
  </tr>
  <tr>
    <th>No.</th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('alistamiento')" title="Ordenar por N&uacute;mero de Alistamiento">
      Alistamiento
      <span id="spanalistamiento" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('nombre_cliente')" title="Ordenar por Cliente">
      Cliente
      <span id="spannombre_cliente" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('fecha')" title="Ordenar por Fecha">
      Fecha
      <span id="spanfecha" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('pedido')" title="Ordenar por Pedido">
      Pedido
      <span id="spanpedido" class="noOrden"></span>
    </th>
    <th class="cursor_ordenar" onclick="javascript:ordenar('fmm')" title="Ordenar por FMM">
      FMM
      <span id="spanfmm" class="noOrden"></span>
    </th>
    <th style="width:10px;">Acción</th>
  </tr>
  <!-- BEGIN ROW  -->
  <tr id="{id_tr_estilo}">
    <td style="text-align: center;">{n}</td>  
    <td style="text-align: center;">{alistamiento}</td>
    <td style="text-align: center; text-transform:uppercase;">{nombre_cliente}</td>
    <td style="text-align: center;">{fecha}</td>        
    <td style="text-align: center; text-transform:lowercase;">{pedido}</td>
    <td style="text-align: center; text-transform:lowercase;">{fmm}</td>
    <td align="center">
      <a href="#" class="signup id_mercancia" title="Etiquetar Alistamiento {alistamiento}" id="{n}">
        <input type="hidden" name="alistamiento" id="alistamiento{n}" value="{alistamiento}"/>
        <img src="img/acciones/buscar.png" border="0" width="20px;" height="20px;" /> 
      </a>
    </td>  
  </tr>
  <!-- END ROW  -->
</table>
</form>
<p><span id="msgbox" style="display: block;" class="{estilo}">{mensaje}</span></p>
<br />{paginacion}

<form name="form_filtroset" id="form_filtroset" action="index_blank.php">
  <input type="hidden" name="pagina" id="pagina" value="{pagina}" />
  <input type="hidden" name="buscar" id="buscar" value="{buscar}" />
  <input type="hidden" name="orden" id="orden" value="{orden}" />
  <input type="hidden" name="id_orden" id="id_orden" value="{id_orden}" />
  <input type="hidden" name="nitea" id="nitea" value="{nitea}" />
  <input type="hidden" name="fechadesdeea" id="fechadesdeea" value="{fechadesdeea}" />
  <input type="hidden" name="fechahastaea" id="fechahastaea" value="{fechahastaea}" />
  <input type="hidden" name="nalista" id="nalista" value="{nalista}" />
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
      url: 'index_blank.php?component=Alistamientos&method=listadoEtiquetar',
      data: $('#form_filtroset').serialize(),
      success: function(msm) {
        $('#componente_central').html(msm);
      }
    });
  }

  function buscarCoincidencias() {
    $('#pagina').attr('value', 1);
    filtrar();
  }
  
  $(function() {
    $("a.id_mercancia").click(function() { //check for the first selection
      var $column = $(this).attr('id'); // assign the ID of the column
      with (document.listadoAlistamientos) {
        var registro = $("#alistamiento"+$column).val();
        url = 'index.php?component=Alistamientos&method=mostrarEtiquetarAlistamiento&id_registro='+registro;
      }

      $.ajax({
        url: url,
        type: "POST",
        async: false,
        data: $('#listadoAlistamientos').serialize(),
        success: function(msm) {
          $('#componente_central').html(msm);
        }
      });
    });
    
    $(":submit").button();
  });
</script>