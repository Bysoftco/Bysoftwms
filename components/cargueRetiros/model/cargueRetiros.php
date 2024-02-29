<?php
require_once(CLASSES_PATH.'BDControlador.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class cargueRetirosModelo extends BDControlador {

  function cargueRetirosModelo() {
    parent :: Manejador_BD();
  }

  function validarFecha($fechav, $formatov = 'Y-m-d H:i:s') {
    $f = DateTime::createFromFormat($formatov,$fechav);
    return $f && $f->format($formatov) === $fechav;
  }

  function inicializaValidador($validador,$fila) {
    $letras = range('A', 'F');
    for($i = 0;$i <= $fila-1;$i++) {
      foreach($letras as $letra) {
        $validador[$i][$letra] = "";
      }
    }

    return $validador;
  }

  function validadora($consulta,$dba) {
    $resultado = $dba->query($consulta);
    $existe = $dba->fetch($resultado);
    $existe = $existe->dato;

    return $existe;
  }

  function validaDatos($arreglo) {
    $db = $_SESSION['conexion'];

    $hojadecalculo = \PhpOffice\PhpSpreadsheet\IOFactory::load($arreglo['rutaNombreArchivoEntrada']);
    $datos = $hojadecalculo->getActiveSheet()->toArray();

    $validador = array(array()); //Creamos el arreglo vacío
    $validador = $this->inicializaValidador($validador,sizeof($datos));
    $count = "0"; $i = 0; $j = 0;
    //Verificamos Inconsistencias
    foreach($datos as $fila) {
      if($count > 0) {
        $novedad = false;
        $obs = $fila['5'];
        $invEntrada = $fila['0']; //Columna A
        //Revisa contenido código Inventario Entrada
        if(is_null($invEntrada)) { 
          $obs .= "Error falta dato inventario entrada; ";
          $novedad = true;
        } else {
          $invEntrada = (int) $invEntrada; //Convertimos entrada a número
          /*Verificamos existencia del invEntrada en inventario_entradas*/
          $query = "SELECT EXISTS(SELECT * FROM inventario_entradas WHERE codigo=$invEntrada) AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia del inventario entrada
          if(strval($existe)==0) {
            $validador[$j]["F"] .= "Código Inventario de Entrada no existe; ";
            $novedad = true;
          }          
        }
        $fecha = $fila['1']; //Columna B
        //Revisa contenido de fecha y hora
        if(empty($fecha)) { 
          $obs .= "Error falta dato fecha; ";
          $novedad = true;
        } else {
          $fechax = $this->validarFecha($fecha);
          //Registra la Observación
          if(!$fechax) { 
            $obs .= "Error en la fecha debe ser aaaa-mm-dd hh:mm:ss; ";
            $novedad = true;
          }
        }
        $codMaestro = $fila['2']; //Columna C        
        //Revisa contenido de código maestro
        if(is_null($codMaestro)) { 
          $obs .= "Error falta dato código maestro; ";
          $novedad = true;
        } else {
          $codMaestro = (int) $codMaestro; //Convertimos entrada a número
          /*Verificamos existencia de codMaestro en maestro_movimientos*/
          /*Paso 2: Validamos la existencia del código maestro*/
          $query = "SELECT EXISTS(SELECT * FROM inventario_maestro_movimientos WHERE codigo=$codMaestro) AS dato";
          $existe = $this->validadora($query,$db);

          //Valida la existencia del código maestro
          if(strval($existe)==0) {
            $validador[$j]["F"] .= "Código maestro no existe; ";
            $novedad = true;
          }
        }
        $cantinaci = $fila['3']; //Columna D           
        //Revisa contenido cantidad_naci
        if(is_null($cantinaci)) { 
          $obs .= "Error falta dato cantidad nacional; ";
          $novedad = true;
        } elseif(!is_numeric($cantinaci)) {
          $obs .= "Error cantidad nacional debe ser un número; ";
          $novedad = true;
        } elseif($cantinaci<0) {
          $obs .= "Error cantidad nacional no debe ser negativa; ";
          $novedad = true;
        }
        $cantinonac = $fila['4']; //Columna E
        //Revisa contenido de cantidad_nonaci
        if(is_null($cantinonac)) {
          $obs .= "Error falta dato cantidad no nacional; ";
          $novedad = true;
        } elseif(!is_numeric($cantinonac)) {
          $obs .= "Error cantidad no nacional debe ser un número; ";
          $novedad = true;
        } elseif($cantinaci==0 && $cantinonac<=0) {
          $obs .= "Error cantidad no nacional debe ser mayor que 0; ";
          $novedad = true;
        }
        //Validamos que no haya novedad inv_entrada y/o cod_maestro
        if(!$novedad) {
          /*Validamos existencia de cantidad a retirar*/
          $query = "SELECT cantidad FROM inventario_entradas WHERE codigo=$invEntrada";
          $db->query($query);
          $resultado = $db->fetch();
          $cantidad = $resultado->cantidad;
          if($cantidad<($cantinaci+$cantinonac)) {
            $obs .= "Error no es posible retirar esa cantidad del inventario; ";
            $novedad = true;
          }
        }
        $validador[$j]["F"] .= $obs;
        if($novedad) {
          //Guarda la fila con alguna novedad
          $validador[$j]["A"] = $fila['0'];
          $validador[$j]["B"] = $fila['1'];
          $validador[$j]["C"] = $fila['2'];
          $validador[$j]["D"] = $fila['3'];
          $validador[$j]["E"] = $fila['4'];
          $j++; //Contamos la novedad para registro
        }
        $i++;
      } else {
        //Valida estructura de la plantilla Excel
        if($fila['0']=='inventario_entrada' && $fila['1']=='fecha' && $fila['2']=='cod_maestro' && $fila['3']=='cantidad_naci' && $fila['4']=='cantidad_nonac' && $fila['5']=='observacion') {
          $count = "1";          
        } else {
          $validador = "Error en la Estructura de la Plantilla";
          break;
        }
      }
    }
    return $validador;
  }

  function cargarRetiros($arreglo) {
    $db = $_SESSION['conexion'];

    $hojadecalculo = \PhpOffice\PhpSpreadsheet\IOFactory::load($arreglo['rutaNombreArchivoEntrada']);
    $datos = $hojadecalculo->getActiveSheet()->toArray();

    $count = "0";
    //Registros para la carga
    foreach($datos as $fila) {
      if($count > 0) {
        $invEntrada = $fila['0']; //Columna A
        $fecha = $fila['1']; //Columna B
        $codMaestro = $fila['2']; //Columna C
        $cantinaci = $fila['3']; //Columna D
        /*Leemos información para proratear peso y valor*/
        $query = "SELECT cantidad,peso,valor FROM inventario_entradas WHERE codigo=$invEntrada";
        $db->query($query);
        $resultado = $db->fetch();
        $cantidad = $resultado->cantidad;
        $peso = $resultado->peso;
        $valor = $resultado->valor;
        $cantinaci = -1 * $cantinaci;
        //Prorateamos Peso Nacional
        $pesonaci = $cantinaci==0 ? 0 : $peso/$cantidad*$cantinaci;
        //Prorateamos Valor Nacional
        $valornaci = $cantinaci==0 ? 0 : $valor/$cantidad*$cantinaci;
        $cantinonac = $fila['4']; //Columna E
        $cantinonac = -1 * $cantinonac;
        //Prorateamos Peso No Nacional
        $pesononac = $cantinonac==0 ? 0 : $peso/$cantidad*$cantinonac;
        //Prorateamos Valor No Nacional
        $valornonac = $cantinonac==0 ? 0 : $valor/$cantidad*$cantinonac;

        //Crea el registro en el Inventario de Movimientos
        $query = "INSERT INTO inventario_movimientos(inventario_entrada,fecha,tipo_movimiento,cod_declaracion,cod_maestro,num_levante,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,interfaz,fecha_interfaz,estado_mcia,flg_control,fmm,ubicacion) VALUES($invEntrada,'$fecha',3,0,$codMaestro,'1',$pesonaci,$pesononac,$cantinaci,$cantinonac,$valornaci,$valornonac,NULL,'0000-00-00 00:00:00',1,1,NULL,0)";

        $db->query($query);
        $msj = true;     
      } else {
        $count = "1";
      }
    }

    if(isset($msj)) {
      $arreglo['mensaje'] = "Importado"; 
    } else {
      $arreglo['mensaje'] = "No Importado";     
    }
    return $arreglo['mensaje'];
  }
}
?>