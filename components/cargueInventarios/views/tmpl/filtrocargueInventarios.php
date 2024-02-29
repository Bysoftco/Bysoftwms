<style>
  body { font-size: 12px; }
  label { display: inline-block; width: 100px; }
  .ui-dialog .ui-state-error { padding: .3em; }
	.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
    text-align: center;
    float: none !important;
	}
  .ui-dialog-title {
    font-size: 12px;
  }
</style>
{COMODIN}
<!-- Acceso a las librerias de Bootstrap 5.3.0 de estilos -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<body>
<div id="wincargueinv" title="Cargue Masivo de Inventarios" style="font-size: 14px;">
  <div class="container-fluid mt-2">
    <div class="row">
      <div class="col-md-12">  
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <p><span><strong>CARGUE DE INVENTARIOS DESDE EXCEL</strong></span></p>
          </div>
          <div class="card-body mt-2">
            <form action="" method="POST" enctype="multipart/form-data" name="archivoSeleccionado">
              <input type="file" name="archivo_importar" class="form-control" />
              <button type="submit" name="cargar_datos_excel" class="btn btn-primary mt-3" id="cargar_datos">Importar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Librerías Javascript de Bootstrap 5.3.0 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script>
	$(function() {
		$( "#wincargueinv" ).dialog({
			autoOpen: false,
			resizable: false,
			height: 260,
			width: 650,
			modal: true,
		}).css("font-size", "12px");

    $('#cargar_datos').click(function(event) {
      event.preventDefault(); //Para que no se recargue la página
      var nombreArchivo = $('input[type=file]').val().split('\\').pop();
      var ext_archivo = nombreArchivo.split('.')[1];
      var ext_permitida = ['xls','csv','xlsx'];

      //Verifica selección de archivo
      if(nombreArchivo != '') {
        //Verifica si la extensión del archivo es válida
        if(in_array(ext_archivo, ext_permitida)) { 
          cargarArchivo(nombreArchivo); //Enviamos archivo para su cargue
        } else {
          alert('** Archivo con extensión no válida **');
          return false;
        } 
      } else {
        alert('** No ha seleccionado el archivo Excel a cargar **');
        return false;
      }
    }); 
	});

  // Muestra la Ventana de Cargue de Inventario
  $( "#wincargueinv" ).dialog( "open" ); 

  function cargarArchivo(nomArchivo) {
    $( "#wincargueinv" ).dialog( "close" );
    //Añadimos la imagen de carga en el contenedor
    $('#componente_central').html('<div class="d-flex justify-content-center align-items-center"><img src="img/bar.gif" alt="cargando" /><br/>&nbsp;&nbsp;Un momento, por favor...</div>');

    /* Creamos el objeto que hará la petición AJAX al servidor. */
    var Req = new XMLHttpRequest();

    /*El objeto FormData nos permite crear un formulario pasándole clave/valor para poder enviarlo, este tipo de objeto ya tiene la propiedad multipart/form-data para poder subir archivos */
    var data = new FormData(document.forms.archivoSeleccionado);

    //Pasándole la url a la que haremos la petición
    Req.open("POST", "index_blank.php?component=cargueInventarios&method=procesarArchivo", true);

    //Tipo de respuesta 
    Req.responseType = 'text';

    Req.onreadystatechange = function() {
      if(Req.readyState == 4 && Req.status == 200) {
        var msm = Req.response;

        if(msm=="Importado") {
          alert('Cargue del INVENTARIO ENTRADAS y MOVIMIENTOS satisfactoriamente');
          location.reload();
        } else {
          $('#componente_central').html(msm);
        }
      }
    }

    //Enviamos la petición 
    Req.send(data);   
  }
</script>
</body>