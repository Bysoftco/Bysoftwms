<?php
if(!defined('entrada_valida')) die('Acceso directo no permitido');
require_once(COMPONENTS_PATH . 'clientes/model/clientes.php');
require_once(COMPONENTS_PATH . 'clientes/views/vista.php');
require_once(COMPONENTS_PATH . 'Entidades/Clienteszona.php');

class clientes {
  var $vista;
  var $datos;

  function clientes() {
    $this->vista = new ClientesVista();
    $this->datos = new ClientesModelo();
  }

  function listadoClientes($arreglo) {
    if(!isset($arreglo['pagina']) || empty($arreglo['pagina'])) {
      $arreglo['pagina'] = 1;
    }
    $arreglo['datos'] = $this->datos->listadoClientes($arreglo);
    $this->vista->listadoClientes($arreglo);
  }

  function agregarCliente($arreglo) {
    $lista_departamento = $this->datos->build_list("departamentos", "codigo", "nombre"," ORDER BY nombre ");
    $arreglo['select_departamento'] = $this->datos->armSelect($lista_departamento, 'Seleccione Departamento...', 'NT');
	
    $lista_municipio = $this->datos->build_list("ciudades", "codigo", "nombre"," ORDER BY nombre ");
    $arreglo['select_municipio'] = $this->datos->armSelect($lista_municipio, 'Seleccione Ciudad...', 'NT');
    
    $lista_tipodoc = $this->datos->build_list("tipos_documentos", "codigo", "nombre");
    $arreglo['select_tipodoc'] = $this->datos->armSelect($lista_tipodoc, 'Seleccione Tipo Documento...', 'NT');

    $lista_regimen = $this->datos->build_list("regimenes", "codigo", "nombre");
    $arreglo['select_regimen'] = $this->datos->armSelect($lista_regimen, 'Seleccione Regimen...', 2);

    $lista_tipocli = $this->datos->build_list("tipos_cliente", "tipo", "nombre");
    $arreglo['select_tipocli'] = $this->datos->armSelect($lista_tipocli, 'Seleccione Tipo Cliente...', 1);

    $lista_tipofac = array("V" => "Vencido", "A" => "Anticipado");
    $arreglo['select_tipofac'] = $this->datos->armSelect($lista_tipofac, 'Seleccione Tipo Facturación...', 'V');

    $lista_actividades = $this->datos->build_list("actividades_economicas", "codigo", "nombre", " ORDER BY nombre ");
    $arreglo['select_actividades'] = $this->datos->armSelect($lista_actividades, 'Seleccione Actividad...');

    $lista_comerciales = $this->datos->build_list("vendedores", "codigo", "nombre");
    $arreglo['select_comercial'] = $this->datos->armSelect($lista_comerciales, 'Seleccione Comercial...', 99);

    $this->vista->agregarCliente($arreglo);
  }

