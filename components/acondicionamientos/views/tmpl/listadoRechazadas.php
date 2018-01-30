{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          LISTADO MERCANC&Iacute;AS RECHAZADAS
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
            <a href="" target="_blank" class="tblank">
              <img src="img/acciones/printer.gif" title="Imprimir" width="25" height="25" border="0" />
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
      <th>Referencia</th>
      <th>Nombre Referencia</th>
      <th>M/L/C</th>
      <th>Fecha Rechazo</th>
      <th>Ubicación</th>
      <th>Tipo Rechazo</th>
      <th>Piezas Nal.</th>
      <th>Peso Nal.</th>
      <th>Piezas Ext.</th>
      <th>Peso Ext.</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td>[{doc_cliente}] {nombre_cliente}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">{doc_transporte}</td>
      <td style="text-align: center;">{codigo_referencia}</td>
      <td>{nombre_referencia}</td>
      <td>{modelo}</td>
      <td style="text-align: center;">{fecha_rechazo}</td>
      <td style="text-align: center;">{nombre_ubicacion}</td>
      <td style="text-align: center;">{tipo_rechazo}</td>
      <td style="text-align: right;">{piezas_nal}</td>
      <td style="text-align: right;">{peso_nal}</td>
      <td style="text-align: right;">{piezas_ext}</td>
      <td style="text-align: right;">{peso_ext}</td>
    </tr>
    <!-- END ROW  -->
  </tbody>
  <tfoot>
    <tr>
      <th colspan="2">Totales</th>
      <th colspan="7"></th>
      <th style="text-align: right;">{total_piezas_nal}</th>
      <th style="text-align: right;">{total_peso_nal}</th>
      <th style="text-align: right;">{total_piezas_ext}</th>
      <th style="text-align: right;">{total_peso_ext}</th>
    </tr>
  </tfoot>
</table>
<form name="frmExcel" action="index_blank.php" >
  <input type="hidden" name="buscarClientefr" id="buscarClientefr" value="{buscarClientefr}" />
  <input type="hidden" name="nitfr" id="nitfr" value="{nitfr}" />
  <input type="hidden" name="fechadesdefr" id="fechadesdefr" value="{fechadesdefr}" />
  <input type="hidden" name="fechahastafr" id="fechahastafr" value="{fechahastafr}" />
  <input type="hidden" name="doasignadofr" id="doasignadofr" value="{doasignadofr}" />
  <input type="hidden" name="tiporechazofr" id="tiporechazofr" value="{tiporechazofr}" />
  <input type="hidden" name="component" id="component" value="acondicionamientos" />
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

  $('a[class="tblank"]').click(function() {
    window.open("index_blank.php?component=acondicionamientos&method=imprimeListadoRechazadas&buscarClientefr="+$("#buscarClientefr").val()+
      "&nitfr="+$("#nitfr").val()+"&fechadesdefr="+$("#fechadesdefr").val()+"&fechahastafr="+$("#fechahastafr").val()+"&doasignadofr="+$("#doasignadofr").val()+
      "&tiporechazofr="+$("#tiporechazofr").val());
    return false;
  });
  
  function Excel() {
    document.frmExcel.submit();
  }
</script>