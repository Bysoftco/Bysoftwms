{COMODIN}
<style>
  #sortable1, #sortable2 { list-style-type: none; padding: 0 0 3em; margin: 10px; }
  #sortable1 li, #sortable2 li { margin: 0 3px 3px 3px; padding: 3px; font-size: 12px; }
</style>
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;font-family:sans-serif;font-size:10px;">
          ACONDICIONAMIENTO DE MERCANCÍA <b>{nombre_tipo_mercancia}</b> - [{documento_cliente}] <b>{nombre_cliente}</b>
        </td>
      </tr>
    </table>
  </div>
</div>
<div style="padding-top: 38px;"></div>
<div id="alerta_accion" style="display: {verAlerta}">{alerta_accion}</div>
<div style="height: 2px"></div>
<table width="100%" border="0" align="center" cellpadding="20">
  <tr>
    <td width="40%" style="text-align: left; vertical-align: top;">
      <fieldset>
        <legend align="left">
          Referencias Disponibles
        </legend>
        <div id="live_filter">
          <div style="padding-left: 10px">
            <input type="text" class="filter" name="liveFilter" placeholder="Filtrar" />
          </div>                    
          <ul id="sortable1" class="connectedSortable">
            <!-- BEGIN ROW -->
            <li class="ui-state-default">
              <input type="hidden" value="{codigo}" name="seleccion[]" />
              [{codigo_ref}] {nombre_ref}
            </li>
            <!-- END ROW -->
          </ul>
        </div>
      </fieldset>
    </td>
    <td width="40%" style="text-align: left; vertical-align: top;">
      <form name="formReferencias" id="formReferencias" action="javascript:enviarAcondicionamiento()">
        <fieldset>
          <legend align="left">
            Referencias a Acondicionar
          </legend>
          <ul id="sortable2" class="connectedSortable">
            <input type="hidden" name="docCliente" id="docCliente" value="{documento_cliente}" />
            <input type="hidden" name="tipo_mercancia" id="tipo_mercancia" value="{tipo_mercancia}" />
            <input type="hidden" name="nombre_tipo_mercancia" id="nombre_tipo_mercancia" value="{nombre_tipo_mercancia}" />
          </ul>
          <input type="submit" class="button" value="Enviar" />
        </fieldset>
      </form>
    </td>
  </tr>
</table>
<div class="acondicionar">
  <a id="realizarAcondicionamiento" href="" title="Preparación del Acondicionamiento">
  </a>
</div>

<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");    

  $(function() {
    $( "#sortable1, #sortable2" ).sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();
  });

  $(document).ready(function() {
    $('#live_filter').liveFilter('ul');
  });

  $("li", "#sortable1").live("dblclick", function() { $(this).appendTo("#sortable2"); });
  $("li", "#sortable2").live("dblclick", function() { $(this).appendTo("#sortable1"); });

  function enviarAcondicionamiento() {
    if($("#sortable2 li").length>0) {
      if(confirm("\u00BFRealmente desea realizar el acondicionamiento con las referencias seleccionadas?")) {
        realizarAcondicionamiento.click();
      }
    } else {
      alert("Debe seleccionar por lo menos una referencia para poder continuar.");
    } 
  }

  $('.acondicionar a').wowwindow({
    draggable: true,
    width: 900,
    height: 450,
    overlay: {
      clickToClose: false,
      background: '#000000'
    },
    onclose: function() {
      $('.formError').remove();
    },
    before: function() {
      $.ajax({
        url: 'index_blank.php?component=acondicionamientos&method=acondicionarReferencias',
        async: true,
        type: "POST",
        data:$('#formReferencias').serialize(),
        success: function(msm) {
          $('#wowwindow-inner').html(msm);
        }
      });
    }
  });
</script>