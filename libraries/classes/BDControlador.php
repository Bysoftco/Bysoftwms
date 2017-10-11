<?php
class BDControlador {
  /**
    * La tabla de la base de datos donde los registros de esta clase son almacenados
    *
    * @var String
    */
  var $table_name = '';

  /**
    * Este es el nombre singular del objeto.  (i.e. Usuario).
    *
    * @var String
    */
  var $object_name = '';

  /**
    * El nombre de directorio del mdulo para este tipo de objeto.
    *
    * @var String
    */
  var $module_directory = '';

  function Manejador_BD() { }

  function log($id_usuario, $ip, $modulo, $query, $funcion = 'save') {
    $db = $_SESSION['conexion'];
    $sql = "INSERT INTO log_aplicacion(usuario_id,ip_equipo,modulo,funcion,codigo_sql)
            VALUE ($id_usuario,'$ip','$modulo','$funcion','$query')";
    $db->query($sql);
  }

  function build_list($table, $code, $name_camp, $where = '') {
    $db = $_SESSION['conexion'];
    $sql = "SELECT $code,$name_camp FROM $table $where";

    $db->query($sql);
    $result = $db->GetArray();
    $array = array();

    foreach ($result as $key => $index) {
      foreach ($index as $keyAux => $value) {
        $je = $keyAux;
        $$je = $value;
      }
      $array[$$code] = $$name_camp;
    }
    return $array;
  }

  function build_list_sqlserver($table, $code, $name_camp, $where = '') {
    global $dbServer;
    $sql = "SELECT $code,$name_camp FROM $table $where";

    $dbServer->query($sql);
    $result = $dbServer->GetArray();
    $array = array();

    foreach ($result as $key => $index) {
      foreach ($index as $keyAux => $value) {
        $je = $keyAux;
        $$je = $value;
      }
      $array[$$code] = $$name_camp;
    }
    return $array;
  }

  /**
    * function guardar()
    * Guarda el objeto. Si se ingresa un id se actualiza dicho registro de lo contrario se inserta un nuevo registro.
    * 
    * 
    * @param ID $registro_id ID del registro que se va a guardar
    * @return BOOLEAN true si guarda correctamente false de lo contrario
    * 
    */
  function save($registro_id = 0, $campo = 'id', $log = 'save') {
    $db = $_SESSION['conexion'];
    $variables_objeto = get_class_vars(get_class($this));
    $total_campos = count($variables_objeto);
    $numero_campo = 1;
    if ($registro_id > 0) {
      $sql_guardar = "UPDATE " . $this->table_name . " SET";
    } else {
      $sql_guardar = "INSERT INTO " . $this->table_name . " SET";
    }

    foreach ($variables_objeto as $variable => $valor) {
      if ($variable != 'table_name' && $variable != 'module_directory' && $variable != 'object_name' && $variable != 'campos' && $variable != 'camposSincronizar' && $variable != 'fecha_creacion' && ($variable != 'id') && $variable != "_etapa") {
        if ($numero_campo != $total_campos && $numero_campo > 1) $sql_guardar .=", ";
        if ($this->$variable === null) {
          $sql_guardar .= " $variable = NULL";
        } else {
          if ($this->$variable === "on") {
            $this->$variable = 1;
          }
          $sql_guardar .= " $variable = '" . addslashes($this->$variable) . "'";
        }
        $numero_campo++;
      } else {
        $total_campos--;
      }
    }
    if ($registro_id > 0) {
      $sql_guardar .= " WHERE $campo = $registro_id";
      //__P($sql_guardar);
      //exit;
      $db->query($sql_guardar);
      $this->log($_SESSION['datos_logueo']['usuario_id'], $_SERVER['REMOTE_ADDR'], $this->module_directory, base64_encode($sql_guardar), $log);
      return $registro_id;
    } else {
      //__P($sql_guardar);
      //exit;
      $db->query($sql_guardar);
      $id_obtenido = $db->getInsertID();
      $this->log($_SESSION['datos_logueo']['usuario_id'], $_SERVER['REMOTE_ADDR'], $this->module_directory, base64_encode($sql_guardar), $log);
      return $id_obtenido;
    }
  }

