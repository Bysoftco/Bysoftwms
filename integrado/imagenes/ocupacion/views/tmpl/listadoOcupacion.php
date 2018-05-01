{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          CONSULTA OCUPACI&Oacute;N
        </td>
      </tr>
    </table>
  </div>
  <div id="contenedor_opciones" align="left">
    <table border="0">
      <tr>
				<td align="center">
					<div class="borde_circular">
						<a href="javascript: vFiltro()">
							<img src="img/acciones/ver.png" title="Filtro Consulta Ocupación" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
        <td align="center">
          <div class="borde_circular">
            <a href="" target="_blank" class="tblank">
              <img src="img/acciones/printer.gif" title="Imprimir" width="25" height="25" border="0" />
            </a>
          </div> 
        </td>
      </tr>
    </table>
  </div>
</div>
<div style="padding-top: 43px;"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z" class="display">
  <thead>
    <tr>
      <th>Ubicaci&oacute;n</th>
      <th>Cliente</th>
      <th>C&oacute;digo Referencia</th>
      <th>Referencia</th>      
      <th>Documento TTE</th>
      <th>Orden</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;padding: 2.5px;">
        <img src="components/ocupacion/views/tmpl/generar.php?ocupacion={nombre_ubicacion}" />
      </td>
      <td>[{doc_cliente}] {nombre_cliente}</td>
      <td style="text-align: center;">{codigo_referencia}</td>
      <td>{nombre_referencia}</td>
      <td style="text-align: center;">{doc_transporte}</td>
      <td style="text-align: center;">{orden}</td>
    </tr>
    <!-- END ROW  -->
  </tbody>
</table>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");

  $(document).ready(function() {
    $('#tabla_general_z').dataTable({
      "aaSorting": [],
      "iDisplayLength": 20,
      "aLengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]],
      "oLanguage": {
        "sLengthMenu": "Mostrar _MENU_ registros por página",
        "sZeroRecords": "No hay registros para mostrar",
        "sInfo": "Mostrando _START_ a _END_ registros de _TOTAL_",
        "sInfoEmpty": "Mostrando 0 a 0 registros de 0",
        "sInfoFiltered": "",
        "sSearch": "Buscar:"
      }
    });
  });	

  function vFiltro() {
    $.ajax({
      url: 'index_blank.php?component=ocupacion&method=filtroOcupacion',
      type: "POST",
      async: false,
      success: function(msm) {
        $("#winocupacion").dialog("close");
        $('#componente_central').html(msm);
      }
    });
  }

  $('a[class="tblank"]').click(function() {
    window.open("index_blank.php?component=ocupacion&method=imprimeListadoOcupacion&buscarClienteo="+$("#buscarClienteo").val()+
      "&nito="+$("#nito").val()+"&doctteo="+$("#doctteo").val()+"&doasignadoo="+$("#doasignadoo").val()+"&ocupaciono="+$("#ocupaciono").val()+
      "&referenciao="+$("#referenciao").val());
    return false;
  });
  
  function Excel() {
    document.frmExcel.submit();
  }
</script>