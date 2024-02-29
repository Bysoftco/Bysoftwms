<?php
require_once(CLASSES_PATH.'BDControlador.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class cargueLevantesModelo extends BDControlador {

  function cargueLevantesModelo() {
    parent :: Manejador_BD();
  }

  function validarFecha($fechav, $formatov = 'Y-m-d H:i:s') {
    $f = DateTime::createFromFormat($formatov,$fechav);
    return $f && $f->format($formatov) === $fechav;
  }

  function inicializaValidador($validador,$fila) {
    $letras = range('A', 'O');
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
        $obs = $fila['14'];
        $invEntrada = $fila['0']; //Columna A
        //Revisa contenido código Inventario Entrada
        if(is_null($invEntrada)) { 
          $obs .= "Error falta dato inventario entrada; ";
          $novedad = true;
        } elseif(!is_numeric($invEntrada)) {
          $obs .= "Error dato inventario entrada debe ser un número; ";
          $novedad = true;
        } else {
          $invEntrada = (int) $invEntrada; //Convertimos entrada a número
          /*Verificamos existencia invEntrada en inventario_entradas*/
          $query = "SELECT EXISTS(SELECT * FROM inventario_entradas WHERE codigo=$invEntrada) AS dato";
          $existe = $this->validadora($query,$db);
          //Valida existencia del inventario entrada
          if(strval($existe)==0) {
            $obs .= "Código Inventario de Entrada no existe; ";
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
        } elseif(!is_numeric($codMaestro)) {
          $obs .= "Error dato código maestro debe ser un número; ";
          $novedad = true;         
        } else {
          $codMaestro = (int) $codMaestro; //Convertimos entrada a número
          /*Verificamos codMaestro en inventario_maestro_movimientos*/
          /*Paso 2: Validamos la existencia del código maestro*/
          $query = "SELECT EXISTS(SELECT * FROM inventario_maestro_movimientos WHERE codigo=$codMaestro) AS dato";
          $existe = $this->validadora($query,$db);
          //Valida la existencia del código maestro
          if(strval($existe)==0) {
            $obs .= "Código maestro no existe; ";
            $novedad = true;
          }
        }
        $num_declaracion = $fila['3']; //Columna D
        if(empty($num_declaracion)) {
          $obs .= "Error falta dato número declaración; ";
          $novedad = true;          
        } else {
          $num_declaracion = (string) $num_declaracion;
          $num_levante = $num_declaracion;
        }
        $tipo_declaracion = $fila['4']; //Columna E           
        //Revisa contenido tipo_declaracion
        if(empty($tipo_declaracion)) { 
          $obs .= "Error falta dato tipo declaración; ";
          $novedad = true;
        } elseif(is_numeric($tipo_declaracion)) {
          $obs .= "Error dato tipo declaración no debe ser un número; ";
          $novedad = true;          
        }
        $subpartida = $fila['5']; //Columna F
        //Revisa contenido subpartida
        if(empty($subpartida)) { 
          $obs .= "Error falta dato subpartida; ";
          $novedad = true;
        } else {
          /*Verificamos existencia subpartida en subpartida*/
          $query = "SELECT EXISTS(SELECT * FROM subpartidas WHERE subpartida='$subpartida') AS dato";
          $existe = $this->validadora($query,$db);
          //Valida existencia de la subpartida
          if(strval($existe)==0) {
            $obs .= "Subpartida no existe; ";
            $novedad = true;
          }
        }
        $modalidad = $fila['6']; //Columna G           
        //Revisa contenido modalidad
        if(empty($modalidad)) { 
          $obs .= "Error falta dato modalidad; ";
          $novedad = true;
        } elseif(is_numeric($modalidad)) {
          $obs .= "Error dato modalidad no debe ser un número; ";
          $novedad = true;          
        }
        $trm = $fila['7']; //Columna H
        //Revisa contenido de trm
        if(is_null($trm)) {
          $obs .= "Error falta dato trm; ";
          $novedad = true;
        } elseif(!is_numeric($trm)) {
          $obs .= "Error trm debe ser un número; ";
          $novedad = true;
        } else {
          $trm = (float) $trm;
        }
        $cantinonac = $fila['8']; //Columna I
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
        $fob = $fila['9']; //Columna J
        //Revisa contenido de fob
        if(is_null($fob)) {
          $obs .= "Error falta dato fob; ";
          $novedad = true;
        } elseif(!is_numeric($fob)) {
          $obs .= "Error fob debe ser un número; ";
          $novedad = true;
        } elseif($fob==0 && $fob<=0) {
          $obs .= "Error fob debe ser mayor que 0; ";
          $novedad = true;
        } else {
          $fob = (float) $fob;
        }        
        $fletes = $fila['10']; //Columna K
        //Revisa contenido de fletes
        if(is_null($fletes)) {
          $obs .= "Error falta dato fletes; ";
          $novedad = true;
        } elseif(!is_numeric($fletes)) {
          $obs .= "Error fletes debe ser un número; ";
          $novedad = true;
        } elseif($fletes==0 && $fletes<=0) {
          $obs .= "Error fletes debe ser mayor que 0; ";
          $novedad = true;
        } else {
          $fletes = (float) $fletes;
        }
        $arancel = $fila['11']; //Columna L
        //Revisa contenido de arancel
        if(is_null($arancel)) {
          $obs .= "Error falta dato arancel; ";
          $novedad = true;
        } elseif(!is_numeric($arancel)) {
          $obs .= "Error arancel debe ser un número; ";
          $novedad = true;
        } elseif($arancel==0 && $arancel<=0) {
          $obs .= "Error arancel debe ser mayor que 0; ";
          $novedad = true;
        }
        $iva = $fila['12']; //Columna M
        //Revisa contenido de arancel
        if(is_null($iva)) {
          $obs .= "Error falta dato iva; ";
          $novedad = true;
        } elseif(!is_numeric($iva)) {
          $obs .= "Error iva debe ser un número; ";
          $novedad = true;
        } elseif($iva==0 && $iva<=0) {
          $obs .= "Error iva debe ser mayor que 0; ";
          $novedad = true;
        }
        $ubicacion = $fila['13']; //Columna N
        //Revisa contenido ubicacion
        if(empty($ubicacion)) { 
          $obs .= "Error falta dato ubicacion; ";
          $novedad = true;
        } else {
          $ubicacion = trim($ubicacion);
          /*Verificamos existencia ubicación en posiciones*/
          $query = "SELECT EXISTS(SELECT * FROM posiciones WHERE nombre='$ubicacion') AS dato";
          $existe = $this->validadora($query,$db);
          //Valida existencia de la ubicacion
          if(strval($existe)==0) {
            $obs .= "ubicacion no existe; ";
            $novedad = true;
          }
        }        
        //Validamos que no haya novedad inv_entrada y/o cod_maestro
        if(!$novedad) {
          /*Validamos existencia de cantidad a nacionalizar*/
          $query = "SELECT cantidad FROM inventario_entradas WHERE codigo=$invEntrada";
          $db->query($query);
          $resultado = $db->fetch();
          $cantidad = $resultado->cantidad;
          if($cantidad<$cantinonac) {
            $obs .= "Error no es posible nacionalizar esa cantidad del inventario; ";
            $novedad = true;
          }
        }
        $validador[$j]["O"] .= $obs;
        if($novedad) {
          //Guarda la fila con alguna novedad
          $validador[$j]["A"] = $fila['0'];
          $validador[$j]["B"] = $fila['1'];
          $validador[$j]["C"] = $fila['2'];
          $validador[$j]["D"] = $fila['3'];
          $validador[$j]["E"] = $fila['4'];
          $validador[$j]["F"] = $fila['5'];
          $validador[$j]["G"] = $fila['6'];
          $validador[$j]["H"] = $fila['7'];
          $validador[$j]["I"] = $fila['8'];
          $validador[$j]["J"] = $fila['9'];
          $validador[$j]["K"] = $fila['10'];
          $validador[$j]["L"] = $fila['11'];
          $validador[$j]["M"] = $fila['12'];
          $validador[$j]["N"] = $fila['13'];
          $j++; //Contamos la novedad para registro
        }
        $i++;
      } else {
        //Valida estructura de la plantilla Excel
        if($fila['0']=='inventario_entrada' && $fila['1']=='fecha' && $fila['2']=='cod_maestro' && $fila['3']=='num_declaracion' && $fila['4']=='tipo_declaracion' && $fila['5']=='subpartida' && $fila['6']=='modalidad' && $fila['7']=='trm' && $fila['8']=='cantidad_nonac' && $fila['9']=='fob' && $fila['10']=='fletes' && $fila['11']=='arancel' && $fila['12']=='iva' && $fila['13']=='ubicación' && $fila['14']=='observacion') {
          $count = "1";          
        } else {
          $validador = "Error en la Estructura de la Plantilla";
          break;
        }
      }
    }
    return $validador;
  }

  function cargarLevantes($arreglo) {
    $db = $_SESSION['conexion'];

    $hojadecalculo = \PhpOffice\PhpSpreadsheet\IOFactory::load($arreglo['rutaNombreArchivoEntrada']);
    $datos = $hojadecalculo->getActiveSheet()->toArray();

    $count = "0";
    //Registros para la carga
    foreach($datos as $fila) {
      if($count > 0) {
        $invEntrada = $fila['0']; //Columna A
        $fecha = $fila['1']; //Columna B
        $fechau = explode(" ",$fecha)[0]; //Extraemos únicamente la Fecha
        $codMaestro = $fila['2']; //Columna C
        /*Extraemos el fmm desde inventario_maestro_movimientos*/
        $query = "SELECT fmm FROM inventario_maestro_movimientos WHERE codigo=$codMaestro";
        $db->query($query);
        $resultado = $db->fetch();
        $fmm = $resultado->fmm;
        $num_declaracion = $fila['3']; //Columna D
        $num_levante = $num_declaracion;
        $tipo_declaracion = $fila['4']; //Columna E
        $subpartida = $fila['5']; //Columna F
        $modalidad = $fila['6']; //Columna G
        $trm = $fila['7']; //Columna H        
        /*Leemos información para proratear peso*/
        $query = "SELECT cantidad,peso FROM inventario_entradas WHERE codigo=$invEntrada";
        $db->query($query);
        $resultado = $db->fetch();
        $cantidad = $resultado->cantidad;
        $peso = $resultado->peso;
        $cantinonac = $fila['8']; //Columna I
        $cantidad_naci = $cantinonac;
        $peso_naci = $peso/$cantidad*$cantinonac;
        $fob = $fila['9']; //Columna J
        $fletes = $fila['10']; //Columna K
        $aduana = $fob + $fletes;
        $cif = $aduana * $trm;
        $arancel = $fila['11']; //Columna L
        $prcarancel = $arancel / 100; //Porcentaje Arancel
        $vrlarancel = $cif * $prcarancel;
        $iva = $fila['12']; //Columna M
        $vrliva = ($cif + $vrlarancel) * $iva / 100;
        $total = $vrlarancel + $vrliva;
        $ubicacion = $fila['13']; //Columna N
        /* Codificamos la ubicación */
        $query = "SELECT codigo FROM posiciones WHERE nombre='$ubicacion'";
        $db->query($query);
        $resultado = $db->fetch();
        $ubicacion = $resultado->codigo;
        //Creamos registro en el Inventario Declaraciones
        $query = "INSERT INTO inventario_declaraciones(cod_maestro,fecha,num_declaracion,num_levante,grupo,tipo_declaracion,subpartida,modalidad,trm,fob,fletes,aduana,arancel,iva,total,obs) VALUES($codMaestro,'$fechau','$num_declaracion','$num_levante',0,'$tipo_declaracion','$subpartida','$modalidad',$trm,$fob,$fletes,$aduana,$arancel,$iva,$total,'')";

        $db->query($query);
        $cod_declaracion = $db->getInsertID();
        //Crea registro tipo_movimiento=2 en Inventario de Movimientos
        $query = "INSERT INTO inventario_movimientos(inventario_entrada,fecha,tipo_movimiento,cod_declaracion,cod_maestro,num_levante,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,interfaz,fecha_interfaz,estado_mcia,flg_control,fmm,ubicacion) VALUES($invEntrada,'$fecha',2,$cod_declaracion,$codMaestro,$num_levante,$peso_naci,0,$cantidad_naci,0,$cif,(-1*$fob),NULL,'0000-00-00 00:00:00',1,1,$fmm,$ubicacion)";

        $db->query($query);
        //Crea registro tipo_movimiento=30 en Inventario de Movimientos
        $query = "INSERT INTO inventario_movimientos(inventario_entrada,fecha,tipo_movimiento,cod_declaracion,cod_maestro,num_levante,peso_naci,peso_nonac,cantidad_naci,cantidad_nonac,cif,fob_nonac,interfaz,fecha_interfaz,estado_mcia,flg_control,fmm,ubicacion) VALUES($invEntrada,'$fecha',30,0,$codMaestro,$num_levante,0,(-1*$peso_naci),0,(-1*$cantidad_naci),0,0,NULL,'0000-00-00 00:00:00',1,1,NULL,0)";

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