  /**
    * Funcin trae una sola fila de datos basado en un valor de clave primaria.
    * 
    * Los datos recuperados son colocados en el objeto instanciado. La funcin tambien procesa los datos formateandolos en 
    * el formato correcto de hora/fecha y valores nmericos.
    * 
    * @param string $id usado para la bsqueda
    * @param boolean $codificar Optional, predeterminado true, codifica los valores recuperados de la base de datos.
    * 
    */
  function recover($id, $field = null, $poblarObjeto = true) {
    $db = $_SESSION['conexion'];
    if ($field === null) {
      $field = 'id';
    } else {
      $field = $field;
    }
    $query = "SELECT $this->table_name.* FROM $this->table_name" . " WHERE $this->table_name.{$field} = '$id' ";
    //$GLOBALS['log']->debug("Recuperar $this->object_name : ".$query);		
    //__P($query);
    //exit;
    $db->query($query);
    $result = $db->getArray();

    if (empty($result)) {
      return null;
    }

    if (!count($result) > 0) {
      return null;
    }

    if ($poblarObjeto === true) {
      $this->poblar_desde_fila($result[0]);
      return $this;
    } else {
      return $registry;
    }
  }

  /**
    * Coloca el valor de la fila recuperada en el objeto actual.
    * 
    * @param ARRAY $registro fila recuperada
    * 
    */
  function poblar_desde_fila($registro) {
    foreach ($this->campos as $campo) {
      if (isset($registro[$campo]) && $registro[$campo] != "") {
        $this->$campo = $registro[$campo];
      } else {
        if (!isset($registro[$campo]) && $this->$campo === null) {
          $this->$campo = null;
        } else {
          $this->$campo = '';
        }
      }
    }
  }

  /**
    * Funcin trae un listado con todos los registro del objeto
    * 
    * Los datos recuperados son devueltos en un array. 
    * 
    * @param STRING $where usado para limitar los registros
    * @param ARRAY $campos array que se utiliza para seleccionar los campos que se necesitan del listado
    * @return ARRAY $registros arreglo con los registros de la lista
    * 
    */
  function get_listed($where = "", $fields = array(), $associative = false, $return_result = false) {
    $db = $_SESSION['conexion'];
    $fields = (count($fields) > 0) ? $fields : $this->campos;
    $sql_list = "SELECT ";
    $sql_list .= $this->select_fields_build($fields);
    $sql_list .= " FROM " . $this->table_name;
    if (!empty($where)) $sql_list .= "\n WHERE $where";
    //__P($sql_list);
    //exit;

    $result = $db->query($sql_list);
    if ($associative) return $db->GetAssoc();
    else return $db->GetArray();
  }

  /**
    * function select_fields_build()
    * Construye una cadena de los campos que se colocarn en el select separado por comas 
    *
    * @param ARRAY $campos array que se utiliza para seleccionar los campos que se necesitan del listado 
    * @return STRING $cadena_campos campos separados por comas
    */
  function select_fields_build($campos = array()) {
    return implode(",", $campos);
  }

  function armSelect($array, $title = '-', $seleccion = 'NA', $maxCaracteres = 50) {
    $returnValue = "<OPTION VALUE=\"\" SELECTED>$title</OPTION> \n";
    foreach ($array as $key => $value) {
      $selected = ($seleccion == $key) ? ' SELECTED' : '';
      $returnValue.= "<OPTION VALUE=\""
        . $key
        . "\"$selected>"
        . htmlentities(ucwords(substr($value, 0, $maxCaracteres)), ENT_QUOTES) . "</OPTION>\n";
    }
    return $returnValue;
  }

  function deleted($id = null) {
    if (is_numeric($id) === true) {
      if ($this->recover($id) === null) {
        return false;
      } else {
        $this->eliminado = 1;
        $this->save($id, 'id', 'delete');
        return true;
      }
    } else {
      return null;
    }
  }

  function paginar($paginaActual, $numeroFilas, $filasMostrar) {
    if ($numeroFilas != 0) {
      $siguiente = $paginaActual + 1;
      $anterior = $paginaActual - 1;
    } else {
      $siguiente = 1;
      $anterior = 1;
    }

    $ultima = ceil($numeroFilas / $filasMostrar);
    $paginaActual = (int) $paginaActual;
    if ($paginaActual > $ultima) {
      $paginaActual = $ultima;
    }
    if ($paginaActual < 1) {
      $paginaActual = 1;
    }

    return $this->lu_paginado($paginaActual, $ultima, $anterior, $siguiente);
  }

