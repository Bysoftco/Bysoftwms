<?php

if (!defined('entrada_valida'))
    die('Acceso directo no permitido');
require_once COMPONENTS_PATH . 'clientes/model/clientes.php';
require_once COMPONENTS_PATH . 'clientes/views/vista.php';
require_once COMPONENTS_PATH . 'Entidades/Clienteszona.php';

class clientes {

    var $vista;
    var $datos;

    function clientes() {
        $this->vista = new ClientesVista();
        $this->datos = new ClientesModelo();
    }

    function listadoClientes($arreglo) {
        if (!isset($arreglo['pagina']) || empty($arreglo['pagina'])) {
            $arreglo['pagina'] = 1;
        }
        $arreglo['datos'] = $this->datos->listadoClientes($arreglo);
        $this->vista->listadoClientes($arreglo);
    }

    function agregarCliente($arreglo) {
        $lista_tipodoc = $this->datos->build_list("tipos_documentos", "codigo", "nombre");
        $arreglo['select_tipodoc'] = $this->datos->armSelect($lista_tipodoc, 'Seleccione Tipo Documento...', 'NT');

        $lista_regimen = $this->datos->build_list("regimenes", "codigo", "nombre");
        $arreglo['select_regimen'] = $this->datos->armSelect($lista_regimen, 'Seleccione Regimen...', 2);

        $lista_tipocli = $this->datos->build_list("tipos_cliente", "tipo", "nombre");
        $arreglo['select_tipocli'] = $this->datos->armSelect($lista_tipocli, 'Seleccione Tipo Cliente...', 1);

        $lista_tipofac = array("V" => "Vencido", "A" => "Anticipado");
        $arreglo['select_tipofac'] = $this->datos->armSelect($lista_tipofac, 'Seleccione Tipo Facturación...', 'V');

        $lista_actividades = $this->datos->build_list("actividades_economicas", "codigo", "nombre", " order by nombre ");
        $arreglo['select_actividades'] = $this->datos->armSelect($lista_actividades, 'Seleccione Actividad...');

        $lista_comerciales = $this->datos->build_list("vendedores", "codigo", "nombre");
        $arreglo['select_comercial'] = $this->datos->armSelect($lista_comerciales, 'Seleccione Comercial...', 99);

        $this->vista->agregarCliente($arreglo);
    }

    function editarCliente($arreglo) {
        $datosCliente = $this->datos->datosCliente($arreglo);
        $arreglo['datosCliente'] = $datosCliente[0];
        $arreglo['id'] = $arreglo['datosCliente']['numero_documento'];

        $lista_tipodoc = $this->datos->build_list("tipos_documentos", "codigo", "nombre");
        $arreglo['select_tipodoc'] = $this->datos->armSelect($lista_tipodoc, 'Seleccione Tipo Documento...', $arreglo['datosCliente']['tipo_documento']);

        $lista_regimen = $this->datos->build_list("regimenes", "codigo", "nombre");
        $arreglo['select_regimen'] = $this->datos->armSelect($lista_regimen, 'Seleccione Regimen...', $arreglo['datosCliente']['regimen']);

        $lista_tipocli = $this->datos->build_list("tipos_cliente", "tipo", "nombre");
        $arreglo['select_tipocli'] = $this->datos->armSelect($lista_tipocli, 'Seleccione Tipo Cliente...', $arreglo['datosCliente']['tipo']);

        $lista_tipofac = array("V" => "Vencido", "A" => "Anticipado");
        $arreglo['select_tipofac'] = $this->datos->armSelect($lista_tipofac, 'Seleccione Tipo Facturación...', $arreglo['datosCliente']['tipo_facturacion']);

        $lista_actividades = $this->datos->build_list("actividades_economicas", "codigo", "nombre", " order by nombre ");
        $arreglo['select_actividades'] = $this->datos->armSelect($lista_actividades, 'Seleccione Actividad...', $arreglo['datosCliente']['actividad_economica']);

        $lista_comerciales = $this->datos->build_list("vendedores", "codigo", "nombre");
        $arreglo['select_comercial'] = $this->datos->armSelect($lista_comerciales, 'Seleccione Comercial...', $arreglo['datosCliente']['vendedor']);

        $this->vista->agregarCliente($arreglo);
    }

