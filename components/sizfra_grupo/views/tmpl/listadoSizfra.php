{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          GENERAR INTERFAZ SIZFRA Z.F.
        </td>
      </tr>
    </table>
  </div>
  <div id="contenedor_opciones" align="left">
    <table border="0">
      <tr>
				<td align="center">
					<div class="borde_circular">
						<a href="javascript: vGenerar()">
							<img src="img/acciones/ver.png" title="Ventana de Generaci�n" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
        <td align="center">
          <div class="borde_circular">
            <a href="integrado/_files/{nombreinterfaz}.txt" target="_blank">
              <img src="img/acciones/interface.gif" title="Ver Archivo Interfaz" width="25" height="25" border="0" />
            </a>
          </div> 
        </td>
				<td align="center">
					<div class="borde_circular">
						<a href="javascript: Enviar_adjunto()">
							<img src="img/acciones/adjunto.png" title="Enviar Archivo Adjunto" width="25" height="25" border="0" />
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
      <th>CSC</th>
      <th>Codigo</th>
      <th>Nombre</th>      
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;">{csc}</td>
      <td style="text-align: center;">{codigo}</td>
      <td style="text-align: center;">{nombre}</td>      
    </tr>
    <!-- END ROW  -->
  </tbody>
</table>
<input type="hidden" name="adjunto" id="adjunto" value="{nombreinterfaz}.txt" />
<input type="hidden" name="destino"  id="destino" value="{emaildestino}" />
<input type="hidden" name="mensaje"  id="mensaje" value="ADJUNTO INTERFAZ GRUPOS SIZFRA GENERADA - Sistema Logistico y Aduanero - BYSOFT" />
<input type="hidden" name="asunto"  id="asunto" value="Interfaz GRUPOS SIZFRA Zona Franca" />
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");

  $(document).ready(function() {
    $('#tabla_general_z').dataTable({
      "aaSorting": [],
      "iDisplayLength": 20,
      "aLengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]],
      "oLanguage": {
        "sLengthMenu": "Mostrar _MENU_ registros por p�gina",
        "sZeroRecords": "No hay registros para mostrar",
        "sInfo": "Mostrando _START_ a _END_ registros de _TOTAL_",
        "sInfoEmpty": "Mostrando 0 a 0 registros de 0",
        "sInfoFiltered": "",
        "sSearch": "Buscar:"
      }
    });
  });	

  function vGenerar() {
    $.ajax({
      url: 'index_blank.php?component=sizfra&method=filtroSizfra',
      type: "POST",
      async: false,
      success: function(msm) {
        $("#winfiltrois").dialog("close");
        $('#componente_central').html(msm);
      }
    });
  }
  
  function Enviar_adjunto() {
    $.ajax({
      url: 'index_blank.php?component=sizfra&method=enviarAdjunto',
      type: "POST",
      data: {
        destino: $("#destino").val(),
        adjunto: $("#adjunto").val(),
				mensaje: $("#mensaje").val()
			},
      async: false,
      success: function(msm) {
        //$("#winfiltrois").dialog("close");
        $('#componente_central').html(msm);
      }
    });
  }
</script>