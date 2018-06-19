{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          NOVEDADES EN ARCHIVO DE EXCEL
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
      <th>Fila</th>
      <th>Arribo</th>
      <th>Orden</th>
      <th>Código Referencia</th>
      <th>Embalaje</th>
      <th>Unidad Empaque</th>
<<<<<<< HEAD
      <th>Posici&oacute;n</th>
=======
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
      <th>Observaciones</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;color:red;">{n}</td>
      <td style="text-align: center;">{arribo}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">{referencia}</td>
      <td style="text-align: center;">{embalaje}</td>
      <td style="text-align: center;">{unimedida}</td>
<<<<<<< HEAD
      <td style="text-align: center;">{posicion}</td>
=======
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
      <td style="text-align: center;">{observacion}</td>
    </tr>
    <!-- END ROW  -->
  </tbody>
</table>
<form name="frmExcel" action="index_blank.php" >
  <input type="hidden" name="buscarClientefe" id="buscarClientefe" value="{buscarClientefe}" />
  <input type="hidden" name="nitfe" id="nitfe" value="{nitfe}" />
  <input type="hidden" name="fechadesdefe" id="fechadesdefe" value="{fechadesdefe}" />
  <input type="hidden" name="fechahastafe" id="fechahastafe" value="{fechahastafe}" />
  <input type="hidden" name="doasignadofe" id="doasignadofe" value="{doasignadofe}" />
  <input type="hidden" name="tipoingresofe" id="tipoingresofe" value="{tipoingresofe}" />
  <input type="hidden" name="component" id="component" value="existencias" />
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
    window.open("index_blank.php?component=existencias&method=imprimeListadoExistencias&buscarClientefe="+$("#buscarClientefe").val()+
      "&nitfe="+$("#nitfe").val()+"&fechadesdefe="+$("#fechadesdefe").val()+"&fechahastafe="+$("#fechahastafe").val()+"&doasignadofe="+$("#doasignadofe").val()+
      "&tipoingresofe="+$("#tipoingresofe").val());
    return false;
  });
  
  function Excel() {
    document.frmExcel.submit();
  }
</script>