  function editarCliente($arreglo) {
    $datosCliente = $this->datos->datosCliente($arreglo);
    $arreglo['datosCliente'] = $datosCliente[0];
    $arreglo['id'] = $arreglo['datosCliente']['numero_documento'];

		$lista_departamento = $this->datos->build_list("departamentos", "codigo", "nombre"," ORDER BY nombre ");
    $arreglo['select_departamento'] = $this->datos->armSelect($lista_departamento, 'Seleccione Departamento...', substr($arreglo['datosCliente']['ciudad'],0,2));

    $lista_municipio = $this->datos->build_list("ciudades", "codigo", "nombre"," ORDER BY nombre ");
    $arreglo['select_municipio'] = $this->datos->armSelect($lista_municipio, 'Seleccione Departamento...', $arreglo['datosCliente']['ciudad']);

    $lista_tipodoc = $this->datos->build_list("tipos_documentos", "codigo", "nombre");
    $arreglo['select_tipodoc'] = $this->datos->armSelect($lista_tipodoc, 'Seleccione Tipo Documento...', $arreglo['datosCliente']['tipo_documento']);

    $lista_regimen = $this->datos->build_list("regimenes", "codigo", "nombre");
    $arreglo['select_regimen'] = $this->datos->armSelect($lista_regimen, 'Seleccione Regimen...', $arreglo['datosCliente']['regimen']);

    $lista_tipocli = $this->datos->build_list("tipos_cliente", "tipo", "nombre");
    $arreglo['select_tipocli'] = $this->datos->armSelect($lista_tipocli, 'Seleccione Tipo Cliente...', $arreglo['datosCliente']['tipo']);

    $lista_tipofac = array("V" => "Vencido", "A" => "Anticipado");
    $arreglo['select_tipofac'] = $this->datos->armSelect($lista_tipofac, 'Seleccione Tipo Facturación...', $arreglo['datosCliente']['tipo_facturacion']);

    $lista_actividades = $this->datos->build_list("actividades_economicas", "codigo", "nombre", " ORDER BY nombre ");
    $arreglo['select_actividades'] = $this->datos->armSelect($lista_actividades, 'Seleccione Actividad...', $arreglo['datosCliente']['actividad_economica']);

    $lista_comerciales = $this->datos->build_list("vendedores", "codigo", "nombre");
    $arreglo['select_comercial'] = $this->datos->armSelect($lista_comerciales, 'Seleccione Comercial...', $arreglo['datosCliente']['vendedor']);

    $this->vista->agregarCliente($arreglo);
  }

  function nuevoCliente($arreglo) {
    if(isset($arreglo['id']) && $arreglo['id'] != '0') {
      $cliente = new Clienteszona();
      $datosCliente = $cliente->recover($arreglo['id'], "numero_documento");
    }

    $_POST['autoretenedor'] = isset($_POST['autoretenedor']) ? 1 : 0;
    $_POST['cir170'] = isset($_POST['cir170']) ? 1 : 0;
    $_POST['razon_social'] = strtoupper($_POST['razon_social']);
    recuperar_Post($this->datos);
    $this->datos->save($arreglo['id'], 'numero_documento');
    if(isset($arreglo['id']) && $arreglo['id'] != '0') {
      $arreglo['alerta_accion'] = 'Cliente Editado Con &Eacute;xito';
      if($datosCliente->numero_documento != $arreglo['numero_documento']) {
        $this->editarOrdenes($datosCliente->numero_documento, $arreglo['numero_documento']);
        $this->editReferencias($datosCliente->numero_documento, $arreglo['numero_documento']);
      }
    } else {
      //Creación Grupo Items del Cliente
      $this->newGrupoItems($arreglo);
      //Creación Automática de Referencia
      $arreglo['accion'] = 0; //Valor que permite agregar Referencia
      //Configuración Datos de Referencia
      $arreglo[id_referencia] = '99'; $arreglo[SKU_Proveedor] = '9801900000';
      $arreglo[nombre_referencia] = 'BULTOS O PIEZAS';
      $arreglo[cliente] = $arreglo['numero_documento'];
      $arreglo[embalaje_referencia] = 3; $arreglo[unidad_referencia] = 6;
      $arreglo[presenta_venta] = '45'; $arreglo[vence_referencia] = 0;
      $arreglo[minimo_stock] = 0; $arreglo[alto_referencia] = 1;
      $arreglo[largo_referencia] = 1; $arreglo[ancho_referencia] = 1;
      $arreglo[serial_referencia] = 0; $arreglo[tipo_referencia] = 15;
      $arreglo[grupo_items] = $arreglo['numero_documento']; $arreglo[factor_conversion] = 1;
      $arreglo['automatica'] = 1;
      $this->nuevaReferencia($arreglo);
      $arreglo['alerta_accion'] = 'Cliente Creado Con &Eacute;xito';
    }
    $this->listadoClientes($arreglo);
  }

  function editarOrdenes($documentoAnterior, $nuevoDocumento) {
    $this->datos->editarOrdenes($documentoAnterior, $nuevoDocumento);
  }

  function editReferencias($documentoAnterior, $nuevoDocumento) {
    $this->datos->editReferencias($documentoAnterior, $nuevoDocumento);
  }

  function clienteRepetido($arreglo) {
    $yaExiste = $this->datos->clienteRepetido($arreglo);
    if($yaExiste == 0) {
      print 'valido';
    } else {
      print 'NoValido';
    }
  }

