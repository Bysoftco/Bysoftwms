<?php
require_once(COMPONENTS_PATH.'salidas/model/salidas.php');

class SalidasVista {
  var $template;
  var $datos;
	
  function SalidasVista() {
    $this->template = new HTML_Template_IT(COMPONENTS_PATH);
    $this->datos = new SalidasModelo();
  }
  
	function filtroSalidas($arreglo) {
    $this->template->loadTemplateFile('salidas/views/tmpl/filtroSalidas.php' );
    $this->template->setVariable('COMODIN', '');
    
    // Carga información del Perfil y Usuario
    $arreglo['perfil'] = $_SESSION['datos_logueo']['perfil_id'];
    $arreglo['usuario'] = $_SESSION['datos_logueo']['usuario'];
    // Valida el Perfil para identificar el Tercero
    if($arreglo['perfil'] == 23) {
      $this->template->setVariable('soloLectura', "readonly=''");
      $this->template->setVariable('usuario', $arreglo['usuario']);
      $cliente = $this->datos->findClientet($arreglo['usuario']);
      $this->template->setVariable('cliente', $cliente->razon_social);
    } else {
      $this->template->setVariable('soloLectura', "");
      $this->template->setVariable('usuario', "");
      $this->template->setVariable('cliente', "");
    }
    
    $this->template->show();
	}
  	
  function listadoSalidas($arreglo) {
    $this->template->loadTemplateFile('salidas/views/tmpl/listadoSalidas.php' );
    $this->template->setVariable('COMODIN', '');
    
    //Datos de Filtro para Impresión
    $this->template->setVariable('buscarClientefsl', $arreglo['buscarClientefsl']);
    $this->template->setVariable('nitfsl', $arreglo['nitfsl']);
    $this->template->setVariable('fechadesdefsl', $arreglo['fechadesdefsl']);
    $this->template->setVariable('fechahastafsl', $arreglo['fechahastafsl']);
    $this->template->setVariable('doasignadofsl', $arreglo['doasignadofsl']);
    $this->template->setVariable('docttefsl', $arreglo['docttefsl']);

    $n = 1; $tpiezas = $tpeso = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', $n);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('fmmi', $value['fmm']);
      $this->template->setVariable('arribo', $value['arribo']);
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('modelo', $value['modelo']);
      $this->template->setVariable('fecha_retiro', $value['fecha']);
      $this->template->setVariable('destino', isset($value['destino'])?$value['destino']:'POR ASIGNAR');
      //Cálculo cantidad y peso de retiros o salidas
      //Salidas de Mercancía - Cantidad
      $spiezas_nal = $value['c_ret_nal'] + $value['c_sptr_nal'] + $value['c_prtt_nal'] + $value['c_kit_nal'];
      $spiezas_ext = $value['c_ret_ext'] + $value['c_sptr_ext'] + $value['c_prtt_ext'] + $value['c_kit_ext'];
      $spiezas = $spiezas_nal + $spiezas_ext + $value['c_rpk'];
      //Salidas de Mercancía - Peso
      $speso_nal = $value['p_ret_nal'] + $value['p_sptr_nal'] + $value['p_prtt_nal'] + $value['p_kit_nal'];
      $speso_ext = $value['p_ret_ext'] + $value['p_sptr_ext'] + $value['p_prtt_ext'] + $value['p_kit_ext'];
      $speso = $speso_nal + $speso_ext + $value['p_rpk'];
      $this->template->setVariable('piezas', number_format($spiezas,2));
      $this->template->setVariable('peso', number_format($speso,2));
      $this->template->setVariable('piezas_nal', number_format($spiezas_nal,2));
      $this->template->setVariable('piezas_ext', number_format($spiezas_ext,2));

      //Acumula Totales
      $tpiezas += $spiezas; $tpeso += $speso;
      $tpiezas_nal += $spiezas_nal; $tpiezas_ext += $spiezas_ext; $n++;
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('total_piezas', number_format($tpiezas,2));
    $this->template->setVariable('total_peso', number_format($tpeso,2));
    $this->template->setVariable('total_piezas_nal', number_format($tpiezas_nal,2));
    $this->template->setVariable('total_piezas_ext', number_format($tpiezas_ext,2));
    
    $this->template->show();
  }

  function imprimeListadoSalidas($arreglo) {
    $this->template->loadTemplateFile('salidas/views/tmpl/verListadoSalidas.php' );
    $this->template->setVariable('COMODIN', '');

    $n = 1; $tpiezas = $tpeso = $tpiezas_nal = $tpiezas_ext = 0;
    foreach($arreglo['datos'] as $value) {
      $this->template->setCurrentBlock("ROW");
      $this->template->setVariable('n', $n);
      $this->template->setVariable('doc_cliente', $value['documento']);
      $this->template->setVariable('nombre_cliente', $value['nombre_cliente']);
      $this->template->setVariable('orden', $value['orden']);
      $this->template->setVariable('doc_transporte', $value['doc_tte']);
      $this->template->setVariable('fmmi', $value['fmm']);
      $this->template->setVariable('arribo', $value['arribo']);  
      $this->template->setVariable('codigo_referencia', $value['codigo_ref']);
      $this->template->setVariable('nombre_referencia', $value['nombre_referencia']);
      $this->template->setVariable('modelo', $value['modelo']);
      $this->template->setVariable('fecha_retiro', $value['fecha']);
      $this->template->setVariable('destino', isset($value['destino'])?$value['destino']:'POR ASIGNAR');
      //Cálculo cantidad y peso de retiros o salidas
      //Salidas de Mercancía - Cantidad
      $spiezas_nal = $value['c_ret_nal'] + $value['c_sptr_nal'] + $value['c_prtt_nal'] + $value['c_kit_nal'];
      $spiezas_ext = $value['c_ret_ext'] + $value['c_sptr_ext'] + $value['c_prtt_ext'] + $value['c_kit_ext'];
      $spiezas = $spiezas_nal + $spiezas_ext + $value['c_rpk'];
      //Salidas de Mercancía - Peso
      $speso_nal = $value['p_ret_nal'] + $value['p_sptr_nal'] + $value['p_prtt_nal'] + $value['p_kit_nal'];
      $speso_ext = $value['p_ret_ext'] + $value['p_sptr_ext'] + $value['p_prtt_ext'] + $value['p_kit_ext'];
      $speso = $speso_nal + $speso_ext + $value['p_rpk'];      
      $this->template->setVariable('piezas',number_format($spiezas,2));
      $this->template->setVariable('peso',number_format($speso,2));
      $this->template->setVariable('piezas_nal',number_format($spiezas_nal,2));
      $this->template->setVariable('piezas_ext',number_format($spiezas_ext,2));

      //Acumula Totales
      $tpiezas += $spiezas; $tpeso += $speso;
      $tpiezas_nal += $spiezas_nal; $tpiezas_ext += $spiezas_ext; $n++;
      $this->template->parseCurrentBlock("ROW");
    }
    $this->template->setVariable('total_piezas', number_format($tpiezas,2));
    $this->template->setVariable('total_peso', number_format($tpeso,2));
    $this->template->setVariable('total_piezas_nal', number_format($tpiezas_nal,2));
    $this->template->setVariable('total_piezas_ext', number_format($tpiezas_ext,2));
    
    $this->template->show();
  }
}
?>