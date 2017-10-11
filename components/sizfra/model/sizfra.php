<?php
require_once(CLASSES_PATH.'BDControlador.php');

class SizfraModelo extends BDControlador {
  function SizfraModelo() {
    parent :: Manejador_BD();
  }
	
  function listadoSizfra($arreglo) {
    $db = $_SESSION['conexion'];
		$sede = $_SESSION['sede'];


    


		//Prepara la condición del filtro
    if(!empty($arreglo[nitfe])) $arreglo[where] .= " AND do_asignados.por_cuenta='$arreglo[nitfe]'";
    if(!empty($arreglo[fechadesdefe])) $arreglo[where] .= " AND DATE_FORMAT(im.fecha, '%Y-%m-%d') >= '$arreglo[fechadesdefe]'";
    if(!empty($arreglo[fechahastafe])) $arreglo[where] .= " AND DATE_FORMAT(im.fecha, '%Y-%m-%d') <= '$arreglo[fechahastafe]'";

    //echo "<script language='javascript'> alert('Nit '+'$arreglo[where]'); </script>";

    /*$query = "SELECT do_asignados.do_asignado AS orden,imm.fecha,imm.codigo,im.codigo AS cod_mov,im.tipo_movimiento,
                itm.zona_franca AS operacion,ref.ref_prove AS sku_proveedor,arribos.peso_bruto,
                ABS(im.peso_naci+im.peso_nonac) AS peso_neto,arribos.fletes,arribos.seguros,arribos.otros_gastos,embalajes.cd_embalaje,
                trans.tipo_transporte AS modo_transporte,p.codigopais AS origen,pr.codigopais AS destino,imm.lev_bultos AS bultos,
                ref.codigo_ref AS cod_referencia,ABS(im.cantidad_naci+im.cantidad_nonac) AS cantidad,
                CASE WHEN im.num_levante = id.num_levante THEN ABS(im.fob_nonac+im.cif/id.trm) ELSE ABS(im.fob_nonac+im.cif) END AS precio
              FROM inventario_maestro_movimientos imm,referencias ref,arribos,embalajes,transportador trans,do_asignados,
                puertos p,puertos pr,inventario_movimientos im
                INNER JOIN inventario_tipos_movimiento itm ON im.tipo_movimiento = itm.codigo
                INNER JOIN inventario_entradas ie ON im.inventario_entrada = ie.codigo
                LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante
              WHERE imm.codigo = im.cod_maestro
                AND ie.referencia = ref.codigo
                AND arribos.arribo = ie.arribo
                AND ie.embalaje = embalajes.codigo
                AND arribos.transportador = trans.codigo
                AND arribos.orden = do_asignados.do_asignado
                AND do_asignados.puertorigen = p.puerto
                AND do_asignados.puertodestino = pr.puerto
                AND do_asignados.sede = '$sede'
                AND im.control_zf = 0$arreglo[where]";*/
    /*$query = "SELECT do_asignados.do_asignado AS orden,imm.fecha,imm.codigo,im.codigo AS cod_mov,im.tipo_movimiento,
                itm.zona_franca AS operacion,ref.ref_prove AS sku_proveedor,arribos.peso_bruto,
                ABS(im.peso_naci+im.peso_nonac) AS peso_neto,arribos.fletes,arribos.seguros,arribos.otros_gastos,embalajes.cd_embalaje,
                trans.tipo_transporte AS modo_transporte,p.codigopais AS origen,pr.codigopais AS destino,imm.lev_bultos AS bultos,
                ref.codigo_ref AS cod_referencia,ABS(im.cantidad_naci+im.cantidad_nonac) AS cantidad,
                CASE WHEN im.num_levante = id.num_levante THEN ABS(im.fob_nonac+im.cif/id.trm) ELSE ABS(im.fob_nonac+im.cif) END AS precio
              FROM inventario_maestro_movimientos imm,referencias ref,arribos,embalajes,transportador trans,do_asignados,
                puertos p,puertos pr,inventario_movimientos im
                INNER JOIN inventario_tipos_movimiento itm ON im.tipo_movimiento = itm.codigo
                INNER JOIN inventario_entradas ie ON im.inventario_entrada = ie.codigo
                LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante 
              WHERE ie.referencia = ref.codigo
                AND arribos.arribo = ie.arribo
                AND ie.embalaje = embalajes.codigo
                AND arribos.transportador = trans.codigo
                AND arribos.orden = do_asignados.do_asignado
                AND do_asignados.puertorigen = p.puerto
                AND do_asignados.puertodestino = pr.puerto
                AND do_asignados.sede = '$sede'
                AND im.control_zf = 0$arreglo[where]";*/
    $query = "SELECT do_asignados.do_asignado AS orden,im.fecha,im.codigo AS cod_mov,im.tipo_movimiento,
                itm.zona_franca AS operacion,ref.ref_prove AS sku_proveedor,arribos.peso_bruto,
                ABS(im.peso_naci+im.peso_nonac) AS peso_neto,arribos.fletes, arribos.seguros,arribos.otros_gastos,embalajes.cd_embalaje,
                trans.tipo_transporte AS modo_transporte,arribos.parcial AS origen,pr.codigopais AS destino,   
                (ABS(im.peso_naci+im.peso_nonac)*arribos.cantidad/arribos.peso_bruto) AS bultos,
                ref.codigo_ref AS cod_referencia,ABS(im.cantidad_naci+im.cantidad_nonac) AS cantidad,
                CASE WHEN im.num_levante = id.num_levante THEN ABS(im.fob_nonac+im.cif/id.trm) ELSE ABS(im.fob_nonac+im.cif) END AS precio
              FROM do_asignados,referencias ref,arribos,embalajes,transportador trans,puertos p,puertos pr,inventario_movimientos im
                INNER JOIN inventario_tipos_movimiento itm ON im.tipo_movimiento = itm.codigo
                INNER JOIN inventario_entradas ie ON im.inventario_entrada = ie.codigo
                LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante 
              WHERE ie.referencia = ref.codigo
                AND arribos.arribo = ie.arribo
                AND ie.embalaje = embalajes.codigo
                AND arribos.transportador = trans.codigo
                AND arribos.orden = do_asignados.do_asignado
                AND do_asignados.puertorigen = p.puerto
                AND do_asignados.puertodestino = pr.puerto
                AND do_asignados.sede = '$sede'$arreglo[where]";    
                
                //echo "<script language='javascript'> alert('SQL:  '+'$query'); </script>";

    $db->query($query);
    return $db->getArray();
  }
  
