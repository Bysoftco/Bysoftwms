{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          LISTADO SALIDAS DE MERCANC&Iacute;A
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
      <th>Arribo</th>
      <th>Documento TTE</th>
      <th>FMMI</th>
      <th>Código Referencia</th>
      <th>Referencia</th>
      <th>M/L/C</th>
      <th>Fecha Retiro</th>
      <th>Destino</th>
      <th>Piezas</th>
      <th>Peso</th>
      <th>Piezas Nal/Nzo</th>
      <th>Piezas Ext.</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td>[{doc_cliente}] {nombre_cliente}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">{arribo}</td>
      <td>{doc_transporte}</td>
      <td style="text-align: center;">{fmmi}</td>
      <td style="text-align: center;">{codigo_referencia}</td>
      <td>{nombre_referencia}</td>
      <td>{modelo}</td>
      <td style="text-align: center;">{fecha_retiro}</td>
      <td style="text-align: center;">{destino}</td>
      <td style="text-align: right;">{piezas}</td>
      <td style="text-align: right;">{peso}</td>
      <td style="text-align: right;">{piezas_nal}</td>
      <td style="text-align: right;">{piezas_ext}</td>
    </tr>
    {total_piezas}{total_peso}{total_piezas_nal}{total_piezas_ext}
    <!-- END ROW  -->
  </tbody>
  <tfoot>
    <tr>
      <th colspan="2">Totales</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th style="text-align: right;">{total_piezas}</th>
      <th style="text-align: right;">{total_peso}</th>
      <th style="text-align: right;">{total_piezas_nal}</th>
      <th style="text-align: right;">{total_piezas_ext}</th>
    </tr>
  </tfoot>
</table>
<form name="frmExcel" action="index_blank.php" >
  <input type="hidden" name="buscarClientefsl" id="buscarClientefsl" value="{buscarClientefsl}" />
  <input type="hidden" name="nitfsl" id="nitfsl" value="{nitfsl}" />
  <input type="hidden" name="fechadesdefsl" id="fechadesdefsl" value="{fechadesdefsl}" />
  <input type="hidden" name="fechahastafsl" id="fechahastafsl" value="{fechahastafsl}" />
  <input type="hidden" name="doasignadofsl" id="doasignadofsl" value="{doasignadofsl}" />
  <input type="hidden" name="docttefsl" id="docttefsl" value="{docttefsl}" />
  <input type="hidden" name="component" id="component" value="salidas" />
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
    window.open("index_blank.php?component=salidas&method=imprimeListadoSalidas&buscarClientefsl="+$("#buscarClientefsl").val()+
      "&nitfsl="+$("#nitfsl").val()+"&fechadesdefsl="+$("#fechadesdefsl").val()+"&fechahastafsl="+$("#fechahastafsl").val()+"&doasignadofsl="+$("#doasignadofsl").val()+"&docttefsl="+$("#docttefsl").val());
    return false;
  });
  
  function Excel() {
    document.frmExcel.submit();
  }
</script>