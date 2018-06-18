<?php
require_once(CLASSES_PATH.'BDControlador.php');

class cargueInventariosModelo extends BDControlador {
  function cargueInventariosModelo() {
    parent :: Manejador_BD();
  }

  function validaDatos($arreglo) {
    $db = $_SESSION['conexion'];
    
    try {
      $objPHPExcel = PHPExcel_IOFactory::load($arreglo[nombrefile]);
    } catch(Exception $e) {
      die('Error cargando la plantilla "'.pathinfo($arreglo[nombrefile],PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    //Asignar hoja de Excel activa
    $objPHPExcel->setActiveSheetIndex(0);
    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
    $arrayCount = count($allDataInSheet); //Cuenta el total de filas en la hoja de Excel
    $validador = $allDataInSheet; //Asigno info de Array en el arreglo validador

    //Verifica Inconsistencias
    for($i = 2; $i <= $arrayCount; $i++) {
      $arribo = $allDataInSheet[$i]["A"];
      $orden = $allDataInSheet[$i]["B"];
      $referencia = $allDataInSheet[$i]["E"];
      $cantidad = $allDataInSheet[$i]["F"];
      $peso = $allDataInSheet[$i]["G"];
      $valor = $allDataInSheet[$i]["H"];
      $fmm = $allDataInSheet[$i]["I"];
      $modelo = $allDataInSheet[$i]["J"];
      $embalaje = $allDataInSheet[$i]["K"];
      $un_empaque = $allDataInSheet[$i]["L"];
<<<<<<< HEAD
      $posicion = $allDataInSheet[$i]["M"];
=======
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
      $observacion = $allDataInSheet[$i]["O"];
      /* Para versión PHP >= 5.3
      $fecha_expira = date_format(DateTime::createFromFormat('d-m-Y',$allDataInSheet[$i]["P"]),'Y-m-d'); */
      // Para versión PHP < 5.3
      //$fecha = new DateTime($allDataInSheet[$i]["P"]);
      $fecha_expira = $allDataInSheet[$i]["P"]; //mm-dd-yy
      $res = explode("-", $fecha_expira);
      $fecha_expira = $res[2]."-".$res[0]."-".$res[1];
      $fecha_expira = date('Y-m-d',strtotime($fecha_expira));

      //Valida existencia del arribo
      $query = "SELECT MIN(ie.codigo) AS item,ref.codigo AS codigo,ie.arribo AS arribo,
<<<<<<< HEAD
                  ie.orden AS orden,emb.codigo AS codemb,um.id AS id,ps.codigo AS codpos
=======
                  ie.orden AS orden,emb.codigo AS codemb,um.id AS id
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
                FROM inventario_entradas ie
                  LEFT JOIN referencias ref ON ref.codigo_ref = '$referencia'
                  LEFT JOIN embalajes emb ON emb.cd_embalaje = '$embalaje'
                  LEFT JOIN unidades_medida um ON um.codigo = '$un_empaque'
<<<<<<< HEAD
                  LEFT JOIN posiciones ps ON ps.nombre = '$posicion'
=======
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
                WHERE (ie.arribo = $arribo) AND (ie.orden = $orden)";

      $db->query($query);
      $regResult = $db->fetch();
      $codigo = $regResult->item;
      $arribo = $regResult->arribo;
      $orden = $regResult->orden;
      $referencia = $regResult->codigo;
      $embalaje = $regResult->codemb;
      $un_empaque = $regResult->id;
<<<<<<< HEAD
      $posicion = $regResult->codpos;
=======
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
      
      if($arribo=="") {
        $validador[$i]['A'] = $allDataInSheet[$i]["A"];
        $validador[$i]['O'] .= "Arribo no existe ";
      }
      if($orden=="") {
        $validador[$i]["B"] = $allDataInSheet[$i]["B"];
        $validador[$i]["O"] .= "Orden no existe ";
      }
      if($referencia=="") {
        $validador[$i]["E"] = $allDataInSheet[$i]["E"];
        $validador[$i]["O"] .= "Referencia no existe ";
      } 
      if($embalaje=="") {
        $validador[$i]["K"] = $allDataInSheet[$i]["K"];
        $validador[$i]["O"] .= "Embalaje no existe ";
      }
      if($un_empaque=="") {
        $validador[$i]["L"] = $allDataInSheet[$i]["L"];
<<<<<<< HEAD
        $validador[$i]["O"] .= "Unidad de Empaque no existe ";
      }
      if($posicion=="") {
        $validador[$i]["M"] = $allDataInSheet[$i]["M"];
        $validador[$i]["O"] .= "Posicion no existe";
=======
        $validador[$i]["O"] .= "Unidad de Empaque no existe";
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
      }
    }
    return $validador;
  }
	
  function procesarPlantilla($arreglo) {
    $db = $_SESSION['conexion'];
    
    try {
      $objPHPExcel = PHPExcel_IOFactory::load($arreglo[nombrefile]);
    } catch(Exception $e) {
      die('Error cargando la plantilla "'.pathinfo($arreglo[nombrefile],PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    //Asignar hoja de Excel activa
    $objPHPExcel->setActiveSheetIndex(0);
    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
    $arrayCount = count($allDataInSheet); //Cuenta el total de filas en la hoja de Excel
    $regArribo = $regOrden = "";
    //Verifica Inconsistencias
    for($i = 2; $i <= $arrayCount; $i++) {
      $arribo = $allDataInSheet[$i]["A"];
      $orden = $allDataInSheet[$i]["B"];
      $referencia = $allDataInSheet[$i]["E"];
      $cantidad = $allDataInSheet[$i]["F"];
      $peso = $allDataInSheet[$i]["G"];
      $valor = $allDataInSheet[$i]["H"];
      $fmm = $allDataInSheet[$i]["I"];
      $modelo = $allDataInSheet[$i]["J"];
      $embalaje = $allDataInSheet[$i]["K"];
      $un_empaque = $allDataInSheet[$i]["L"];
<<<<<<< HEAD
      $posicion = $allDataInSheet[$i]["M"];
=======
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
      $observacion = $allDataInSheet[$i]["O"];
      /* Para versión PHP >= 5.3
      $fecha_expira = date_format(DateTime::createFromFormat('d-m-Y',$allDataInSheet[$i]["P"]),'Y-m-d'); */
      // Para versión PHP < 5.3
      $fecha_expira = $allDataInSheet[$i]["P"]; //mm-dd-yy
      $res = explode("-", $fecha_expira);
      $fecha_expira = $res[2]."-".$res[0]."-".$res[1];
      $fecha_expira = date('Y-m-d',strtotime($fecha_expira));
           
      if($arribo!=$regArribo && $orden!=$regOrden) {
        $query = "SELECT MIN(ie.codigo) AS item,ref.codigo AS codigo,
<<<<<<< HEAD
                    emb.codigo AS codemb,um.id AS id,ps.codigo AS codpos
=======
                    emb.codigo AS codemb,um.id AS id
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
                  FROM inventario_entradas ie 
                    INNER JOIN referencias ref ON ref.codigo_ref = '$referencia'
                    INNER JOIN embalajes emb ON emb.cd_embalaje = '$embalaje'
                    INNER JOIN unidades_medida um ON um.codigo = '$un_empaque'
<<<<<<< HEAD
                    INNER JOIN posiciones ps ON ps.nombre = '$posicion'
=======
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
                  WHERE (ie.arribo = $arribo) AND (ie.orden = $orden)";        
        $actualizo = true;
      } else {
        $query = "SELECT ref.codigo AS codigo,emb.codigo AS codemb,
<<<<<<< HEAD
                    um.id AS id,ps.codigo AS codpos,da.tipo_operacion
=======
                    um.id AS id,da.tipo_operacion
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
                  FROM inventario_entradas ie 
                    INNER JOIN referencias ref ON ref.codigo_ref = '$referencia'
                    INNER JOIN embalajes emb ON emb.cd_embalaje = '$embalaje'
                    INNER JOIN unidades_medida um ON um.codigo = '$un_empaque'
<<<<<<< HEAD
                    INNER JOIN posiciones ps ON ps.nombre = '$posicion'
=======
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
                    INNER JOIN do_asignados da ON da.do_asignado = $orden
                  WHERE (ie.arribo = $arribo) AND (ie.orden = $orden)";        
      }
      $db->query($query);
      $regResult = $db->fetch();
      $codigo = $actualizo ? $regResult->item : "";
      $referencia = $regResult->codigo;
      $embalaje = $regResult->codemb;
      $un_empaque = $regResult->id;
<<<<<<< HEAD
      $posicion = $regResult->codpos;
=======
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
      $tipo_operacion = $regResult->tipo_operacion;
      
      if($codigo!="" && $actualizo) {
        //Existe el registro en el inventario_entradas y se actualiza la información
        $query = "UPDATE inventario_entradas
                    SET referencia = '$referencia',
                        cantidad = $cantidad,
                        peso = $peso,
                        valor = $valor,
                        fmm = '$fmm',
                        modelo = '$modelo',
                        embalaje = $embalaje,
                        un_empaque = $un_empaque,
<<<<<<< HEAD
                        posicion = $posicion,
=======
                        posicion = 1,
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
                        fecha_expira = '$fecha_expira' WHERE (codigo = $codigo)";

        $db->query($query);
        $regArribo = $allDataInSheet[$i]["A"];
        $regOrden = $allDataInSheet[$i]["B"];
        $actualizo = false;
      } else {
        $query = "INSERT INTO inventario_entradas(arribo,orden,tipo_mov,fecha,referencia,cantidad,
                    peso,valor,fmm,modelo,embalaje,un_empaque,posicion,cant_declaraciones,
                    observacion,fecha_expira) 
                  VALUES($arribo,$orden,0,CURDATE(),'$referencia',$cantidad,$peso,$valor,'$fmm',
<<<<<<< HEAD
                    '$modelo',$embalaje,$un_empaque,$posicion,0,'$observacion',
=======
                    '$modelo',$embalaje,$un_empaque,1,0,'$observacion',
>>>>>>> c4a9fdc7fd5521c67a74d1c6e559033ce162631e
                    '$fecha_expira');";

        $db->query($query);
        $inventario_entrada = mysql_insert_id();
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
                        fob_nonac = $fob_nonac WHERE (inventario_entrada = $inventario_entrada)";

        $db->query($query);
      }
    }
    echo "<script>alert('Cargue del INVENTARIO ENTRADAS y MOVIMIENTOS satisfactoriamente');</script>";
    //Elimina el archivo Excel con novedades
    unlink($arreglo['nombrefile']);    
  }
}
?>