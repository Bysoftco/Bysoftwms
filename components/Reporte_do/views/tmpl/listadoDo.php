{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          LISTADO DE ORDENES
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
          <div class="borde_circular noSeleccion">
            <a href="javascript:verDo()">
              <img src="img/acciones/ver.png" title="Ver" width="25" height="25" border="0" />
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
      <th>Orden</th>
      <th>Documento Transporte</th>
      <th>Cliente</th>
      <th>Modelo/Lote/Cosecha</th>
      <th>Controles</th>
      <th>Piezas Iniciales</th>
      <th>Peso Inicial</th>
      <th style="width:10px;">Seleccionar</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">{doc_transporte}</td>
      <td>[{doc_cliente}] {nombre_cliente}</td>      
      <td style="text-align: center;">{modelo}</td>
      <td>{controles}</td>
      <td style="text-align: right;">{piezas}</td>
      <td style="text-align: right;">{peso}</td>
      <td align="center">
        <input type="radio" name="seleccion" onclick="javascript:seleccionado('{orden}')" />
      </td>
    </tr>
    <!-- END ROW  -->
  </tbody>
</table>
<form name="frmExcel" action="index_blank.php" >
  <input type="hidden" name="docCliente" id="docCliente" value="{docCliente}" />
  <input type="hidden" name="do" id="do" value="{do}" />
  <input type="hidden" name="doc_transporte" id="doc_transporte" value="{doc_transporte}" />
  <input type="hidden" name="modelo" id="modelo" value="{modelo}" />
  <input type="hidden" name="fecha_desde" id="fecha_desde" value="{fecha_desde}" />
  <input type="hidden" name="fecha_hasta" id="fecha_hasta" value="{fecha_hasta}" />
  <input type="hidden" name="component" id="component" value="Reporte_do" />
  <input type="hidden" name="method" id="method" value="exportarExcel" />
</form>
<input type="hidden" name="orden" id="orden" />
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  $('.noSeleccion').css('display', 'none');

  function seleccionado(orden) {
    $('.noSeleccion').css('display', 'block');
    $("#orden").attr("value", orden);
  }
  
  function verDo() {
    $.ajax({
      url: 'index_blank.php?component=Reporte_do&method=verDetalleDo',
      async: true,
      type: "POST",
      data: 'orden=' + $('#orden').attr('value'),
      success: function(msm) {
        $('#componente_central').html(msm);
      }
    });
  }

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

  function Excel() {
    document.frmExcel.submit();
  }
</script>