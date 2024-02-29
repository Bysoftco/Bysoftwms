$(document).ready(function(){
	$('.popups2 a').wowwindow({
        draggable: true,
        width:450,
        height:200,
        overlay: {clickToClose: false,
        	      background: '#000000'},
		onclose: function() {$('.formError').remove();}
    }); 
});

$(document).ready(function(){
	$('.popups3 a').wowwindow({
        draggable: true,
        width:300,
        height:300,
        overlay: {clickToClose: false,
        	      background: '#000000'},
		onclose: function() {$('.formError').remove();}
    }); 
});

$(document).ready(function(){
	$('.popups4 a').wowwindow({
        draggable: true,
        width:350,
        height:330,
        overlay: {clickToClose: false,
        	      background: '#000000'},
		onclose: function() {$('.formError').remove();}
    }); 
});

$(document).ready(function(){
	$('.popupsTasas a').wowwindow({
        draggable: true,
        width:400,
        height:350,
        overlay: {clickToClose: false,
        	      background: '#000000'},
		onclose: function() {$('.formError').remove();}
    }); 
});

// Ventana Ordenes Ingresos
$(document).ready(function(){
	$('.popupsFiltro a').wowwindow({
    draggable: true,
    width:600,
    height:300,
    overlay: {
      clickToClose: false,
      background: '#000000'
    },
    onclose: function() {
      $('.formError').remove();
    }
  }); 
});

function llamado_ajax(url){
    
	if(url==''){}
	else{
		logMenu(url);
		$.ajax({
		  url: url,
		  success: function(msm){
			$('.formError').remove();
		    $('#componente_central').html(msm);
		  }
		});
	}
}

function logMenu(url){
	var aux1 = new Array(); 
	aux1 = url.split("?");
	var parametros = aux1[1];
	
	var aux2 = new Array(); 
	aux2 = parametros.split("&");
	
	for(i=0; i<aux2.length; i++){
		var valores = aux2[i].split("=");
		if(valores[0]=='log'){
			$.ajax({
			  url: 'index_blank.php?component=logAplicacion&method=logMenu&accionLog='+valores[1]
			});
		}
	}
}

function llamado_ayuda(url){
	$.ajax({
		  url: 'index_blank.php?component=logAplicacion&method=logMenu&accionLog=Ayuda'
	});
	window.open(url);
}

function llamado_popups(url){
        $('#componente_central').html("")
	if(url==''){}
	else{
		var aux1 = new Array(); 
		aux1 = url.split("?");
		var parametros = aux1[1];
		
		var aux2 = new Array(); 
		aux2 = parametros.split("&");
		
		for(i=0; i<aux2.length; i++){
			var valores = aux2[i].split("=");
			if(valores[0]=='log'){
				$.ajax({
				  url: 'index_blank.php?component=logAplicacion&method=logMenu&accionLog='+valores[1]
				});
			}
		}
		
		$.ajax({
		  url: url,
		  success: function(msm){
			$('#wowwindow-inner').html(msm);
		  }
		});
	}
}

function encabezadoEst(tablaClonar, colocarEncabezado, cantidadFilas){
	var $tbl = $('.'+tablaClonar);
	var $tblhfixed = $tbl.find("tr:lt(" + cantidadFilas + ")");
	var headerelement = "th";
	if ($tblhfixed.find(headerelement).length == 0)
		headerelement = "td";
	
	if ($tblhfixed.find(headerelement).length > 0){
		$tblhfixed.find(headerelement).each(function(){
			var w = $(this).width();
			$(this).css("width", w);
		});
		var $clonedTable = $tbl.clone().empty();
		var tblwidth = GetTblWidth($tbl);
		$clonedTable.css({ "top": "0", "left": $tbl.offset().left }).append($tblhfixed.clone()).hide().appendTo($("body"));
		
		$('.'+colocarEncabezado).html($clonedTable.show());
		$tblhfixed.hide();
	}
}

function GetTblWidth($tbl){
	var tblwidth = $tbl.outerWidth();
	return tblwidth; 
}

function Imprimir(nombre){
  var ficha = document.getElementById(nombre);
  var ventimp = window.open(' ', 'popimpr');
  ventimp.document.write( '<link href="template/css/styles.css" rel="stylesheet" type="text/css" />');
  ventimp.document.write( ficha.innerHTML );
  ventimp.document.close();
  ventimp.print( );
  ventimp.close();
}

function ordenar(orden){
	var orden_actual = $('#orden').attr('value');
	var id_actual = $('#id_orden').attr('value');

	if(orden_actual == orden){
		if(id_actual=='DESC'){
			$('#orden').attr('value','');
			$('#id_orden').attr('value', '');
		}
		else if(id_actual=='ASC'){
			$('#id_orden').attr('value', 'DESC');
		}
	}
	else{
		$('#orden').attr('value', orden);
		$('#id_orden').attr('value', 'ASC');
	}
	filtrar();
}