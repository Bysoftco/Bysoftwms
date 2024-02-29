{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          LISTADO DE VENCIMIENTOS
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
<div style="padding-top: 43px;"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z" class="display">
  <thead>
    <tr>
      <th>Nit</th>
      <th>Cliente</th>
      <th>Orden</th>
      <th>Documento TTE</th>
      <th>Referencia</th>
      <th>Fecha Ingreso</th>
      <th>&Uacute;ltima Factura</th>
      <th>D&iacute;as Vencidos</th>
      <th>Piezas</th>
      <th>Peso</th>
      <th>Valor</th>
      <th>Piezas Nal.</th>
      <th>Piezas Ext.</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td>{doc_cliente}</td>
      <td>{nombre_cliente}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">{doc_transporte}</td>
      <td>[{codigo_referencia}] {nombre_referencia}</td>
      <td style="text-align: center;">{fecha_ingreso}</td>
      <td style="text-align: center;">{fecha_ufactura}</td>
      <td style="text-align: center;color:red;">{numdias}</td>
      <td style="text-align: right;">{piezas}</td>
      <td style="text-align: right;">{peso}</td>
      <td style="text-align: right;">{valor}</td>
      <td style="text-align: right;">{piezas_nal}</td>
      <td style="text-align: right;">{piezas_ext}</td>
    </tr>
    {total_piezas}{total_peso}{total_valor}
    {total_piezas_nal}{total_piezas_ext}
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
      <th style="text-align: right;">{total_piezas}</th>
      <th style="text-align: right;">{total_peso}</th>
      <th style="text-align: right;">{total_valor}</th>
      <th style="text-align: right;">{total_piezas_nal}</th>
      <th style="text-align: right;">{total_piezas_ext}</th>
    </tr>
  </tfoot>
</table>
<form name="frmExcel" action="index_blank.php" >
  <input type="hidden" name="buscarClientefe" id="buscarClientefe" value="{buscarClientefe}" />
  <input type="hidden" name="nitfe" id="nitfe" value="{nitfe}" />
  <input type="hidden" name="fechadesdefe" id="fechadesdefe" value="{fechadesdefe}" />
  <input type="hidden" name="fechahastafe" id="fechahastafe" value="{fechahastafe}" />
  <input type="hidden" name="doasignadofe" id="doasignadofe" value="{doasignadofe}" />
  <input type="hidden" name="tipoingresofe" id="tipoingresofe" value="{tipoingresofe}" />
  <input type="hidden" name="component" id="component" value="vencimientos" />
  <input type="hidden" name="method" id="method" value="exportarExcel" />
</form>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");

  $(document).ready(function() {
    $('#tabla_general_z').dataTable({
      "iDisplayLength": 20,
      "aLengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]],
      "oLanguage": {
        "sLengthMenu": "Mostrar _MENU_ registros por p&aacute;gina",
        "sZeroRecords": "No hay registros para mostrar",
        "sInfo": "Mostrando _START_ a _END_ registros de _TOTAL_",
        "sInfoEmpty": "Mostrando 0 a 0 registros de 0",
        "sInfoFiltered": "",
        "sSearch": "Buscar:"
      }
    });
  });	

  $('a[class="tblank"]').click(function() {
    window.open("index_blank.php?component=vencimientos&method=imprimeListadoVencimientos&buscarClientefe="+$("#buscarClientefe").val()+
      "&nitfe="+$("#nitfe").val()+"&fechadesdefe="+$("#fechadesdefe").val()+"&fechahastafe="+$("#fechahastafe").val()+"&doasignadofe="+$("#doasignadofe").val());
    return false;
  });
  
  function Excel() {
    document.frmExcel.submit();
  }
</script>