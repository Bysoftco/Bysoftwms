<style>
  body { font-size: 62.5%; }
  label { display: inline-block; width: 100px; }
  legend { padding: 0.5em; }
  fieldset fieldset label { display: block; }
  #frmfiltroci label { width: 110px; margin-left: 10px;} /*ancho de las etiquetas de los campos*/
  h1 { font-size: 1.2em; margin: .6em 0; }
  div#users-contain { width: 100%; margin: 5px 0; margin-left: 0%; }
  div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
  div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
  .ui-dialog .ui-state-error { padding: .3em; }
  tbody tr.odd th,tbody tr.odd td {border-color:#EBE5D9;background:#F7F4EE;}
  tbody tr:hover td,tbody tr:hover th {background:#EAECEE;border-color:#523A0B;}
	.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
    text-align: center;
    float: none !important;
	}
</style>
{COMODIN}
<div id="winfiltroci" title="Cargue Masivo de Inventarios">
  <div id="frmfiltroci">
    <p id="msgfiltroci">Seleccione el archivo en Excel que desea cargar.</p>
    <form name="frmcargueInventarios" id="frmcargueInventarios" method="post" action="">
      <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all">
          <div id="filtrocargueInventarios">Selección y Carga del Archivo</div>
        </legend>  
        <div class="margenes">
          <table align="center" width="100%" cellpadding="0" cellspacing="0" id="tabla_general">
            <tr>
              <th colspan="2">CARGUE DE INVENTARIOS DESDE EXCEL</th>
            </tr>
            <tr>
              <td width="30%">Proceso de Selecci&oacute;n
                <img src="img/Flecha.png" style="padding:0px 0px 0px 7px;" title="Seleccionar" width="15" height="15" border="0" />
              </td>
              <td>
                <input type="file" name="file" id="file" />
              </td>
            </tr>
          </table>
        </div>
      </fieldset>
    </form>
  </div>
</div>
<script>
	$(function() {
		$( "#winfiltroci" ).dialog({
			autoOpen: false,
			resizable: false,
			height: 250,
			width: 650,
			modal: true,
			buttons: {
				"Enviar": function() {
		      var archivos = document.getElementById("file");//Damos el valor del input tipo file
		      var archivo = archivos.files; //Obtenemos el valor del input (el archivo) en modo de arreglo

				  //Valida el formato del archivo a cargar
          if(archivo[0].name.slice(-4)=='.xls'||archivo[0].name.slice(-5)=='.xlsx') {
            $( "#winfiltroci" ).dialog( "close" );
            seleccionar(archivo);
          } else alert('Error, debe cargar un archivo con extensión .xls o .xlsx');
				}
			},
		});
	});
	
	function procesarPlantilla(nomfile) {
    var ruta = "integrado/_files/";
    var nombrefile = ruta+nomfile;

		$.ajax({
			url: 'index_blank.php?component=cargueInventarios&method=procesarPlantilla',
			data: { nombrefile: nombrefile },
			async: true,
			type: "POST",
			success: function(msm) {
				jQuery(document.body).overlayPlayground('close');void(0);
				$('#componente_central').html(msm);
			}
		});
	}
	
	function seleccionar(archivo) {
    /* Creamos el objeto que hara la petición AJAX al servidor, debemos de validar 
       si existe el objeto “ XMLHttpRequest” ya que en internet explorer viejito no esta,
       y si no esta usamos “ActiveXObject” */ 
    if(window.XMLHttpRequest) {
      var Req = new XMLHttpRequest(); 
    } else if(window.ActiveXObject) { 
      var Req = new ActiveXObject("Microsoft.XMLHTTP"); 
    }

    //El objeto FormData nos permite crear un formulario pasandole clave/valor para poder enviarlo, 
    //este tipo de objeto ya tiene la propiedad multipart/form-data para poder subir archivos
    var data = new FormData();

    data.append('archivo',archivo[0]);

    //Pasándole la url a la que haremos la petición
    Req.open("POST", "index_blank.php?component=cargueInventarios&method=cargarPlantilla", true);

    /* Le damos un evento al request, esto quiere decir que cuando termine de hacer la petición,
       se ejecutara este fragmento de código */ 

    Req.onload = function(Event) {
      //Validamos que el status http sea Ok 
      if(Req.status == 200) {
        //Recibimos la respuesta de php
        var msg = Req.responseText;
                 
        //Valida Carga Exitosa de la Plantilla
        if(msg == archivo[0]['name']) {
          //Carga Datos a MySQL
          procesarPlantilla(msg);
        } else { alert(msg); } // Error al Cargar la Plantilla
      } else {
        alert("No se recibe una respuesta");
        console.log(Req.status); //Vemos que paso. 
      } 
    };

    //Enviamos la petición 
    Req.send(data);
  }

  // Muestra la Ventana de Filtro de Existencias
  $( "#winfiltroci" ).dialog( "open" );
</script>