  function verCliente($arreglo) {
    $datosCliente = $this->datos->datosCliente($arreglo);
    $arreglo['datosCliente'] = $datosCliente[0];
    $arreglo['datosTarifas'] = $this->datos->datosTarifas($arreglo);
    foreach($arreglo['datosTarifas'] as $key => $value) {
      $arreglo['datosTarifas'][$key]['servicios'] = $this->datos->datosTarifasServicios($value['id']);
    }
    $arreglo['datosReferencias'] = $this->datos->datosReferencias($arreglo);
    $arreglo['datosDocumentos'] = $this->datos->datosDocumentos($arreglo);
    $this->vista->vistaGeneral($arreglo);
  }

  function agregarTarifa($arreglo) {
    $arr[0]['servicios'] = '';
    $arr[0]['base'] = '';
    $arr[0]['valor_minimo'] = '';
    $arr[0]['tope'] = '';
    $arr[0]['valor'] = '';
    $arr[0]['adicional'] = '';
    $arr[0]['dias'] = '';
    $arr[0]['vigencia'] = '';
    $arreglo['serviciosAtados'] = $arr;
    $this->vista->agregarTarifa($arreglo);
  }

  function agregarServicio($arreglo) {
    $this->vista->nuevoServicio($arreglo, $arreglo['numeral']);
  }

  function nuevaTarifa($arreglo) {
    $arreglo['id_tarifa'] = $this->datos->nuevaTarifa($arreglo);
    foreach ($arreglo['servicio'] as $key => $value) {
      $this->datos->serviciosTarifa($arreglo['id_tarifa'], $value, $arreglo['base'][$key], $arreglo['valor_minimo'][$key], $arreglo['tope'][$key], $arreglo['valor'][$key], $arreglo['adicional'][$key], $arreglo['dias'][$key], $arreglo['vigencia'][$key]);
    }
    $arreglo['documento'] = $arreglo['cliente'];
    $arreglo['datosTarifas'] = $this->datos->datosTarifas($arreglo);
    foreach ($arreglo['datosTarifas'] as $key => $value) {
      $arreglo['datosTarifas'][$key]['servicios'] = $this->datos->datosTarifasServicios($value['id']);
    }
    $this->vista->verTarifas($arreglo);
  }

  function editarTarifa($arreglo) {
    $arreglo['infoTarifa'] = $this->datos->datosTarifaCliente($arreglo);
    $arreglo['serviciosAtados'] = $this->datos->serviciosPorTarifa($arreglo);
    $this->vista->agregarTarifa($arreglo);
  }

  function agregarReferencia($arreglo) {
    $lista_tiporef = $this->datos->build_list("tipos_referencias", "codigo", "nombre");
    $arreglo['select_tiporef'] = $this->datos->armSelect($lista_tiporef, 'Seleccione Tipo Referencia...', 0);

    $lista_unidad = $this->datos->build_list("unidades_medida", "id", "medida");
    $arreglo['select_unidad'] = $this->datos->armSelect($lista_unidad, 'Seleccione Unidad...', 1);

    $lista_tipoemb = $this->datos->build_list("tipos_embalaje", "codigo", "nombre");
    $arreglo['select_tipoemb'] = $this->datos->armSelect($lista_tipoemb, 'Seleccione Presentacion Venta...', 0);
    
    $lista_grupo = $this->datos->build_list("grupo_items", "codigo", "nombre");
    $arreglo['select_grupo'] = $this->datos->armSelect($lista_grupo, 'Seleccione Grupo Items...', 0);


    $this->vista->agregarReferencia($arreglo);
  }

  function nuevaReferencia($arreglo) {
    if($arreglo['accion'] != 1) {
      $arreglo['id_referencia'] = $this->datos->nuevaReferencia($arreglo);
      $arreglo['documento'] = $arreglo['cliente'];
      $arreglo['datosReferencias'] = $this->datos->datosReferencias($arreglo);
    } else {
      $arreglo['id_referencia'] = $this->datos->editarReferencia($arreglo);
      $arreglo['documento'] = $arreglo['cliente'];
      $arreglo['datosReferencias'] = $this->datos->datosReferencias($arreglo);
    }
    
    //Valida Creación Automática Referencia Interfaz Sizfra
    if($arreglo['automatica'] != 1) {
      $this->vista->verReferencias($arreglo);
    }
  }

