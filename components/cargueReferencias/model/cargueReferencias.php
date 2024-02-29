<?php
require_once(CLASSES_PATH.'BDControlador.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class cargueReferenciasModelo extends BDControlador {
  function cargueReferenciasModelo() {
    parent :: Manejador_BD();
  }

  function validarFecha($fechav, $formatov = 'Y-m-d') {
    $f = DateTime::createFromFormat($formatov, $fechav);
    return $f && $f->format('Y-m-d') === $fechav;
  }

  function inicializaValidador($validador,$fila) {
    $letras = range('A', 'T');
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
        $obs = $fila['3']; //Columna D
        if(is_numeric($obs)) {
          $obs .= " Error dato observaciones;";
          $novedad = true;
        }
        $codigo_ref = $fila['0']; //Columna A
        $validaref = true;     
        //Revisa contenido de codigo_ref
        if($codigo_ref=="") {
          $obs .= " Error falta codigo_ref;";
          $novedad = true;
          $validaref = false;       
        }
        $cliente = $fila['4']; //Columna E  
        //Revisa contenido de cliente
        if($cliente=="") {
          $obs .= " Error falta cliente;";
          $novedad = true;
          $validaref = false;       
        } elseif(!is_numeric($cliente)) {
          $obs .= " Error dato cliente;";
          $novedad = true;
          $validaref = false; 
        } else {
          //Valida existencia de cliente
          $query = "SELECT EXISTS(SELECT * FROM clientes WHERE numero_documento='$cliente') AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia del cliente
          if(strval($existe)==0) {
            $obs .= " Cliente no existe;";
            $novedad = true;
            $validaref = false;
          }
        }
        //Verifica si valida Existencia de Referencia asociada al Cliente
        if($validaref) {
          $query = "SELECT EXISTS(SELECT * FROM referencias WHERE (codigo_ref = '$codigo_ref') AND (cliente = '$cliente')) AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia de la referencia
          if(strval($existe)!=0) {
            $obs .= " Referencia ya existe para este cliente;";
            $novedad = true;
          }          
        }
        $subpartidas = $sbprtds = $fila['1']; //Columna B
        //Revisa contenido de subpartidas
        if($subpartidas=="") {
          $obs .= " Error falta subpartida;";
          $novedad = true;        
        } elseif(is_numeric($subpartidas)) {
          $subpartidas = (string) $sbprtds; //Convertimos subpartida a cadena
        } else {
          $obs .= " Error dato subpartida;";
          $novedad = true;          
        }
        //Valida confiabilidad en la información
        if(!$novedad) {
          //Valida existencia de subpartidas
          $query = "SELECT EXISTS(SELECT * FROM subpartidas WHERE subpartida='$subpartidas') AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia de subpartidas
          if(strval($existe)==0) {
            $obs .= " Subpartida no existe;";
            $novedad = true;
          }        
        }
        $nombre = $fila['2']; //Columna C        
        //Revisa contenido de nombre referencia
        if($nombre=="") {
          $obs .= " Error falta nombre;";
          $novedad = true;        
        } elseif(is_numeric($nombre)) {
          $obs .= " Error dato nombre;";
          $novedad = true;
        }
        $parte_numero = $fila['5']; //Columna F  
        //Revisa contenido de parte_numero
        if($parte_numero=="") {
          $obs .= " Error falta parte_numero;";
          $novedad = true;        
        }
        $unidad = $fila['6']; //Columna G  
        //Revisa contenido de unidad
        if(!is_numeric($unidad)) { 
          $obs .= " Error dato unidad;";
          $novedad = true;
        } else {
          //Valida existencia de la unidad
          $query = "SELECT EXISTS(SELECT * FROM unidades_medida WHERE id=$unidad) AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia de la unidad
          if(strval($existe)==0) {
            $obs .= " Unidad no existe;";
            $novedad = true;
          }                   
        }
        $embalaje = $fila['7']; //Columna H
        //Revisa contenido de embalaje
        if(is_numeric($embalaje)) {
          $obs .= " Error dato embalaje;";
          $novedad = true;
        } else {
          //Valida existencia del código de embalaje
          $query = "SELECT EXISTS(SELECT * FROM embalajes WHERE cd_embalaje='$embalaje') AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia del código de embalaje
          if(strval($existe)==0) {
            $obs .= " Embalaje no existe;";
            $novedad = true;
          }          
        }
        $u_empaque = $fila['8']; //Columna I
        //Revisa contenido de u_empaque
        if($u_empaque=="") {
          $obs .= " Error falta U_empaque;";
          $novedad = true;          
        } elseif(is_numeric($u_empaque)) { 
          $obs .= " Error dato U_empaque;";
          $novedad = true;
        } else {
          //Valida existencia de la unidad de medida del empaque
          $query = "SELECT EXISTS(SELECT * FROM unidades_medida WHERE codigo='$u_empaque') AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia de la unidad de medida del empaque
          if(strval($existe)==0) {
            $obs .= " U_empaque no existe;";
            $novedad = true;
          }          
        }
        $fecha_expira = $fila['9']; //Columna J
        //Revisa contenido de fecha expira
        if(!is_numeric($fecha_expira)) { 
          $obs .= " Error falta dato fecha expira;";
          $novedad = true;
        }
        $vigencia = $fila['10']; //Columna K
        //Revisa contenido de vigencia
        if($vigencia=="") {
          $obs .= " Error falta dato fecha vigencia;";
          $novedad = true;
        } else {
          $fechax = $this->validarFecha($vigencia);
          //Registra la Observación
          if(!$fechax) { 
            $obs .= " Error en la vigencia debe ser aaaa-mm-dd;";
            $novedad = true;
          }
        }  
        $min_stock = $fila['11']; //Columna L
        //Revisa contenido de min_stock
        if(!is_numeric($min_stock)) {
          $obs .= " Error dato min_stock;";
          $novedad = true;
        }
        $lote_cosecha = $fila['12']; //Columna M
        //Revisa contenido de lote_cosecha
        if($lote_cosecha=="") {
          $obs .= " Error falta dato lote_cosecha;";
          $novedad = true;
        }
        $alto = $fila['13']; //Columna N
        //Revisa contenido de alto
        if(!is_numeric($alto)) { 
          $obs .= " Error dato alto;";
          $novedad = true;
        }
        $largo = $fila['14']; //Columna O
        //Revisa contenido de largo
        if(!is_numeric($largo)) {
          $obs .= " Error dato largo;";
          $novedad = true;
        }
        $ancho = $fila['15']; //Columna P
        //Revisa contenido de ancho
        if(!is_numeric($ancho)) { 
          $obs .= " Error dato ancho;";
          $novedad = true;
        }
        $serial = $fila['16']; //Columna Q
        //Revisa contenido de serial
        if(!is_numeric($serial)) {
          $obs .= " Error dato serial;";
          $novedad = true;
        }
        $tipo = $fila['17']; //Columna R
        //Revisa contenido de tipo
        if(!is_numeric($tipo)) {
          $obs .= " Error dato tipo;";
          $novedad = true;
        } else {
          //Valida existencia del tipo de referencia
          $query = "SELECT EXISTS(SELECT * FROM tipos_referencias WHERE codigo=$tipo) AS dato";
          $existe = $this->validadora($query,$db);

          //Valida existencia del tipo de referencia
          if(strval($existe)==0) {
            $obs .= " Tipo de referencia no existe;";
            $novedad = true;
          }             
        }
        $grupo_item = $fila['18']; //Columna S
        //Revisa contenido de grupo_item
        if($grupo_item=="") {
          $obs .= " Error falta dato grupo_item;";
          $novedad = true;
        } else {
          if(!$cliente=="") {
            //Verifica los 4 últimos dígitos del cliente
            if($grupo_item==substr($cliente,-1,4)) {
              $obs .= " Error grupo_item no corresponde a los últimos 4 dígitos del cliente;";
              $novedad = true;
            }
          }
        }
        $factor_conversion = $fila['19']; //Columna T
        //Revisa contenido de factor_conversion
        if(!is_numeric($factor_conversion)) { 
          $obs .= " Error dato factor_conversion;";
          $novedad = true;
        }

        if($novedad) {
          //Guarda la fila con alguna novedad
          $validador[$j]["A"] = $codigo_ref;
          $validador[$j]["B"] = $subpartidas;
          $validador[$j]["C"] = $nombre;
          $validador[$j]["D"] = $obs;
          $validador[$j]["E"] = $cliente;
          $validador[$j]["F"] = $parte_numero;
          $validador[$j]["G"] = $unidad;
          $validador[$j]["H"] = $embalaje;
          $validador[$j]["I"] = $u_empaque;      
          $validador[$j]["J"] = $fecha_expira;
          $validador[$j]["K"] = $vigencia;
          $validador[$j]["L"] = $min_stock;
          $validador[$j]["M"] = $lote_cosecha;
          $validador[$j]["N"] = $alto;
          $validador[$j]["O"] = $largo;
          $validador[$j]["P"] = $ancho;
          $validador[$j]["Q"] = $serial;
          $validador[$j]["R"] = $tipo;
          $validador[$j]["S"] = $grupo_item;          
          $validador[$j]["T"] = $factor_conversion;
          $j++; //Contamos la novedad para registro
        }
        $i++;
      } else {
        //Valida estructura de la plantilla Excel
        if($fila['0']=='codigo_ref' && $fila['1']=='subpartidas' && $fila['2']=='nombre' && $fila['3']=='observaciones' && $fila['4']=='cliente' && $fila['5']=='parte_numero' && $fila['6']=='unidad' && $fila['7']=='Embalaje' && $fila['8']=='U_empaque' && $fila['9']=='fecha_expira' && $fila['10']=='vigencia' && $fila['11']=='min_stock' && $fila['12']=='lote_cosecha' && $fila['13']=='alto' && $fila['14']=='largo' && $fila['15']=='ancho' && $fila['16']=='serial' && $fila['17']=='tipo' && $fila['18']=='grupo_item' && $fila['19']=='factor_conversion') {
          $count = "1";          
        } else {
          $validador = "Error en la Estructura de la Plantilla";
          break;
        }
      }
    }
    return $validador;
  }

  function cargarReferencias($arreglo) {
    $db = $_SESSION['conexion'];

    $hojadecalculo = \PhpOffice\PhpSpreadsheet\IOFactory::load($arreglo['rutaNombreArchivoEntrada']);
    $datos = $hojadecalculo->getActiveSheet()->toArray();

    $count = "0"; 
    //Registros para la carga
    foreach($datos as $fila) {
      if($count > 0) {
        $codigo_ref = $fila['0']; //Columna A
        $subpartidas = $fila['1']; //Columna B
        $nombre = $fila['2']; //Columna C
        $observaciones = $fila['3']; //Columna D
        $cliente = $fila['4']; //Columna E
        $parte_numero = $fila['5']; //Columna F
        $unidad = $fila['6']; //Columna G
        $embalaje = $fila['7']; //Columna H
        //Verificamos la existencia del embalaje
        $query = "SELECT codigo FROM embalajes WHERE cd_embalaje='$embalaje'";
        $db->query($query);
        $embalaje = $db->fetch();
        $idembalaje = $embalaje->codigo;
        $embalaje = $idembalaje; //Asignamos id del embalaje
        $u_empaque = $fila['8']; //Columna I
        //Verificamos la unidad de empaque
        $query = "SELECT id FROM unidades_medida WHERE codigo='$u_empaque'";
        $db->query($query);
        $u_empaque = $db->fetch();
        $idu_empaque = $u_empaque->id;
        $u_empaque = $idu_empaque; //Asignamos id de la unidad medida del empaque
        $fecha_expira = $fila['9']; //Columna J
        $vigencia = $fila['10']; //Columna K
        $min_stock = $fila['11']; //Columna L
        $lote_cosecha = $fila['12']; //Columna M
        $alto = $fila['13']; //Columna N
        $largo = $fila['14']; //Columna O
        $ancho = $fila['15']; //Columna P
        $serial = $fila['16']; //Columna Q
        $tipo = $fila['17']; //Columna R
        $grupo_item = $fila['18']; //Columna S
        $factor_conversion = $fila['19']; //Columna T      
        //Crea el registro en Referencias
        $query = "INSERT INTO referencias(codigo_ref,ref_prove,nombre,observaciones,cliente,parte_numero,unidad,unidad_venta,presentacion_venta,fecha_expira,vigencia,min_stock,lote_cosecha,alto,largo,ancho,serial,tipo,grupo_item,factor_conversion) 
                  VALUES('$codigo_ref','$subpartidas','$nombre','$observaciones','$cliente','$parte_numero',$unidad,$embalaje,'$u_empaque',$fecha_expira,'$vigencia',$min_stock,'$lote_cosecha',$alto,$largo,$ancho,$serial,$tipo,'$grupo_item',$factor_conversion)";
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