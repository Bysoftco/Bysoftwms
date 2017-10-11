{COMODIN}
<div id="tab_aplica">
  {tab_que_aplica}
</div>
<div style="height: 30px;"></div>
<div id="tabs">
  <ul>
    <li><a href="#infoCliente" onclick="javascript:cambiarTab('header_info_cliente')">Informaci&oacute;n del Cliente</a></li>
  </ul>
  <div id="infoCliente">
    {info_cliente}
  </div>
</div>
<input type="hidden" name="numero_documento" id="numero_documento" value="{numero_identificacion}" />
<script>
  $(function() {
	 $("#tabs").tabs();
  });

  function cambiarTab(activar) {
    $.ajax({
      url: 'index_blank.php?component=clientes&method=retornar_header',
      async: true,
      type: "POST",
      data: 'activar='+activar,
      success: function(msm) {
        $('#tab_aplica').html(msm);
      }
    });
  }
</script>