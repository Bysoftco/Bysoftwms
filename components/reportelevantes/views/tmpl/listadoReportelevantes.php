{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          LISTADO REPORTE LEVANTES 
        </td>
      </tr>
    </table>
  </div>
  <div id="contenedor_opciones" align="left">
    <table border="0">
      <tr>
				<td align="center">
					<div class="borde_circular">
						<a href="javascript: Excel()">
							<img src="img/acciones/repoexcel.gif" title="Exportar a Excel" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
				<td align="center">
					<div class="borde_circular">
						<a href="javascript: vFiltro()">
							<img src="img/acciones/ver.png" title="Filtro Consulta Ocupación" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
      </tr>
    </table>
  </div>
</div>
<div style="padding-top: 45px;"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z" class="display">
  <thead>
    <tr>
      <th>Cliente</th>
      <th>Orden</th>
      <th>Documento TTE</th>
      <th>C&oacute;digo Referencia</th>
      <th>Referencia</th>
      <th>Ancho</th>
      <th>Fecha</th>
      <th>Piezas</th>
      <th>Peso</th>
      <th>Valor</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="padding-left: 5px;">[{doc_cliente}] {nombre_cliente}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">{doc_transporte}</td>
      <td style="text-align: center;">{codigo_referencia}</td>
      <td style="padding-left: 5px;">{nombre_referencia}</td>
      <td style="text-align: center;">{ancho}</td>
      <td style="text-align: center;">{fecha_naci}</td>
      <td style="text-align: right;">{piezas}</td>
      <td style="text-align: right;">{peso}</td>
      <td style="text-align: right;">{valor}</td>
    </tr>
    {total_piezas}{total_peso}{total_valor}
    <!-- END ROW  -->
  </tbody>
  <tfoot>
    <tr>
      <th>Totales</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th style="text-align: right;">{total_piezas}</th>
      <th style="text-align: right;">{total_peso}</th>
      <th style="text-align: right;">{total_valor}</th>
    </tr>
  </tfoot>
</table>
<form name="frmExcel" action="index_blank.php" >
  <input type="hidden" name="nitrl" id="nitrl" value="{nitrl}" />
  <input type="hidden" name="fechadesderl" id="fechadesderl" value="{fechadesderl}" />
  <input type="hidden" name="fechahastarl" id="fechahastarl" value="{fechahastarl}" />
  <input type="hidden" name="doctterl" id="doctterl" value="{doctterl}" />
  <input type="hidden" name="doasignadorl" id="doasignadorl" value="{doasignadorl}" />
  <input type="hidden" name="referenciarl" id="referenciarl" value="{referenciarl}" />
  <input type="hidden" name="component" id="component" value="reportelevantes" />
  <input type="hidden" name="method" id="method" value="exportarExcel" />
</form>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");

  $(document).ready(function() {
    $('#tabla_general_z').dataTable({
      "aaSorting": [],
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
      url: 'index_blank.php?component=reportelevantes&method=filtroReportelevantes',
      type: "POST",
      async: false,
      success: function(msm) {
        $("#winfiltrorl").dialog("close");
        $('#componente_central').html(msm);
      }
    });
  }
  
  function Excel() {
    document.frmExcel.submit();
  }
</script>