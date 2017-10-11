{COMODIN}
<div class="div_barra">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>
          LISTADO KITS
        </td>
      </tr>
    </table>
  </div>
  <div id="contenedor_opciones" align="left">
    <table border="0">
      <tr>
        <td align="center">
          <div class="popupsAgregar borde_circular">
            <a href="">
              <img src="img/acciones/agregar.png" title="Agregar" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
          <div class="popupsVer borde_circular noSeleccion">
            <a href="">
              <img src="img/acciones/ver.png" title="Ver" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
          <div class="popupsEditar borde_circular noSeleccion">
            <a href="">
              <img src="img/acciones/edit.png" title="Editar" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
          <div class="borde_circular noSeleccion" onclick="javascript:eliminar()">
            <a href="javascript:void(0)">
              <img src="img/acciones/eliminar.png" title="Eliminar" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
          <div class="popupsAlistar borde_circular noSeleccion">
            <a href="">
              <img src="img/acciones/camion.png" title="Alistar" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
      </tr>
    </table>
  </div>
</div>
<div style="padding-top: 40px;"></div>
<div id="alerta_accion" style="display: {verAlerta}">{alerta_accion}</div>
<div style="height: 10px"></div>

<span class="font_login">CLIENTE [{documento_cliente}] {nombre_cliente}</span><br /><br />
<table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
  <tr>
    <th colspan="8">KITS</th>
  </tr>
  <tr>
    <th rowspan="2">C&oacute;digo</th>
    <th rowspan="2">Nombre Kit</th>
    <th rowspan="2">Descripci&oacute;n</th>
    <th rowspan="2">Fecha Creaci&oacute;n</th>
    <th colspan="3">Piezas disponibles</th>
    <th width="10px" rowspan="2">Seleccionar</th>
  </tr>
  <tr>
    <th>Nacional</th>
    <th>Extranjera</th>
    <th>Mixta</th>
  </tr>
  <!-- BEGIN ROW -->
  <tr>
    <td>{codigo_kit}</td>
    <td>{nombre_kit}</td>
    <td>{descripcion}</td>
    <td>{fecha_creacion}</td>
    <td align="right">{cantidad_disponible_nal} Kits</td>
    <td align="right">{cantidad_disponible_nonal} Kits</td>
    <td align="right">{cantidad_disponible_mixta} Kits</td>
    <td width="10px" style="text-align:center;">
      <input type="radio" name="seleccion" onclick="javascript:seleccionado('{id}')" />
    </td>
  </tr>
  <!-- END ROW -->
</table>
<input type="hidden" name="doc_cliente" id="doc_cliente" value="{documento_cliente}" />
<input type="hidden" name="noSeleccionado" id="noSeleccionado" />
<script>
  Nifty("div.borde_circular", "transparent");
  Nifty("div.div_barra", "top transparent");
  $('.noSeleccion').css('display', 'none');

  $('.popupsAgregar a').wowwindow({
    draggable: true,
    width: 800,
    overlay: {
      clickToClose: false,
      background: '#000000'
    },
    onclose: function() {
      $('.formError').remove();
    },
    before: function() {
      $.ajax({
        url: 'index_blank.php?component=Kits&method=agregarKit',
        async: true,
        type: "POST",
        data:'docCliente='+$('#doc_cliente').attr('value'),
        success: function(msm) {
          $('#wowwindow-inner').html(msm);
        }
      });
    }
  });
    
  $('.popupsEditar a').wowwindow({
    draggable: true,
    width: 800,
    overlay: {
      clickToClose: false,
      background: '#000000'
    },
    onclose: function() {
      $('.formError').remove();
    },
    before: function() {
      var idKit = $('#noSeleccionado').attr('value');
      $.ajax({
        url: 'index_blank.php?component=Kits&method=agregarKit',
        async: true,
        type: "POST",
        data: 'docCliente='+$('#doc_cliente').attr('value')+'&idKit='+idKit,
        success: function(msm) {
          $('#wowwindow-inner').html(msm);
        }
      });
    }
  });
    
  function seleccionado(documento) {
    $('#noSeleccionado').attr('value', documento);
    $('.noSeleccion').css('display', 'block');
  }
    
  $('.popupsAlistar a').wowwindow({
    draggable: true,
    width: 800,
    overlay: {
      clickToClose: false,
      background: '#000000'
    },
    onclose: function() {
      $('.formError').remove();
    },
    before: function() {
      $.ajax({
        url: 'index_blank.php?component=Kits&method=alistarKit',
        async: true,
        type: "POST",
        data: 'cliente='+$('#doc_cliente').attr('value')+'&idKit='+$('#noSeleccionado').attr('value'),
        success: function(msm) {
          $('#wowwindow-inner').html(msm);
        }
      });
    }
  }); 
    
  $('.popupsVer a').wowwindow({
    draggable: true,
    width: 800,
    overlay: {clickToClose: false,
    background: '#000000'},
    onclose: function() {
      $('.formError').remove();
    },
    before: function() {
      $.ajax({
        url: 'index_blank.php?component=Kits&method=verKit',
        async: true,
        type: "POST",
        data: 'cliente='+$('#doc_cliente').attr('value')+'&idKit='+$('#noSeleccionado').attr('value'),
        success: function(msm) {
          $('#wowwindow-inner').html(msm);
        }
      });
    }
  }); 

  function eliminar() {
    if(confirm("\u00BFRealmente desea eliminar el Kit?")) {
      $.ajax({
        url: 'index_blank.php?component=Kits&method=eliminarKit',
        async: true,
        type: "POST",
        data: 'docCliente='+$('#doc_cliente').attr('value')+'&idKit='+$('#noSeleccionado').attr('value'),
        success: function(msm) {
          $('#componente_central').html(msm);
        }
      });
    }
  }
</script>