	function consultaSizfra($arreglo) {
    $db = $_SESSION['conexion'];

    $query = "SELECT interfaz,fecha_interfaz,SUM(cantidad_naci+cantidad_nonac) AS cantidad,SUM(peso_naci+peso_nonac) AS peso,
                CASE WHEN im.num_levante = id.num_levante THEN SUM(im.fob_nonac+im.cif/id.trm) ELSE SUM(im.fob_nonac+im.cif) END AS valor
              FROM inventario_movimientos im
                LEFT JOIN inventario_declaraciones id ON im.num_levante = id.num_levante
              WHERE interfaz IS NOT NULL
              GROUP BY interfaz";

		$db->query($query);
    return $db->getArray();
	}

	function deshacerSizfra($arreglo) {
    $db = $_SESSION['conexion'];
    
    //Libera el código del inventario_movimientos
    $query = "UPDATE inventario_movimientos
                SET interfaz = NULL,fecha_interfaz = ''
              WHERE interfaz = '$arreglo[nombreinterfazc]'";
       
    $db->query($query);
  }
  
  function actualizaMovimientos($cod_mov,$interfaz) {
    $db = $_SESSION['conexion'];
    $fecha = date('Y-m-d H:i:s');
        
    $query = "UPDATE inventario_movimientos
                SET interfaz = '$interfaz', fecha_interfaz = '$fecha'
              WHERE codigo = $cod_mov AND interfaz IS NULL";

    $db->query($query);
  }
  
	function findInterfaz($arreglo) {
    $db = $_SESSION['conexion'];

		$query = "SELECT interfaz FROM inventario_movimientos WHERE (interfaz = '$arreglo[nombreinterfaz]')";

		$db->query($query);
    return $db->getArray();
	}
  
  function findCliente($arreglo) {
    $db = $_SESSION['conexion'];
    
		$query = "SELECT numero_documento,razon_social,correo_electronico,v.nombre as nvendedor
						FROM clientes, vendedores v WHERE (razon_social LIKE '%$arreglo[q]%') AND (v.codigo = vendedor)";

		$db->query($query);
    return $db->getArray();
	} 
}
?>