  function lu_paginado($pagina, $ultima) {
    $paginacion = '';
    $paginacion .= "<ul id='pagination-digg'>";
    if ($pagina == 1) {
      $paginacion.='<li class="previous-off">&laquo; Anterior</li>';
    } else {
      $paginacion.='<li class="previous"><a href="javascript:paginar(' . ($pagina - 1) . ')"  >&laquo; Anterior</a></li>';
    }
    $contAnt = $pagina - 2;
    $contSig = $pagina + 2;
    if ($ultima <= 5) {
      $contAnt = 0;
      $contSig = $ultima;
    } elseif ($contAnt <= 0) {
      $contSig += abs($contAnt) + 1;
      $contAnt = 1;
    } elseif ($contSig >= $ultima) {
      $contSig = $ultima;
      $contAnt = $ultima - 4;
    }
    $opciones = '';
    for ($i = 1; $i <= $ultima; $i++) {
      if ($pagina == $i) {
        $paginacion .= '<li class="active">' . $i . '</li>';
        $opciones .= "<option selected value='$i'>$i</option>";
      } elseif ($i >= $contAnt && $i <= $contSig) {
        $paginacion.='<li><a href="javascript:paginar(' . $i . ')" >' . $i . '</a></li>';
        $opciones.="<option value='$i'>$i</option>";
      } else {
        $opciones.="<option value='$i'>$i</option>";
      }
    }
    if ($ultima > $pagina) {
      $paginacion.='<li class="next"><a href="javascript:paginar(' . ($pagina + 1) . ')" >Siguiente &raquo;</a></li>';
    } else {
      $paginacion.='<li class="next-off">Siguiente &raquo;</li>';
    }
    $paginacion.="</ul>";
    $tabla .= "<table cellpadding='0' cellspacing='0'  border='0' width='100%'>
                <tr style='vertical-align: bottom; white-space: nowrap;'>
                  <td>
                    $paginacion
                  </td>
                  <td align='right'>
                    <table>
                      <tr>
                        <td>P&aacute;gina $pagina de $ultima &nbsp;&nbsp;</td>
                        <td>ir a &raquo; &nbsp;</td>
                        <td><select onchange='javascript:paginar(this.value)' class='select_paginacion'>$opciones</select></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>";
    return $tabla;
  }

  function ExcelSimple($arreglo) {
    header("Content-type: application/force-download");
    header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera 
    header("Content-type: application/x-msexcel");                    // This should work for the rest
    header("Content-disposition: attachment; filename=" . $arreglo['tituloArchivo'] . ".xls; charset=iso-8859-1");
    header("Content-Transfer-Encoding: binary");

    /* $tabla="";
    if(!isset($arreglo['encabezados'])) {
      foreach($arreglo['datosExcel'][0] as $key => $value) {
        //$tabla.='<td>'.$key.'</td>';
        $tabla.="<b>".$key."</b> \t ";
      }
      $tabla.=" \n ";
    } else {
      foreach($arreglo['encabezados'] as $key) {
        //$tabla.='<td>'.$key.'</td>';
        $tabla.=$key." \t ";
      }
      $tabla.=" \n ";
    }
    foreach($arreglo['datosExcel'] as $key => $value) {
      foreach($value as $key2 => $value2) {
        $tabla.=$value2." \t ";
        //$tabla.='<td>'.$value2.'</td>';
      }
      $tabla.=" \n ";
    } */

    $tabla = "<table border='1'>";
    $tabla .= "<tr style='font-weight: bold;'>";
    if (!isset($arreglo['encabezados'])) {
      foreach ($arreglo['datosExcel'][0] as $key => $value) {
        $tabla.="<td>" . $key . "</td>";
      }
    } else {
      foreach ($arreglo['encabezados'] as $key) {
        $tabla.="<td>" . $key . "</td>";
      }
    }
    $tabla .= "</tr>";
    foreach ($arreglo['datosExcel'] as $key => $value) {
      $tabla .= "<tr>";
      foreach ($value as $key2 => $value2) {
        $tabla.="<td>" . $value2 . "</td>";
      }
      $tabla .= "</tr>";
    }
    $tabla .= "</table>";
    echo $tabla;
  }
}
?>