{COMODIN}
<link rel="stylesheet" type="text/css" href="integrado/cz_estilos/jquery.autocomplete.css" />
<form action="javascript:mostrarReferencias()" id="filtroCliente" name="filtroCliente" method="post"><br /><br />
  <table align="center" width="50%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr class="tituloForm" align="center">
      <td  align="center" colspan="2">
        Ingrese parte del nombre de cliente y seleccione la opci&oacute;n que desea consultar...
      </td>
    </tr>
    <tr>
      <td align="right">Por cuenta de:</td>
      <td align="left">
        <input type="text" id="buscarCliente_Kit" class="{required:true}" size="50" style="width: 255px; height: 20px;" />
      </td>
    </tr>
    <tr>
      <td align="right">Nit:</td>
      <td align="left">
        <input name="docCliente" type="text" class="{required:true}" id="docCliente" style="width: 255px; height: 20px;" />
      </td>
    </tr>
    <tr>
      <td align="right">Tipo de mercanc&iacute;a:</td>
      <td align="left">
        <select class="{required:true} input" id="tipo_mercancia" name="tipo_mercancia" style="width: 260px;">
          <option value="1">Nacional</option>
          <option value="2">Extranjera</option>
          <option value="3">Mixta</option>
        </select>
      </td>
    </tr>
    <tr align="center">
      <td colspan="2">
        <input type="submit" name="enviar" value="Enviar" />
      </td>
    </tr>
  </table>
</form>
<script>
  $().ready(function() {
    $("#filtroCliente").validate();
  });

  function mostrarReferencias() {
    $.ajax({
      url: 'index_blank.php?component=Alistamientos&method=validarCliente',
      data: "docCliente="+$("#docCliente").attr("value"),
      async: true,
      type: "POST",
      success: function(msm) {
        if(msm=='valido') {
          $.ajax({
            url: 'index_blank.php?component=Alistamientos&method=mostrarListadoReferencias',
            data: $("#filtroCliente").serialize(),
            async: true,
            type: "POST",
            success: function(msm) {
              jQuery(document.body).overlayPlayground('close');void(0);
              $('#componente_central').html(msm);
            }
          });
        } else {
          alert("El Cliente con identificación "+$("#docCliente").attr("value")+" NO se encuentra registrado en la BD")
        }
      }
    });
  }
    
  $("#buscarCliente_Kit").autocomplete("scripts_index.php?clase=Orden&metodo=findCliente", {
    width: 260,
    selectFirst: false
  });
    
  $("#buscarCliente_Kit").result(function(event, data, formatted) {
    $("#docCliente").val(data[1]);
  });
</script>