    function nuevoCliente($arreglo) {
        
        if (isset($arreglo['id']) && $arreglo['id'] != '0') {
            $cliente = new Clienteszona();
            $datosCliente = $cliente->recover($arreglo['id'], "numero_documento");
        }

        $_POST['autoretenedor'] = isset($_POST['autoretenedor']) ? 1 : 0;
        $_POST['cir170'] = isset($_POST['cir170']) ? 1 : 0;
        recuperar_Post($this->datos);
        $this->datos->save($arreglo['id'], 'numero_documento');
        if (isset($arreglo['id']) && $arreglo['id'] != '0') {
            $arreglo['alerta_accion'] = 'Cliente Editado Con &Eacute;xito';
            if ($datosCliente->numero_documento != $arreglo['numero_documento']) {
                $this->editarOrdenes($datosCliente->numero_documento, $arreglo['numero_documento']);
            }
        } else {
            $arreglo['alerta_accion'] = 'Cliente Creado Con &Eacute;xito';
        }
        $this->listadoClientes($arreglo);
    }
    
    function editarOrdenes($documentoAnterior, $nuevoDocumento){
        $this->datos->editarOrdenes($documentoAnterior, $nuevoDocumento);
    }

    function clienteRepetido($arreglo) {
        $yaExiste = $this->datos->clienteRepetido($arreglo);
        if ($yaExiste == 0) {
            print 'valido';
        } else {
            print 'NoValido';
        }
    }

    function verCliente($arreglo) {
        $datosCliente = $this->datos->datosCliente($arreglo);
        $arreglo['datosCliente'] = $datosCliente[0];
        $arreglo['datosTarifas'] = $this->datos->datosTarifas($arreglo);
        foreach ($arreglo['datosTarifas'] as $key => $value) {
            $arreglo['datosTarifas'][$key]['servicios'] = $this->datos->datosTarifasServicios($value['id']);
        }
        $arreglo['datosReferencias'] = $this->datos->datosReferencias($arreglo);
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

        $lista_unidad = $this->datos->build_list("unidades_medida", "codigo", "medida");
        $arreglo['select_unidad'] = $this->datos->armSelect($lista_unidad, 'Seleccione Unidad...', 'U');

        $lista_tipoemb = $this->datos->build_list("tipos_embalaje", "codigo", "nombre");
        $arreglo['select_tipoemb'] = $this->datos->armSelect($lista_tipoemb, 'Seleccione Presentacion Venta...', 0);

        $this->vista->agregarReferencia($arreglo);
    }

    function nuevaReferencia($arreglo) {
        if ($arreglo['accion'] != 1) {
            $arreglo['id_referencia'] = $this->datos->nuevaReferencia($arreglo);
            $arreglo['documento'] = $arreglo['cliente'];
            $arreglo['datosReferencias'] = $this->datos->datosReferencias($arreglo);
        } else {
            $arreglo['id_referencia'] = $this->datos->editarReferencia($arreglo);
            $arreglo['documento'] = $arreglo['cliente'];
            $arreglo['datosReferencias'] = $this->datos->datosReferencias($arreglo);
        }

        $this->vista->verReferencias($arreglo);
    }

    function editarReferencia($arreglo) {
        $arreglo['infoReferencia'] = $this->datos->datosReferenciaCliente($arreglo);

        $lista_tiporef = $this->datos->build_list("tipos_referencias", "codigo", "nombre");
        $arreglo['select_tiporef'] = $this->datos->armSelect($lista_tiporef, 'Seleccione Tipo Referencia...', $arreglo['infoReferencia']->tipo);

        $lista_unidad = $this->datos->build_list("unidades_medida", "codigo", "medida");
        $arreglo['select_unidad'] = $this->datos->armSelect($lista_unidad, 'Seleccione Unidad...', $arreglo['infoReferencia']->unidad_venta);

        $lista_tipoemb = $this->datos->build_list("tipos_embalaje", "codigo", "nombre");
        $arreglo['select_tipoemb'] = $this->datos->armSelect($lista_tipoemb, 'Seleccione Presentacion Venta...', $arreglo['infoReferencia']->presentacion_venta);

        $arreglo['accion'] = 1;

        $this->vista->agregarReferencia($arreglo);
    }

    function eliminaReferencia($arreglo) {
        $this->datos->eliminaReferencia($arreglo);
        $arreglo['documento'] = $arreglo['numero_documento'];
        $arreglo['datosReferencias'] = $this->datos->datosReferencias($arreglo);

        $this->vista->verReferencias($arreglo);
    }

    function retornar_header($arreglo) {
        $this->vista->retornar_header($arreglo['activar']);
    }

    function listadoActividades($arreglo) {
        $actividades = $this->datos->listadoActividades($arreglo['q']);
        header('Content-type: text/html; charset=iso-8859-1');
        foreach ($actividades as $key => $value) {
            echo "[" . $value['codigo'] . "] " . $value['nombre'] . "|" . $value['codigo'] . "\n";
        }

        if (!count($actividades) > 0) {
            echo "No hay actividades para mostrar.";
        }
    }
    
    function eliminarCliente($arreglo){
        $this->datos->eliminarCliente($arreglo['documento']);
        $this->listadoClientes($arreglo);
    }

}

?>