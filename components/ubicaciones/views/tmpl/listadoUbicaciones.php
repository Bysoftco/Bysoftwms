{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          CONSULTA UBICACIONES
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
							<img src="img/acciones/ver.png" title="Filtro Consulta Ubicación" width="25" height="25" border="0" />
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
      <th>Referencia</th>      
      <th>Documento TTE</th>
      <th>Orden</th>
      <th>Piezas</th>
      <th>Peso</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;padding: 2.5px;">
        <img src="components/ubicaciones/views/tmpl/generar.php?ubicacion={nombre_ubicacion}" />
      </td>
      <td>[{doc_cliente}] {nombre_cliente}</td>
      <td>[{codigo_referencia}] {nombre_referencia}</td>
      <td style="text-align: center;">{doc_transporte}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: right;">{piezas}</td>
      <td style="text-align: right;">{peso}</td>
    </tr>
    <!-- END ROW  -->
  </tbody>
  <tfoot>
    <tr>
      <th colspan="2">Totales</th>
      <th></th>
      <th></th>
      <th></th>
      <th style="text-align: right;">{total_piezas}</th>
      <th style="text-align: right;">{total_peso}</th>
    </tr>
  </tfoot>
</table>
<form name="frmExcel" action="index_blank.php" >
  <input type="hidden" name="buscarClienteu" id="buscarClientefe" value="{buscarClienteu}" />
  <input type="hidden" name="nitu" id="nitu" value="{nitu}" />
  <input type="hidden" name="doctteu" id="doctteu" value="{doctteu}" />
  <input type="hidden" name="doasignadou" id="doasignadou" value="{doasignadou}" />
  <input type="hidden" name="ubicacionu" id="ubicacionu" value="{ubicacionu}" />
  <input type="hidden" name="referenciau" id="referenciau" value="{referenciau}" />
  <input type="hidden" name="todos" id="todos" value="{todos}" />
  <input type="hidden" name="component" id="component" value="ubicaciones" />
  <input type="hidden" name="method" id="method" value="exportarExcel" />
</form>
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
      url: 'index_blank.php?component=ubicaciones&method=filtroUbicaciones',
      type: "POST",
      async: false,
      success: function(msm) {
        $("#winubicaciones").dialog("close");
        $('#componente_central').html(msm);
      }
    });
  }

  $('a[class="tblank"]').click(function() {
    window.open("index_blank.php?component=ubicaciones&method=imprimeListadoUbicaciones&buscarClienteu="+$("#buscarClienteu").val()+
      "&nitu="+$("#nitu").val()+"&doctteu="+$("#doctteu").val()+"&doasignadou="+$("#doasignadou").val()+"&ubicacionu="+$("#ubicacionu").val()+
      "&referenciau="+$("#referenciau").val()+"&todos="+$("#todos").val());
    return false;
  });
  
  function Excel() {
    document.frmExcel.submit();
  }
</script>