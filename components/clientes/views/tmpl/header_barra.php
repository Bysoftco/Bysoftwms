{COMODIN}
<div id="tab_aplica">
  <div id="header_info_cliente" style="display: none;">
    <div class="div_barra">
      <div id="titulo_ruta">
        <table align="right" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>
              <a href="javascript:listarClientes()">LISTAR CLIENTES</a> / VISTA CLIENTE
              <div id="nombrar_cliente_1"></div>
            </td>
          </tr>
        </table>
      </div>
    </div><br />
  </div>
  <div id="header_tarifas" style="display: none;">
    <div class="div_barra">
      <div id="titulo_ruta">
        <table align="right" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>
              <a href="javascript:listarClientes()">LISTAR CLIENTES</a> / TARIFAS CLIENTE
              <div id="nombrar_cliente_2"></div>
            </td>
          </tr>
        </table>
      </div>
      <div id="contenedor_opciones" align="left">
        <table border="0">
          <tr>
            <td align="center">
              <div class="popupsAgregarT borde_circular">
                <a href="">
                  <img src="img/acciones/agregar.png" title="Agregar Tarifa" width="25" height="25" border="0" />
                </a>
              </div>
            </td>
            <td align="center">
              <div class="popupsEditarT borde_circular noSeleccion">
                <a href="">
                  <img src="img/acciones/edit.png" title="Editar Tarifa" width="25" height="25" border="0" />
                </a>
              </div>
            </td>
            <td align="center">
              <div class="borde_circular noSeleccion" onclick="javascript:eliminarUsuario()">
                <a href="javascript:void(0)">
                  <img src="img/acciones/eliminar.png" title="Eliminar Tarifa" width="25" height="25" border="0" />
                </a>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div><br />
  </div>
  <div id="header_referencias" style="display: none;">
    <div class="div_barra">
      <div id="titulo_ruta">
        <table align="right" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>
              <a href="javascript:listarClientes()">LISTAR CLIENTES</a> / REFERENCIAS CLIENTE
              <div id="nombrar_cliente_3"></div>
            </td>
          </tr>
        </table>
      </div>
      <div id="contenedor_opciones" align="left">
        <table border="0">
          <tr>
            <td align="center">
              <div class="popupsAgregarR borde_circular">
                <a href="">
                  <img src="img/acciones/agregar.png" title="Agregar Referencia" width="25" height="25" border="0" />
                </a>
              </div>
            </td>
            <td align="center">
              <div class="popupsEditarR borde_circular noSeleccion">
                <a href="">
                  <img src="img/acciones/edit.png" title="Editar Referencia" width="25" height="25" border="0" />
                </a>
              </div>
            </td>
            <td align="center">
              <div class="borde_circular noSeleccion" onclick="javascript:eliminarReferencia()">
                <a href="javascript:void(0)">
                  <img src="img/acciones/eliminar.png" title="Eliminar Referencia" width="25" height="25" border="0" />
                </a>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div><br />
  </div>
  <div id="header_documentos" style="display: none;">
    <div class="div_barra">
      <div id="titulo_ruta">
        <table align="right" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>
              <a href="javascript:listarClientes()">LISTAR CLIENTES</a> / DOCUMENTOS ANEXOS CLIENTE
              <div id="nombrar_cliente_4"></div>
            </td>
          </tr>
        </table>
      </div>
      <div id="contenedor_opciones" align="left">
        <table border="0">
          <tr>
            <td align="center">
              <div class="popupsAgregarDA borde_circular">
                <a href="" title="Cargar Documentos Anexos">
                  <img src="img/acciones/agregar.png" title="Agregar Documentos" width="25" height="25" border="0" />
                </a>
              </div>
            </td>
            <td align="center">
              <div class="borde_circular noSeleccion">
                <a href="" target="_blank" class="tblank">
                  <img src="img/acciones/ver.png" title="Ver Documento" width="25" height="25" border="0" />
                </a>
              </div>
            </td>
            <td align="center">
              <div class="borde_circular noSeleccion" onclick="javascript:eliminarDocumento()">
                <a href="javascript:void(0)">
                  <img src="img/acciones/eliminar.png" title="Eliminar Documento" width="25" height="25" border="0" />
                </a>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div><br />
  </div>
  <script>
    Nifty("div.borde_circular","transparent");
    Nifty("div.div_barra","top transparent");
    $('.noSeleccion').css('display', 'none');

    $('#{mostrar_header}').css('display','block');
  
    $('#nombrar_cliente_1').html($('#razon_social').val());
    $('#nombrar_cliente_2').html($('#razon_social').val());
    $('#nombrar_cliente_3').html($('#razon_social').val());
    $('#nombrar_cliente_4').html($('#razon_social').val());

    $('.popupsAgregarT a').wowwindow({
      draggable: true,
      width: 900,
      overlay: {
        clickToClose: false,
        background: '#000000'},
      onclose: function() {$('.formError').remove();},
      before: function() {
        var numero_documento = $('#numero_documento').attr('value');
        $.ajax({
          url: 'index_blank.php?component=clientes&method=agregarTarifa',
          data: 'numero_documento='+numero_documento,
          async: true,
          type: "POST",
          success: function(msm) {
            $('#wowwindow-inner').html(msm);
          }
        });
      }
    });

    $('.popupsEditarT a').wowwindow({
      draggable: true,
      width: 900,
      overlay: {
        clickToClose: false,
        background: '#000000'},
      onclose: function() {$('.formError').remove();},
      before: function() {
        var numero_documento = $('#numero_documento').attr('value');
        var seleccionado = $('#noSeleccionado').attr('value');
        $.ajax({
          url: 'index_blank.php?component=clientes&method=editarTarifa',
          data: 'numero_documento='+numero_documento+"&tarifa="+seleccionado,
          async: true,
          type: "POST",
          success: function(msm) {
            $('#wowwindow-inner').html(msm);
          }
        });
      }
    });

    $('.popupsAgregarR a').wowwindow({
      draggable: true,
      width: 1100,
      height: 285,
      overlay: {
        clickToClose: false,
        background: '#000000'},
      onclose: function() {$('.formError').remove();},
	    before: function() {
		    var numero_documento = $('#numero_documento').attr('value');
        $.ajax({
          url: 'index_blank.php?component=clientes&method=agregarReferencia',
          data: 'numero_documento='+numero_documento,
          async: true,
          type: "POST",
          success: function(msm) {
            $('#wowwindow-inner').html(msm);
          }
        });
      }
    });

    $('.popupsEditarR a').wowwindow({
      draggable: true,
      width: 1100,
      height: 285,
      overlay: {
        clickToClose: false,
        background: '#000000'},
      onclose: function() {$('.formError').remove();},
      before: function() {
		    var numero_documento = $('#numero_documento').attr('value');
		    var seleccionado = $('#noSeleccionado').attr('value');
		    $.ajax({
          url: 'index_blank.php?component=clientes&method=editarReferencia',
          data: 'numero_documento='+numero_documento+"&referencia="+seleccionado,
          async: true,
          type: "POST",
          success: function(msm) {
            $('#wowwindow-inner').html(msm);
          }
        });
      }
    });
  
    function eliminarReferencia() {
      var numero_documento = $('#numero_documento').attr('value');
      var seleccionado = $('#noSeleccionado').attr('value');
      Eliminar = confirm("¿Está seguro de eliminar la referencia "+seleccionado+"?");
      if(Eliminar) {
        $.ajax({
          url: 'index_blank.php?component=clientes&method=eliminaReferencia',
          data: 'numero_documento='+numero_documento+"&referencia="+seleccionado,
          type: "POST",
          success: function(msm) {
            $("#referenciasCliente").html(msm);
          }
        });
      }  
    }
    
    $('.popupsAgregarDA a').wowwindow({
      draggable: true,
      width: 700,
      height: 150,
      overlay: {
        clickToClose: false,
        background: '#000000'},
      onclose: function() {$('.formError').remove();},
	    before: function() {
		    var numero_documento = $('#numero_documento').attr('value');
        $.ajax({
          url: 'index_blank.php?component=clientes&method=agregarDocumento',
          data: 'numero_documento='+numero_documento,
          async: true,
          type: "POST",
          success: function(msm) {
            $('#wowwindow-inner').html(msm);
          }
        });
      }
    });
    
    $('a[class="tblank"]').click(function() {
      var seleccionado = $('#noSeleccionado').attr('value');
      window.open("integrado/_files/"+$('#numero_documento').attr('value')+"/"+seleccionado);
      return false;
    });

    function eliminarDocumento() {
      var numero_documento = $('#numero_documento').attr('value');
      var seleccionado = $('#noSeleccionado').attr('value');
      Eliminar = confirm("¿Está seguro de eliminar el documento: "+seleccionado+"?");
      if(Eliminar) {
        $.ajax({
          url: 'index_blank.php?component=clientes&method=eliminarDocumento',
          data: {
            documento: numero_documento,
            nombredoc: seleccionado
          },
          type: "POST",
          success: function(msm) {
            $("#documentosCliente").html(msm);
          }
        });
      }  
    }
  </script>
</div>