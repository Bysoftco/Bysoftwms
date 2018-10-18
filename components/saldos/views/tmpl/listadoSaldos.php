{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          LISTADO SALDOS DE MERCANC&Iacute;A
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
      <th>C&oacute;digo Referencia</th>
      <th>Referencia</th>
      <th>U.Comercial</th>
      <th>Fecha Expiración</th>
      <th>Piezas Ing.</th>
      <th>Piezas Nal.</th>
      <th>Piezas Ext.</th>
      <th>Ret Ext</th>
      <th>Ret Nal</th>
      <th>Saldo</th>
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
      <td style="text-align: center;">{ucomercial}</td>
      <td style="text-align: center;">{fecha_expira}</td>
      <td style="text-align: right;">{piezas}</td>
      <td style="text-align: right;">{piezas_nal}</td>
      <td style="text-align: right;">{piezas_ext}</td>
      <td style="text-align: right;">{c_ret_ext}</td>
      <td style="text-align: right;">{c_ret_nal}</td>
      <td style="text-align: right;">{saldo_piezas}</td>
    </tr>
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
      <th style="text-align: right;">{total_piezas}</th>
      <th style="text-align: right;">{total_piezas_nal}</th>
      <th style="text-align: right;">{total_piezas_ext}</th>
      <th style="text-align: right;">&nbsp;</th>
      <th style="text-align: right;">&nbsp;</th>
      <th style="text-align: right;">{total_saldo_piezas}</th>
    </tr>
  </tfoot>
</table>
<form name="frmExcel" action="index_blank.php" >
  <input type="hidden" name="buscarClientefs" id="buscarClientefs" value="{buscarClientefs}" />
  <input type="hidden" name="nitfs" id="nitfs" value="{nitfs}" />
  <input type="hidden" name="fechadesdefs" id="fechadesdefs" value="{fechadesdefs}" />
  <input type="hidden" name="fechahastafs" id="fechahastafs" value="{fechahastafs}" />
  <input type="hidden" name="doasignadofs" id="doasignadofs" value="{doasignadofs}" />
  <input type="hidden" name="component" id="component" value="saldos" />
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

  $('a[class="tblank"]').click(function() {
    window.open("index_blank.php?component=saldos&method=imprimeListadoSaldos&buscarClientefs="+$("#buscarClientefs").val()+
      "&nitfs="+$("#nitfs").val()+"&fechadesdefs="+$("#fechadesdefs").val()+"&fechahastafs="+$("#fechahastafs").val()+"&doasignadofs="+$("#doasignadofs").val()+
      "&tipoingresofs="+$("#tipoingresofs").val());
    return false;
  });
  
  function Excel() {
    document.frmExcel.submit();
  }
</script>