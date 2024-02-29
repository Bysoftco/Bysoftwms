<?php
require_once(CLASSES_PATH.'BDControlador.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class cargueInventariosModelo extends BDControlador {
  function cargueInventariosModelo() {
    parent :: Manejador_BD();
  }

  function validarFecha($fechav, $formatov = 'Y-m-d') {
    $f = DateTime::createFromFormat($formatov, $fechav);
    return $f && $f->format('Y-m-d') === $fechav;
  }

  function inicializaValidador($validador,$fila) {
    $letras = range('A', 'P');
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
        $obs = $fila['13']; 
        $arribo = $fila['0']; //Columna A
        //Revisa contenido de arribo
        if(empty($arribo)) { 
          $obs .= "Error falta dato arribo; ";
          $novedad = true;
        }
        $orden = $fila['1']; //Columna B
        //Revisa contenido de orden
        if(empty($orden)) { 
          $obs .= "Error falta dato orden; ";
          $novedad = true;
        }
        $fecha = $fila['2']; //Columna C    
        //Revisa contenido de fecha
        if(empty($fecha)) { 
          $obs .= "Error falta dato fecha expira; ";
          $novedad = true;
        } else {
          $fechax = $this->validarFecha($fecha);
          //Registra la Observación
          if(!$fechax) { 
            $obs .= "Error en la fecha debe ser aaaa-mm-dd; ";
            $novedad = true;
          }
        }
        $referencia = $fila['3']; //Columna D          
        //Revisa contenido de referencia
        if(empty($referencia)) { 
          $obs .= "Error falta dato referencia; ";
          $novedad = true;
        }
        $cantidad = $fila['4']; //Columna E
        //Revisa contenido de cantidad
        if(is_null($cantidad)) {
          $obs .= "Error falta dato cantidad; ";
          $novedad = true;
        } else {
          if($cantidad<=0) {
            $obs .= "Error cantidad debe ser mayor que 0; ";
            $novedad = true;
          }
        }       
        $peso = $fila['5']; //Columna F
        //Revisa contenido de peso
        if(is_null($peso)) {
          $obs .= "Error falta dato peso; ";
          $novedad = true;
        } else {
          if($peso<=0) {
            $obs .= "Error peso debe ser mayor que 0; ";
            $novedad = true;
          }
        } 
        $valor = $fila['6']; //Columna G
        //Revisa contenido de valor
        if(is_null($valor)) {
          $obs .= "Error falta dato valor; ";
          $novedad = true;
        } else {
          if($valor<=0) {
            $obs .= "Error valor debe ser mayor que 0; ";
            $novedad = true;
          }
        }
        $fmm = $fila['7']; //Columna H
        //Revisa contenido de fmm
        if(empty($fmm)) { 
          $obs .= "Error falta dato fmm; ";
          $novedad = true;
        }
        $modelo = $fila['8']; //Columna I
        //Revisa contenido de modelo
        if(empty($modelo) || is_numeric($modelo)) { 
          $obs .= "Error dato modelo; ";
          $novedad = true;
        }
        $embalaje = $fila['9']; //Columna J
        //Revisa contenido de embalaje
        if(empty($embalaje) || is_numeric($embalaje)) { 
          $obs .= "Error dato embalaje; ";
          $novedad = true;
        }
        $un_empaque = $fila['10']; //Columna K
        //Revisa contenido de un_empaque
        if(empty($un_empaque) || is_numeric($un_empaque)) { 
          $obs .= "Error dato un_empaque; ";
          $novedad = true;
        }
        $posicion = $fila['11']; //Columna L
        //Revisa contenido de posicion
        if(empty($posicion) || is_numeric($posicion)) { 
          $obs .= "Error dato posicion; ";
          $novedad = true;
        }
        $cant_declaraciones = $fila['12']; //Columna M
        if(!is_numeric($cant_declaraciones)) { 
          $obs .= "Error dato cant_declaraciones; ";
          $novedad = true;
        }
        $fecha_expira = $fila['14']; //Columna O
        //Revisa contenido de fecha expira
        if(empty($fecha_expira)) { 
          $obs .= "Error falta dato fecha expira; ";
          $novedad = true;
        } else {
          $fechax = $this->validarFecha($fecha_expira);
          //Registra la Observación
          if(!$fechax) { 
            $obs .= "Error en la fecha_expira debe ser aaaa-mm-dd; ";
            $novedad = true;
          }
        }
        $validador[$j]["N"] .= $obs;

        //Verificamos la existencia de los registros de entrada
        if(strlen(trim($arribo))!=0 && strlen(trim($orden))!=0) {
          /*Paso 1: Verificamos la existencia de la orden y la correspondencia con el arribo*/
          $query = "SELECT EXISTS(SELECT * FROM arribos WHERE arribo=$arribo AND orden=$orden) AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia de arribo y orden
          if(strval($existe)==0) {
            $validador[$j]["N"] .= "Orden y/o Arribo no existen; ";
            $novedad = true;
          }
          /*Paso 2: Validamos la existencia del código de referencia*/
          $query = "SELECT EXISTS(SELECT * FROM referencias WHERE codigo_ref='$referencia') AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia de código de referencia
          if(strval($existe)==0) {
            $validador[$j]["N"] .= "Referencia no existe; ";
            $novedad = true;
          } else {
            //Codifica codigo_ref
            $query = "SELECT codigo FROM referencias WHERE codigo_ref='$referencia'";
            $db->query($query);
            $referencia = $db->fetch();
            $ref = $referencia->codigo;
            /*Paso 3: Validamos que el inventario_entrada exista*/
            $query = "SELECT EXISTS(SELECT * FROM inventario_entradas WHERE arribo=$arribo AND orden=$orden AND referencia='$ref') AS dato";
            $existe = $this->validadora($query,$db);

            //Valida existencia del registro en inventario_entradas
            if(strval($existe)!=0) {
              $validador[$j]["N"] .= "Registro ya existe; ";
              $novedad = true;
            }
          }
          /*Paso 4: Validamos la existencia del código de embalaje*/
          $query = "SELECT EXISTS(SELECT * FROM embalajes WHERE cd_embalaje='$embalaje') AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia del código de embalaje
          if(strval($existe)==0) {
            $validador[$j]["N"] .= "Embalaje no existe; ";
            $novedad = true;
          }
          /*Paso 5: Validamos la existencia de la unidad de medida del empaque*/
          $query = "SELECT EXISTS(SELECT * FROM unidades_medida WHERE codigo='$un_empaque') AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia de la unidad de empaque
          if(strval($existe)==0) {
            $validador[$j]["N"] .= "un_empaque no existe; ";
            $novedad = true;
          }
          /*Paso 6: Validamos la existencia de la posición*/
          $query = "SELECT EXISTS(SELECT * FROM posiciones WHERE nombre='$posicion') AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia de la posición
          if(strval($existe)==0) {
            $validador[$j]["N"] .= "posición no existe; ";
            $novedad = true;
          }       
        } else {
          $validador[$j]["N"] .= "Falta el arribo y/o la orden; ";
          $novedad = true;          
        }
        if($novedad) {
          //Guarda la fila con alguna novedad
          $validador[$j]["A"] = $fila['0'];
          $validador[$j]["B"] = $fila['1'];
          $validador[$j]["C"] = $fila['2'];
          $validador[$j]["D"] = $fila['3'];
          $validador[$j]["E"] = $cantidad;
          $validador[$j]["F"] = $peso;
          $validador[$j]["G"] = $valor;
          $validador[$j]["H"] = $fmm;        
          $validador[$j]["I"] = $modelo;
          $validador[$j]["J"] = $fila['9'];
          $validador[$j]["K"] = $fila['10'];
          $validador[$j]["L"] = $fila['11'];
          $validador[$j]["M"] = $cant_declaraciones;
          $validador[$j]["N"] = $validador[$j]["N"];
          $validador[$j]["O"] = $fila['14'];
          $j++; //Contamos la novedad para registro
        }
        $i++;
      } else {
        //Valida estructura de la plantilla Excel
        if($fila['0']=='arribo' && $fila['1']=='orden' && $fila['2']=='fecha' && $fila['3']=='referencia' && $fila['4']=='cantidad' && $fila['5']=='peso' && $fila['6']=='valor' && $fila['7']=='fmm' && $fila['8']=='modelo' && $fila['9']=='embalaje' && $fila['10']=='un_empaque' && $fila['11']=='posicion' && $fila['12']=='cant_declaraciones' && $fila['13']=='observacion' && $fila['14']=='fecha_expira') {
          $count = "1";          
        } else {
          $validador = "Error en la Estructura de la Plantilla";
          break;
        }
      }
    }
    return $validador;
  }
	
  function cargarInventarios($arreglo) {
    $db = $_SESSION['conexion'];

    $hojadecalculo = \PhpOffice\PhpSpreadsheet\IOFactory::load($arreglo['rutaNombreArchivoEntrada']);
    $datos = $hojadecalculo->getActiveSheet()->toArray();
    $count = "0";
    //Registros para la carga
    foreach($datos as $fila) {
      if($count > 0) {
        $arribo = $fila['0']; //Columna A
        $orden = $fila['1']; //Columna B
        $fecha = $fila['2']; //Columna C
        $referencia = $fila['3']; //Columna D
        //Codifica codigo_ref
        $query = "SELECT codigo FROM referencias WHERE codigo_ref='$referencia'";
        $db->query($query);
        $referencia = $db->fetch();
        $ref = $referencia->codigo;        
        $cantidad = $fila['4']; //Columna E
        $peso = $fila['5']; //Columna F
        $valor = $fila['6']; //Columna G
        $fmm = $fila['7']; //Columna H
        $modelo = $fila['8']; //Columna I
        $embalaje = $fila['9']; //Columna J
        //Codifica Embalaje
        $query = "SELECT codigo FROM embalajes WHERE cd_embalaje='$embalaje'";
        $db->query($query);
        $embalaje = $db->fetch();
        $codigo = $embalaje->codigo;
        $embalaje = $codigo; //Asignamos código del embalaje
        $un_empaque = $fila['10']; //Columna K
        //Codifica Unidad de Empaque
        $query = "SELECT id FROM unidades_medida WHERE codigo='$un_empaque'";
        $db->query($query);
        $un_empaque = $db->fetch();
        $idu_empaque = $un_empaque->id;
        $un_empaque = $idu_empaque; //Asignamos id unidad medida del empaque
        $posicion = $fila['11']; //Columna L
        //Codifica Posición
        $query = "SELECT codigo FROM posiciones WHERE nombre='$posicion'";
        $db->query($query);
        $codpos = $db->fetch();
        $codpos = $codpos->codigo;
        $posicion = $codpos; //Asignamos código de la posición
        $cant_declaraciones = $fila['12']; //Columna M
        $observacion = $fila['13']; //Columna N
        $fecha_expira = $fila['14']; //Columna O

        //Elimina Referencia Bultos del Inventario Entradas
        $query = "DELETE FROM inventario_entradas WHERE (arribo=$arribo) AND (orden=$orden) AND (referencia='1')";
        $db->query($query);

        //Crea el registro en el Inventario de Entradas
        $query = "INSERT INTO inventario_entradas(arribo,orden,tipo_mov,fecha,referencia,cantidad,peso,valor,fmm,modelo,embalaje,un_empaque,posicion,cant_declaraciones,observacion,fecha_expira) VALUES($arribo,$orden,0,'$fecha','$ref',$cantidad,$peso,$valor,'$fmm','$modelo',$embalaje,$un_empaque,$posicion,$cant_declaraciones,'$observacion','$fecha_expira')";

        $db->query($query);
        $codigo = $db->getInsertID();

        //Extrae el tipo de operación de la tabla do_asignados
        $query = "SELECT tipo_operacion FROM do_asignados WHERE do_asignado=$orden";
        $db->query($query);
        $operacion = $db->fetch();
        $tipo_operacion = $operacion->tipo_operacion;
        switch($tipo_operacion) {
          case 3:
          case 10:
          case 24:
          case 30:
          case 31:
            $cantidad_naci = $cantidad;
            $peso_naci = $peso;
            $cif = $valor;
            $cantidad_nonac = $peso_nonac = $fob_nonac = 0;
            break;
          default:
            $cantidad_nonac = $cantidad;
            $peso_nonac = $peso;
            $fob_nonac = $valor;
            $cantidad_naci = $peso_naci = $cif = 0;
        }
        //Actualiza el registro creado en inventario_movimientos
        $query = "UPDATE inventario_movimientos
                    SET peso_naci = $peso_naci,
                        peso_nonac = $peso_nonac,
                        cantidad_naci = $cantidad_naci,
                        cantidad_nonac = $cantidad_nonac,
                        cif = $cif,
                        fob_nonac = $fob_nonac WHERE (inventario_entrada = $codigo)";

        $db->query($query);
        //Inserta información de la posición en la tabla referencias_ubicacion
        $query = "INSERT INTO referencias_ubicacion(item,ubicacion,rango,inicio,fin) VALUES($codigo,$posicion,'',$posicion,0);";
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