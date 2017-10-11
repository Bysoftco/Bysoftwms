{COMODIN}
<div id="tab_aplica">
  {tab_que_aplica}
</div>
<div style="height: 30px;"></div>
<div id="tabs">
  <ul>
    <li><a href="#infoCliente" onclick="javascript:cambiarTab('header_info_cliente')">Informaci&oacute;n de cliente</a></li>
    <li><a href="#tarifasCliente" onclick="javascript:cambiarTab('header_tarifas')">Tarifas Clientes</a></li>
    <li><a href="#referenciasCliente" onclick="javascript:cambiarTab('header_referencias')">Referencias</a></li>
    <li><a href="#documentosCliente" onclick="javascript:cambiarTab('header_documentos')">Documentos Anexos</a></li>
    <li><a href="#contactosCliente">Contactos</a></li>
  </ul>
  <div id="infoCliente">
    {info_cliente}
  </div>
  <div id="tarifasCliente">
    {info_tarifas}
  </div>
  <div id="referenciasCliente">
    {info_referencias}
  </div>
  <div id="documentosCliente">
    {info_documentos}
  </div>
  <div id="contactosCliente">
    {info_contactos}
  </div>
</div>
<input type="hidden" name="numero_documento" id="numero_documento" value="{numero_identificacion}" />
<script>
  $(function() {
    $( "#tabs" ).tabs();
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