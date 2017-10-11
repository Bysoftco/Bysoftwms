<style>
  @media print {
    * { -webkit-print-color-adjust: exact; }
  }
  
  @page { margin: 0; }
</style>
{COMODIN}
<div class="div_barra" style="text-align:left;width:670px;">
  <div id="titulo_ruta">
    <table align="right" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td align="right" height="30px" style="padding-left: 20px;">
          Orden: <b>{do_asignadoc}</b>
        </td>
      </tr>
    </table>
  </div>
  <div id="contenedor_opciones" align="left">
    <table border="0">
      <tr>
        <td align="center">
          <div class="popupsAgregar borde_circular">
            <a href="" title="Agregar Costos del DO">
              <img src="img/acciones/agregar.png" title="Agregar" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
          <div class="borde_circular verImprimir" onclick="javascript:imprimirdetalle()">
            <a href="javascript:void(0)">
              <img src="integrado/imagenes/printer.png" title="Imprimir" width="25" height="25" border="0" />
            </a>
          </div>
        </td>
        <td align="center">
					<div class="popupsEditar borde_circular noSeleccion">
						<a href="javascript:void(0)">
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
      </tr>
    </table>
  </div>
</div>
<div id="alerta_accion" style="display: {verAlerta}">{alerta_accion}</div>
<div style="height: 43px"></div>
<div id="infodetallecostos">
  <table class="verCabeza" align="center" style="width:100%;display:block;" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th style="width:210px;"><div id="do_imp"></div></th>
      <th style="width:340px;"><div id="doc_imp"></div></th>
      <th style="width:334px;"><div id="utl_imp"></div></th>
    </tr>
  </table>
  <table align="center" style="width:100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th><div id="total_ingre"></div></th><th><div id="total_gasto"></div></th>
    </tr>
  </table>
  <table align="center" style="width:100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <th style="width:10%;">No.</th>
		  <th style="width:30%;" class="cursor_ordenar" onclick="javascript:ordenar('nomservicio')" title="Ordenar por Servicio">
			 Servicio
			 <span id="spannomservicio" class="noOrden"></span>
		  </th>
		  <th style="width:15%;" class="cursor_ordenar" onclick="javascript:ordenar('fecha')" title="Ordenar por Fecha">
        Fecha
        <span id="spanfecha" class="noOrden"></span>
      </th>
      <th style="width:15%;" class="cursor_ordenar" onclick="javascript:ordenar('ingreso')" title="Ordenar por Ingreso">
        Ingreso
        <span id="spaningreso" class="noOrden"></span>
      </th>
      <th style="width:15%;" class="cursor_ordenar" onclick="javascript:ordenar('gasto')" title="Ordenar por Gasto">
        Gasto
        <span id="spangasto" class="noOrden"></span>
      </th>
      <th style="width:15%;" class="selecc">Seleccionar</th>
    </tr>
    <!-- BEGIN ROW  -->
    <tr id="{id_tr_estilo}">
      <td style="text-align:center;">{numdetallec}</td>
      <td style="text-align:left;">&nbsp;{nomservicio}</td>    
      <td style="text-align:center;">{fecha}</td>
      <td style="text-align:right;">{ingreso}</td>
      <td style="text-align:right;">{gasto}</td>
      <td align="center" class="seleccionar">
        <input type="radio" name="seleccion" onclick="javascript:seleccionado('{numdetallec}')" />
      </td>
    </tr>
    <!-- END ROW  -->
  </table>
  <table class="verLogos" align="center" style="width:100%" cellpadding="0" cellspacing="0" id="tabla_general">
    <tr>
      <td style="width:453px;" align="center">
        <div id="cliente"></div>
      </td>
      <td style="width:454px;" align="center">
        <div id="bysoft"></div>
      </td>
    </tr>
  </table>
</div>
<p><span id="msgbox" style="display:block;" class="{estilo}"> {mensaje} </span></p>
<br />{paginacion}

<input type="hidden" name="muestraImprimir" id="muestraImprimir" value="{muestraImprimir}" />

<form name="form_filtros" id="form_filtros" action="index_blank.php">
	<input type="hidden" name="pagina" id="pagina" value="{pagina}" />
	<input type="hidden" name="orden" id="orden" value="{orden}" />
	<input type="hidden" name="id_orden" id="id_orden" value="{id_orden}" />
	<input type="hidden" name="do_asignado" id="do_asignado" value="{do_asignadoc}" />
	<input type="hidden" name="numdetalle" id="numdetalle" value="{nr}" />
	<input type="hidden" name="totalingreso" id="totalingreso" value="{totalingreso}" />
	<input type="hidden" name="totalgasto" id="totalgasto" value="{totalgasto}" />
  <input type="hidden" name="utilidad" id="utilidad" value="{utilidad}" />
