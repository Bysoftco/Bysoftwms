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
<div style="padding-top: 45px;"></div>
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general_z" class="display">
  <thead>
    <tr>
      <th>Ubicaci&oacute;n</th>
      <th>Cliente</th>
      <th>C&oacute;digo Referencia</th>
      <th>Referencia</th>      
      <th>Documento TTE</th>
      <th>Orden</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;padding: 2.5px;">
        <img src="components/ocupacion/views/tmpl/generar.php?ocupacion={nombre_ubicacion}" />
        <input name="codigo{n}" type="hidden" id="codigo{n}" value="{codigo}" />
      </td>
      <td>[{doc_cliente}] {nombre_cliente}</td>
      <td style="text-align: center;">{codigo_referencia}</td>
      <td>{nombre_referencia}</td>
      <td style="text-align: center;">{doc_transporte}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">
        <a href="javascript: Eliminar({n})">
				  <img src="img/acciones/eliminar.png" title="Eliminar {nombre_ubicacion}" width="20" height="20" border="0" />
          <input type="hidden" name="ubicacion{n}" id="ubicacion{n}" value="{nombre_ubicacion}"/>
          <input type="hidden" name="item{n}" id="item{n}" value="{item}"/>
        </a>
      </td>
    </tr>
    <!-- END ROW  -->
  </tbody>
</table>
<input type="hidden" name="nr" id="nr" value="{tr}" />
<input type="hidden" name="nito" id="nito" value="{nito}" />
<input type="hidden" name="doctteo" id="doctteo" value="{doctteo}" />
<input type="hidden" name="doasignadoo" id="doasignadoo" value="{doasignadoo}" />
<input type="hidden" name="ocupaciono" id="ocupaciono" value="{ocupaciono}" />
<input type="hidden" name="referenciao" id="referenciao" value="{referenciao}" />
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
  
  function Eliminar(ide) {
    var ocupacion = $("#ubicacion"+ide).val();
    var confirma = confirm('\u00BF Está seguro de eliminar la ocupación '+ocupacion+' ?');
    // Valida Respuesta
    if(confirma) {
      $.ajax({
        url: 'index_blank.php?component=ocupacion&method=Eliminar',
        type: "POST",
        async: false,
        data: { 
          codigo: $("#codigo"+ide).val(),
          nito: $('#nito').val(),
          doctteo: $('#doctteo').val(),
          doasignadoo: $('#doasignadoo').val(),
          ocupaciono: $('#ocupaciono').val(),
          referenciao: $('#referenciao').val()
        },
        success: function(msm) {
          $('#componente_central').html(msm);
        }
      });
    }
  }
</script>