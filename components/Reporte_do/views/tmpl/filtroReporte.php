{COMODIN}
<link rel="stylesheet" type="text/css" href="integrado/cz_estilos/jquery.autocomplete.css" />
<form style="padding-top:30px;" name="form_filtro_do" id="form_filtro_do" method="POST" action="javascript:filtrarReporte()">
  <table width="100%" align="center" id="tabla_general" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th colspan="2">Filtro Reporte</th>
      </tr>
    </thead>
    <tbody>
      <tr class="tituloForm" align="center">
        <td align="center" colspan="2">
          Ingrese parte del nombre de cliente y seleccione la opción que desea consultar...
        </td>
      </tr>
      <tr>
        <td align="right">Por cuenta de:</td>
        <td align="left">
          <input type="text" id="buscarCliente_Do" size="50" value="{cliente}" {soloLectura} />
        </td>
      </tr>
      <tr>
        <td align="right">Nit:</td>
        <td align="left">
          <input name="docCliente" type="text" id="docCliente" value="{usuario}" {soloLectura} />
        </td>
      </tr>
      <tr>
        <td align="right">DO:</td>
        <td align="left">
          <input name="do" type="text" id="do" />
        </td>
      </tr>
      <tr>
        <td align="right">Doc Transporte:</td>
        <td align="left">
          <input name="doc_transporte" type="text" id="doc_transporte" />
        </td>
      </tr>
      <tr>
        <td align="right">Modelo/Lote/Cosecha:</td>
        <td align="left">
          <input name="modelo" type="text" id="modelo" />
        </td>
      </tr>
      <tr>
        <td align="right">Agencia Aduanera:</td>
        <td align="left">
          <select name="agencia_aduana" id="agencia_aduana" style="width: 255px;">
            {select_agencias}
          </select>
        </td>
      </tr>
      <tr>
        <td align="right">Fecha Desde:</td>
        <td align="left">
          <input type="text" name="fecha_desde" id="fecha_desde" />
        </td>
      </tr>
      <tr>
        <td align="right">Fecha Hasta:</td>
        <td align="left">
          <input type="text" name="fecha_hasta" id="fecha_hasta" />
        </td>
      </tr>
      <tr>
        <td align="center" colspan="2">
          <input type="submit" class="button small yellow2" name="ver_reporte" id="ver_reporte" value="Ver Reporte" />
        </td>
      </tr>
    </tbody>
  </table>
</form>
<script>
  $("#buscarCliente_Do").autocomplete("scripts_index.php?clase=Orden&metodo=findCliente", {
    width: 260,
    selectFirst: false
  });

  $("#buscarCliente_Do").result(function(event, data, formatted){
    $("#docCliente").val(data[1]);
  });

  $(function() {
    $( "#fecha_desde" ).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd'
    });
    
    $( "#fecha_hasta" ).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd'
    });
  });

  function filtrarReporte() {
    $.ajax({
      url: 'index_blank.php?component=Reporte_do&method=mostrarListadoOrdenes',
      data: $("#form_filtro_do").serialize(),
      async: true,
      type: "POST",
      success: function(msm) {
        jQuery(document.body).overlayPlayground('close');void(0);
        $('#componente_central').html(msm);
      }
    });
  }
</script>