</form>
<script>
	Nifty("div.borde_circular","transparent");
	Nifty("div.div_barra","top transparent");
	$('.noSeleccion').css('display', 'none');
  $('.verImprimir').css('display', $("#muestraImprimir").val());
  $('.verLogos').css('display', 'none');
  $('.verCabeza').css('display', 'none');
	$("div#total_ingre").html("Total Ingresos: $ "+$("#totalingreso").val());
	$("div#total_gasto").html("Total Gastos: $ "+$("#totalgasto").val());
  $("div#do_imp").html("Orden: {do_asignadoc}");
  $("div#doc_imp").html("Documento de Transporte: {doc_ttec}");
  $("div#utl_imp").html("Utilidad: $ "+$("#utilidad").val());
  $("div#cliente").html("<img src='integrado/imagenes/cliente.png' width='60' height='27' style='border:none;' />");
  $("div#bysoft").html("<img src='integrado/imagenes/bysoft.png' width='60' height='27' style='border:none;' />");
  
	ordenadoActual();
	
	function seleccionado(numdetalle) {
		$('#numdetalle').attr('value', numdetalle);
		$('.noSeleccion').css('display', 'block');
	}
	
	function paginar(pagina) {
		$('#pagina').attr('value', pagina);
    filtrar();
	}

	function ordenadoActual() {
		$('.noOrden').html('');
		var orden_actual = $('#orden').attr('value');
		var id_actual = $('#id_orden').attr('value');
		var cSpan = orden_actual.replace('.','');

		if(id_actual == 'ASC') {
			$('#span'+cSpan).html('<span style="font-size: 15px">&uarr;</span>');
		} else if(id_actual == 'DESC') {
			$('#span'+cSpan).html('<span style="font-size: 15px">&darr;</span>');
		} else {
			$('#span'+cSpan).html('');
		}
	}
	
	function buscarCoincidencias() {
		$('#pagina').attr('value', 1);
   	filtrar();
	}
	
	function filtrar() {
		var busqueda = trim($('#campoBuscar').attr('value'));

		$('#buscar').attr('value', busqueda);
		$.ajax({
	  	url: 'index_blank.php?component=dcosteardo&method=listadoDetallec',
	  	data: $('#form_filtros').serialize(),
	  	success: function(msm) {
				$('#vdetallec').html(msm);
	  	}
		});
	}
  
  function imprimirdetalle() {
    $(".selecc").css('display', 'none');
    $(".seleccionar").css('display', 'none');
    $('.verCabeza').css('display', 'block');
    $('.verLogos').css('display', 'block');    
    imprimirDiv('infodetallecostos');
    $(".selecc").css('display', 'block');
    $(".seleccionar").css('display', 'block');
    $('.verCabeza').css('display', 'none');
    $('.verLogos').css('display', 'none');
    return false;
  }
  
	function imprimirDiv(divNombre) {
		var printContents = document.getElementById(divNombre).innerHTML;
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
  
  function number_format(number, decimals, dec_point, thousands_sep) {
    var n = !isFinite(+number) ? 0 : +number, 
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  }
	
	$('.popupsAgregar a').wowwindow({
		draggable: true,
		width: 550,
		height: 250,
		overlay: {
			clickToClose: false,
			background: '#000000'
		},
		onclose: function() {
			$('.formError').remove();
		},
		before: function() {
			var numdetalle = parseInt($("#numdetalle").attr("value")) + 1;
			var do_asignado = $("#do_asignado").attr("value");
                 
			$.ajax({
				url: 'index_blank.php?component=dcosteardo&method=agregarDetallec',
				data: {
					numdetalle: numdetalle,
					do_asignado: do_asignado
				},
				async: true,
				type: "POST",
				success: function(msm) {
					$('#wowwindow-inner').html(msm);
				}
			});
		}
	});
	
	$('.popupsEditar a').wowwindow({
		draggable: true,
		width: 550,
		height: 250,
		overlay: {
			clickToClose: false,
			background: '#000000'
		},
		onclose: function() {
			$('.formError').remove();
		},
		before: function() {
			var numdetalle = $("#numdetalle").attr("value");
			var do_asignado = $("#do_asignado").attr("value");
                 
			$.ajax({
				url: 'index_blank.php?component=dcosteardo&method=editarDetallec',
				data: {
					numdetalle: numdetalle,
					do_asignado: do_asignado
				},
				async: true,
				type: "POST",
				success: function(msm) {
					$('#wowwindow-inner').html(msm);
				}
			});
		}
	});
	
	function eliminar() {
    var numdetalle = $("#numdetalle").attr("value");
    var do_asignado = $("#do_asignado").attr("value");

		if(confirm('\u00bfRealmente desea eliminar el detalle '+numdetalle+'?')) {
			$.ajax({
				url: 'index_blank.php?component=dcosteardo&method=eliminarDetallec',
				async: true,
				type: "POST",
				data: {
          numdetalle: numdetalle,
          do_asignado: do_asignado 
				},
				success: function(msm) {
					$('#vdetallec').html(msm);
				}
			});
		}			
	}
</script>