  function newGrupoItems($arreglo) {
    $this->datos->newGrupoItems($arreglo);
  }

  function editarReferencia($arreglo) {
  
    $arreglo['infoReferencia'] = $this->datos->datosReferenciaCliente($arreglo);

    $lista_tiporef = $this->datos->build_list("tipos_referencias", "codigo", "nombre");
    $arreglo['select_tiporef'] = $this->datos->armSelect($lista_tiporef, 'Seleccione Tipo Referencia...', $arreglo['infoReferencia']->tipo);

    $lista_unidad = $this->datos->build_list("unidades_medida", "id", "medida");
    $arreglo['select_unidad'] = $this->datos->armSelect($lista_unidad, 'Seleccione Unidad...', $arreglo['infoReferencia']->unidad_venta);

    $lista_tipoemb = $this->datos->build_list("tipos_embalaje", "codigo", "nombre");
    $arreglo['select_tipoemb'] = $this->datos->armSelect($lista_tipoemb, 'Seleccione Presentacion Venta...', $arreglo['infoReferencia']->presentacion_venta);
    
    $lista_grupo = $this->datos->build_list("grupo_items", "codigo", "nombre");
    $arreglo['select_grupo'] = $this->datos->armSelect($lista_grupo, 'Seleccione Grupo Items...', $arreglo['infoReferencia']->grupo_item);

    $arreglo['accion'] = 1;

    $this->vista->agregarReferencia($arreglo);
  }

  function eliminaReferencia($arreglo) {
    $this->datos->eliminaReferencia($arreglo);
    $arreglo['documento'] = $arreglo['numero_documento'];
    $arreglo['datosReferencias'] = $this->datos->datosReferencias($arreglo);

    $this->vista->verReferencias($arreglo);
  }

  function agregarDocumento($arreglo) {
    $this->vista->agregarDocumento($arreglo);
  }
  
  function eliminarDocumento($arreglo) {
    $this->datos->eliminarDocumento($arreglo);
    $this->cargarDocumentos($arreglo);
    }
  
  function retornar_header($arreglo) {
    $this->vista->retornar_header($arreglo['activar']);
  }

  function listadoActividades($arreglo) {
    $actividades = $this->datos->listadoActividades($arreglo['q']);
    header('Content-type: text/html; charset=iso-8859-1');
    foreach($actividades as $key => $value) {
      echo "[" . $value['codigo'] . "] " . $value['nombre'] . "|" . $value['codigo'] . "\n";
    }

    if(!count($actividades) > 0) {
      echo "No hay actividades para mostrar.";
    }
  }
    
  function eliminarCliente($arreglo) {
    $this->datos->eliminarCliente($arreglo['documento']);
    $this->listadoClientes($arreglo);
  }
  
  function cargarArchivos($arreglo) {
    $ruta = "integrado/_files/";
    //Verificamos si existe el Directorio
    if(!file_exists($ruta.$arreglo[numdoc])) { mkdir($ruta.$arreglo[numdoc]); }
    $ruta .= $arreglo[numdoc]."/";
		foreach ($_FILES as $key) {
    	if($key['error'] == UPLOAD_ERR_OK ) {//Verificamos si se subió correctamente
        $nombre = $key['name']; //Definimos el nombre del archivo en el servidor
      	$temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      	move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada
        $arreglo[archivo] = $nombre;
        $this->datos->cargarDocumentos($arreglo);
			} else {
				echo $key['error']; //Si no se cargo mostramos el error
        $nombre = "Error";
			}
		}
    echo $ruta . $nombre;
  }
  
  function cargarDocumentos($arreglo) {
    $arreglo['datosDocumentos'] = $this->datos->datosDocumentos($arreglo);
		$this->vista->verDocumentos($arreglo);
	}
}
?>