{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="padding-top: 8px;">
          FACTURAS X ENVIAR A FORMATO F.E.
        </td>
      </tr>
    </table>
  </div>
  <div id="contenedor_opciones" align="left">
    <table border="0">
      <tr>
				<td align="center">
					<div id="repExcel" class="borde_circular">
						<a href="javascript: Excel()">
							<img src="img/acciones/repoexcel.gif" title="Exportar a Excel" width="25" height="25" border="0" />
						</a>
					</div>
				</td>
        <td align="center">
          <div id="imprimir" class="borde_circular">
            <a href="" target="_blank" class="tblank">
              <img src="img/acciones/printer.gif" title="Imprimir" width="25" height="25" border="0" />
            </a>
          </div> 
        </td>
				<td align="center">
					<div id="vfactura" class="popupsVer borde_circular noSeleccion">
            <a id="verfactu" href="" title="">				
							<img src="img/acciones/ver.png" title="Ver" width="25" height="25" border="0" />
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
      <th>Fecha</th>
      <th>Factura</th> 
      <th>Nit</th>
      <th>Cliente</th>
      <th>Orden</th>
      <th>Documento</th>
      <th>SubTotal</th>
      <th>IVA</th>
      <th>Total</th>
      <th>Seleccionar</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN ROW  -->
    <tr>
      <td style="text-align: center;">{fecha}</td>
      <td style="text-align: center;">{factura}</td>
      <td style="text-align: center;">{nit}</td>
      <td style="padding-right: 5px;">{cliente}</td>
      <td style="text-align: center;">{orden}</td>
      <td style="text-align: center;">{documento}</td>
      <td style="text-align: right;padding-right: 5px;">{subtotal}</td>
      <td style="text-align: right;padding-right: 5px;">{iva}</td>
      <td style="text-align: right;padding-right: 5px;">{total}</td>
      <td align="center">
        <input type="radio" name="seleccion" onclick="javascript:seleccionado('{factura}')" />
      </td>
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
      <th style="text-align: right;">{total_subtotal}</th>
      <th style="text-align: right;">{total_iva}</th>
      <th style="text-align: right;">{total_valor}</th>
      <th></th>
    </tr>
  </tfoot>
</table>
<form name="frmExcel" action="index_blank.php" >
  <input type="hidden" name="buscarClientefel" id="buscarClientefel" value="{buscarClientefel}" />
  <input type="hidden" name="nitfel" id="nitfe" value="{nitfel}" />
  <input type="hidden" name="fechadesdefel" id="fechadesdefel" value="{fechadesdefel}" />
  <input type="hidden" name="fechahastafel" id="fechahastafel" value="{fechahastafel}" />
  <input type="hidden" name="facturafiltrofel" id="facturafiltrofel" value="{facturafiltrofel}" />
  <input type="hidden" name="dofiltrofel" id="dofiltrofel" value="{dofiltrofel}" />
  <input type="hidden" name="docfiltrofel" id="docfiltrofel" value="{docfiltrofel}" />
  <input type="hidden" name="component" id="component" value="felectronica" />
  <input type="hidden" name="method" id="method" value="exportarExcel" />
  <input type="hidden" name="factura" id="factura" value="0" />
</form>
<script>
  Nifty("div.borde_circular","transparent");
  Nifty("div.div_barra","top transparent");
  $('.noSeleccion').css('display', 'none');
  
  function seleccionado(factura) {
    $('.noSeleccion').css('display', 'block');
    $("#factura").attr("value", factura);
    document.getElementById('verfactu').setAttribute("title", "Ver Factura No. "+$("#factura").val());
  }

  $('.popupsVer a').wowwindow({
    draggable: true,
    width: 900,
    height: 567,
    overlay: {
      clickToClose: false,
    	background: '#000000'
    },
    onclose: function() {
      $('.formError').remove();
    },
    before: function() {
      $.ajax({
        url:'index_blank.php?component=felectronica&method=verFactura',
        async:true,
        type: "POST",
        data:'factura='+$('#factura').attr('value'),
        success: function(msm){
          $('#wowwindow-inner').html(msm);
        }
      });
    }
  });

  $( "#generarxml" ).button({
    text: true,
    icons: {
      primary: "ui-icon-document"
    }
  })
	.click(function() {
    if(confirm("\u00BFRealmente desea Generar documento XML?")) {
      $.ajax({
        url: 'index_blank.php?component=felectronica&method=devolverAcondicionamiento',
        type: "POST",
        data: {
          codigo_operacion: $("#codigo_operacion").attr("value"),
          tipo_mercancia: $("#tipo_mercancia").attr('value'),
          nombre_tipo_mercancia: $('#nombre_tipo_mercancia').val(),
          doc_cliente: $("#doc_cliente").attr("value")
        },
        success: function(msm) {
          alert("Devolución Realizada con Éxito.");
          jQuery(document.body).overlayPlayground('close');void(0);
          $('#componente_central').html(msm);
        }
      });
    }
  });
  
  $( "#ordenac-"+$("#n").val() ).button({
    text: true,
    icons: {
      primary: "ui-icon-document"
    }
  })
	.click(function() {
    window.open("index_blank.php?component=acondicionamientos&method=generarOrdenAcondicionamiento&codigoMaestro="
      +$("#codigoMaestro").attr("value")+"&nombre_tipo_mercancia="+$("#nombre_tipo_mercancia").attr("value")
      +"&codigo_reporte="+$("#codigo_reporte").attr("value"));
    return false;
  });

  //************************************************************************
  
  function verFactura() {
    alert('Visualización de la Factura '+$("#factura").val());
    /*$.ajax({
      url: 'index_blank.php?component=Reporte_do&method=verDetalleDo',
      async: true,
      type: "POST",
      data: 'orden=' + $('#orden').attr('value'),
      success: function(msm) {
        $('#componente_central').html(msm);
      }
    });*/
  }
  
  $(document).ready(function() {
    $('#tabla_general_z').dataTable({
      "aaSorting": [],
      "iDisplayLength": 17,
      "aLengthMenu": [[17, 34, 51, -1], [17, 34, 51, "Todos"]],
      "oLanguage": {
        "sLengthMenu": "Mostrar _MENU_ registros por página",
        "sZeroRecords": "No hay Facturas x Enviar a Formato F.E.",
        "sInfo": "Mostrando _START_ a _END_ registros de _TOTAL_",
        "sInfoEmpty": "Mostrando 0 a 0 registros de 0",
        "sInfoFiltered": "",
        "sSearch": "Buscar:"
      }
    });
  });	

  $('a[class="tblank"]').click(function() {
    window.open("index_blank.php?component=felectronica&method=imprimeListadoFacturas&buscarClientefe="+$("#buscarClientefe").val()+
      "&nitfe="+$("#nitfe").val()+"&fechadesdefe="+$("#fechadesdefe").val()+"&fechahastafe="+$("#fechahastafe").val()+"&doasignadofe="+$("#doasignadofe").val()+
      "&tipoingresofe="+$("#tipoingresofe").val());
    return false;
  });
  
  function Excel() {
    document.frmExcel.submit();
  }
</script>