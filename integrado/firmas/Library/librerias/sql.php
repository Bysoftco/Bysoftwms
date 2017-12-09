<?php

function puedeActualizar($perfil,$accion) {

switch($perfil)
{
 case'ADM':
  return 'visible';
 break;
 case'OPE':
       switch($accion) {
	    case 'consults':
		return 'hidden';
		break;
		default:
		return 'statusBD';
     
	   }
    
  return 'visible';
 break;
 }
}
function puedeCrear($perfil,$accion) {

switch($perfil)
{
 case'ADM':
  return 'visible';
 break;
 case'OPE':
       switch($accion) {
	    case'consults':
		return 'hidden';
		break;
		default:
		return 'visible';
     
	   }
    
  return 'visible';
 break;

}

}
 function nombre_cliente($cliente,$co) 
   { 
    $sql="
						SELECT * 
						FROM  clientes
						WHERE   numero_documento ='$cliente'
						";
				
				   	//$result   = pg_Exec ($co, $sql);
				   	$result = mysql_query($sql,$co);		   
                  	//$row = pg_fetch_array($result);
					$row = mysql_fetch_array($result); 
				 
       return $row ['razon_social'];
  
	}
	 function nombre_ciudad($ciudad,$co) 
   { 
    $sql="
						SELECT * 
						FROM  ciudades
						WHERE   codigo =$ciudad
						";
				
				   	//$result = pg_Exec ($co, $sql);
				   	$result = mysql_query ($sql,$co);		   
					//$row = pg_fetch_array($result);
					$row = mysql_fetch_array($result); 
				 
       return $row ['nombre'];
  
	}
		 function actividad_economica($actividad,$co) 
   { 
    $sql="
						SELECT * 
						FROM  actividades_economicas
						WHERE   codigo =$actividad
						";
				
				   	//$result = pg_Exec ($co, $sql);
					$result = mysql_query ($sql,$co);
					//$row = pg_fetch_array($result);
					$row = mysql_fetch_array($result);
				 
       return $row ['nombre'];
  
	}

	 function tipo_operacion($operacion,$co) 
   { 
    $sql="
						SELECT * 
						FROM  tipos_operacion
						WHERE   codigo  =$operacion
						";
				
				   	//$result = pg_Exec ($co, $sql);
					$result = mysql_query ($sql,$co);
					//$row = pg_fetch_array($result); 
					$row = mysql_fetch_array($result); 
				 
       return $row ['nombre'];
  
	}
     
    function nombre_sede($sede,$co) 
   { 
    $sql="
						SELECT * 
						FROM  sedes
						WHERE   codigo ='$sede'
						";
				
				   	//$result = pg_Exec ($co, $sql);
					$result = mysql_query ($sql,$co);
					//$row = pg_fetch_array($result);
					$row = mysql_fetch_array($result); 
				 
       return $row ['nombre'];
  
	}
	 function nombre_embalaje($embalaje,$co) 
   { 
    $sql="
						SELECT * 
						FROM  tipos_embalaje
						WHERE   id ='$embalaje'
						";
				
				   	//$result = pg_Exec ($co, $sql);
					$result = mysql_query ($sql,$co);
					//$row = pg_fetch_array($result);
					$row = mysql_fetch_array($result); 
				 
       return $row ['nombre'];
  
	}
	/*********************************************************************************************
	//Funcion que valida que la referencia y subreferencia ingrezadas no existan en la BD
	//**********************************************************************************************/
	 function verifica_id($id,$tabla,$cliente,$refe,$co) 
   { 
    
	$sql="
						SELECT * 
						FROM  ".$tabla."
						 WHERE   id ='$id'";
						 
						 if($tabla=='subreferencia')
						 {
						 $sql=$sql.
						 
						 
						 "AND refe ='$refe'";
						 }
						 
						 $sql=$sql.
						 
						 
						 "AND cliente ='$cliente'
						";
				
				   	//$result = pg_Exec ($co, $sql);
					$result = mysql_query ($sql,$co);		   
					//echo $sql;
					//$cantidad = pg_numrows($result);
					$cantidad = mysql_numrows($result);
					
				 
       return $cantidad;
  
	}
	 function verifica_id_cliente($cliente,$co) 
   { 
    
	$sql="
						SELECT * 
						FROM  clientes
						 WHERE    numero_documento ='$cliente'
						
						";
				
				   	//$result = pg_Exec ($co, $sql);
					$result = mysql_query ($sql,$co);
					//$cantidad = pg_numrows($result);
					$cantidad = mysql_numrows($result);
				 
       return $cantidad;
  
	}
	function verifica_id_vuelo($vuelo,$co) { 
   
    $sql="
						SELECT * 
						FROM  vuelos
						WHERE    vuelo ='$vuelo'
						
						";
				
				$result = mysql_query ($sql,$co);
				$cantidad = mysql_numrows($result);
				 return $cantidad;
  
	}
	function tipo_sede($sede,$co) 
   { 
    $sql="SELECT tipo_sede
		FROM sedes
		WHERE codigo ='$sede'";
		//echo $sql;
				
				   	//$result = pg_Exec ($co, $sql);
					$result = mysql_query ($sql,$co);
					//$row = pg_fetch_array($result);
					$row = mysql_fetch_array($result);
				 
       return $row ['tipo_sede'];
  
	}
  
     function tipo_ingreso_do($do,$co)
   {
   $sql="
						SELECT tipo_ingreso
						FROM  ordenes
						WHERE  codigo  ='$do'
						";
				
				 //$result = pg_Exec ($co, $sql);
					$result = mysql_query ($sql,$co);
					//$row = pg_fetch_array($result);
					$row = mysql_fetch_array($result); 
				 
       return $row ['tipo_ingreso'];
   }
    function crear_ingreso($do,$consecutivo_ingreso,$sede,$co)
	
   { 
     $anio=date(Y);
	 $tipo_ingreso=tipo_ingreso_do($do,$co);
       $sql="
						SELECT por_defecto
						FROM  sedes
						WHERE  codigo  ='$sede' 
						
						";
						
				
				  // $result   = pg_Exec ($co, $sql);	
				   $result   = mysql_query ($sql,$co);		   
                  // $row      =pg_fetch_array($result); 
                  $row      =mysql_fetch_array($result); 
				 
       $documento_por_defecto= $row ['por_defecto'];

 $date=date('Y-m-d H:i:s');
	$sql="
		INSERT INTO ordenes_ingresos(
   orden ,
  ingreso,
  fecha_ingreso ,
  manifiesto ,
  fecha_manifiesto ,
  tipo_documento_transporte ,
  numero_documento_transporte ,
  cantidad ,
  tipo_embalaje ,
  peso_bruto ,
  peso_guia ,
  repeso ,
  metros_cuadrados ,
  posicion_estiba ,
  consignada_asignada ,
  tarifa ,
  tipo_tarifa ,
  valor_calculo_factura ,
  valor_fob ,
  fletes ,
  dice_contener ,
  observaciones,
  deposito )
		VALUES(
 		 '$do',
         $consecutivo_ingreso,
  '$date',
  '',
  '$date',
  '$documento_por_defecto',
  '',
  0,
  'te',
  0,
  0,
  0,
 0,
 0,
  'C',
  't',
  1,
  0,
  0,
  0,
  '   ',
  '   ',
  '01'
	)" ;
	

	


			
    		//if (!$query = pg_Exec ($co, $sql))
			if (!$query = mysql_query ($sql,$co))
                {

                 $mensaje= 'Problemas al intentar guardar el do  '; 
                }
              else{
		        }
	}
	
	
  
	
	//********************************************************************************************
	//Funcion que inserta los productos de diferente referencia en el inventario
	//*******************************************************************************************
	  function agregar_referencia($num_ingreso,$do,$co) 
   { 
     // se procede ha buscar el consecutivo de referencias
   $sql="
						SELECT  max(num_referencia) + 1 as ref
						FROM  entradas_ordenes_ingresos
						WHERE  num_ingreso =$num_ingreso AND
						orden				='$do'
						";
			
				      //$result = pg_Exec ($co, $sql);
					  $result = mysql_query ($sql,$co);
					  //$row = pg_fetch_array($result);
					  $row = mysql_fetch_array($result); 
				      $num_referencia= $row['ref'];
					 
					  if(empty($num_referencia))
					  {
					    $num_referencia=1;
						
					 }
					
 
	$sql="
		INSERT INTO entradas_ordenes_ingresos(
		orden,
		num_ingreso,
		num_referencia,
  		id_referencia,
  		cantidad_ingreso,
  		por_facturar,
  		nacionalizado,
  		largo,
  		alto,
  		observaciones,
  		peso_guia,
  		peso_bascula,
		ancho,
		unidades_embalaje,
	    confirmar_fob)
		VALUES(
		        '$do', 
       			$num_ingreso,
				$num_referencia,
 			   '0',
                 0,
 				1,
				0,
  			  '0',
			   '0',
   			  'observaciones',
 			   0,
 			   0	,
			   '0',
			   1,
			   pendiente)" ;

			
    			//if (!$query = pg_Exec ($co, $sql))
				if (!$query = mysql_query ($sql,$co))
                {

               echo  'Problemas al Crear Ingresos al DO  '; 
                }
              else{
		        }
	}
	
	
//**********************************************************************************************
//Funcion que hace el calculo de Unidades Disponibles en el inventario
//**********************************************************************************************
function unidades_recibidas($num_ingreso,$referencia,$co,$do){
//1 Se Averigua Cuantas llegaron
	$sql="SELECT *
		FROM entradas_ordenes_ingresos
		WHERE num_ingreso=$num_ingreso 
		AND num_referencia=$referencia
		AND orden='$do'";
	//$result = pg_Exec ($co, $sql);
	$result = mysql_query($sql,$co);
	//$row=pg_fetch_array($result);
	$row=mysql_fetch_array($result);
	return  $row ['cantidad_ingreso'];
}
function peso_recibido($num_ingreso,$do,$co){
//1 Se Averigua Cuantas llegaron
	$sql="SELECT sum(peso_bascula) as total_peso
		FROM entradas_ordenes_ingresos
		WHERE num_ingreso=$num_ingreso 
	    AND orden='$do'";
	//$result = pg_Exec ($co, $sql);
	$result = mysql_query($sql,$co);
	//$row=pg_fetch_array($result);
	$row=mysql_fetch_array($result);
	return  $row ['total_peso'];
}
 function unidades_disponibles($num_ingreso,$referencia,$que_retorna,$co,$do) 
   { 
    //1 Se Averigua Cuantas llegaron
	$sql="
				SELECT * 
				FROM  entradas_ordenes_ingresos
				WHERE   num_ingreso =$num_ingreso AND
				num_referencia=$referencia
				AND  orden = '$do'
				";
				
				    
                 $result   =mysql_query($sql,$co);
                 $row      =mysql_fetch_array($result); 
				 
       $ingreso= $row ['cantidad_ingreso'];
  
	
	//2. se averigua cuantas han retirado
	
	$sql="
						SELECT sum(cantidad)  AS retiradas
						FROM  ordenes_ingresos_salidas
						WHERE   ingreso 	=$num_ingreso AND
						        referencia	=$referencia AND
								orden= $do
						";
				 
						   
                   $result   =mysql_query($sql,$co);
                 	$row     =mysql_fetch_array($result); 
       $retiro= $row ['retiradas'];
	   
	   if($que_retorna==1){return  $ingreso - $retiro;}else{return  $retiro;}
	  
  
	
	}	 


function tiene_ingreso($do,$ingreso,$con){
$sql="SELECT sum(cantidad_ingreso) as cantidad
		FROM entradas_ordenes_ingresos
		where orden='$do' AND
		num_ingreso= $ingreso";

	$result=mysql_query($sql,$con);
	$row=mysql_fetch_array($result);
	return $row ['cantidad'];
	
}

function es_cliente($cliente,$con){

$sql="SELECT numero_documento 
	  FROM  clientes
	  WHERE numero_documento='$cliente'";

	$result=mysql_query($sql,$con);
	$row=mysql_fetch_array($result);
	return mysql_num_rows($result);
	
}
function nombre_referencia1($refe,$con){
$sql="SELECT *
		FROM ref_articulos
		where id ='$refe' ";
	
	$result=mysql_query($sql,$con);
	$row=mysql_fetch_array($result);
	return $row ['nombre'];
}


function nombre_referencia($refe,$cliente,$con){
	$sql="SELECT *
		FROM ref_articulos
		where id ='$refe' AND cliente='$cliente'";
	//$result=pg_Exec($con,$sql);
	$result=mysql_query($sql,$con);
	//$row=pg_fetch_array($result);
	$row=mysql_fetch_array($result);
	return $row ['nombre'];
}
		
function insertar_referencia($num_ref,$nom_ref,$cliente,$co,$ref_prove='99',$unidad=1,$pre_venta, $fecha, $serial, $largo, $ancho, $alto, $tipo_referencia){

    if (empty($fecha))
	$fecha_expiracion = 0;
	else $fecha_expiracion = 1;
	
	if (empty($serial))
	$serial = 0;
	else $serial = 1;
	
	$existe=verifica_id($num_ref,'ref_articulos',$cliente,'comodin',$co);
	if($existe==0){
		//insertar subreferencia
		insertar_subreferencia(0,'SIN DETERMINAR',$num_ref,$cliente,$co);
		$sql="INSERT INTO ref_articulos(id,
										nombre,
										observaciones,
										cliente,
										ref_prove,
										unidad,
										presentacion_venta,
										fecha_expira,
										serial,
										alto,
										largo,
										ancho,
										tipo)
			  VALUES('$num_ref',
					'$nom_ref',
					'',
					'$cliente',
					'$ref_prove',
					'$unidad',
					'$pre_venta',
					 $fecha_expiracion,
					 $serial,
					 $alto,
					 $largo,
					 $ancho,
					 $tipo_referencia)";
		
		if(!$query=mysql_query($sql,$co)){
			$mensaje= 'Problemas al intentar guardar el do';
		}
		else{}
	}//fin si existe
	else{ 
		?>
		<script>
			alert('El codigo ingrezado ya existe en la BD');
		</script>
		<?
	}
}

function actualiza_referencia($num_ref,$nombre,$cliente, $co,$ref_prove,$unidad, $pre_venta, $fecha, $serial, $largo, $ancho, $alto,$tipo_referencia_vista) 
{ 

           if (empty($fecha))
	       $fecha_expiracion = 0;
	       else $fecha_expiracion = 1;
	
	       if (empty($serial))
	       $serial = 0;
	       else $serial = 1;

    		$sql="
			UPDATE ref_articulos
			set nombre          		='$nombre',
				ref_prove           	='$ref_prove',
				unidad          		='$unidad',
				presentacion_venta      ='$pre_venta',
				largo                   =$largo,
				ancho                   =$ancho,
				alto                    =$alto,
				fecha_expira            =$fecha_expiracion,
				serial                  =$serial,
				tipo                    =$tipo_referencia_vista
				
  			WHERE id		='$num_ref'
			AND cliente		='$cliente'" ;          
	
           //if (!$query = pg_Exec ($co, $sql))
		   if (!$query = mysql_query ($sql,$co))
            {
            $mensaje= 'Problemas al intentar guardar el do  '; 
            }else{}
	}
	 function borrar_subreferencia($num_ref,$cliente,$co) 
   			{ 

    		$sql="
			DELETE FROM subreferencia
		    WHERE id='$num_ref'
			AND cliente='$cliente'" ;
		
           //if (!$query = pg_Exec ($co, $sql))
		   if (!$query = mysql_query ($sql,$co))
            {
            $mensaje= 'Problemas al intentar guardar el do  '; 
            }else{}
	}
	 function actualiza_subreferencia($num_subref,$nombre,$co,$ref,$cliente) 
   			{ 

    		$sql="
			UPDATE subreferencia
            set nombre ='$nombre'
  			WHERE id='$num_subref' AND refe='$ref' AND cliente='$cliente'" ;
			
           //if (!$query = pg_Exec ($co, $sql))
		   if (!$query = mysql_query ($sql,$co))
            {
            $mensaje= 'Problemas al intentar guardar el do  '; 
            }else{}
	}
	
	 function insertar_subreferencia($num_subref,$nom_subref,$refere,$cliente,$co) 
   			{ 
        $existe=verifica_id($num_subref,'subreferencia',$cliente,$refere,$co);
			//echo '<br>hahhhaq'.$existe.' _'.$num_subref.' _'.$cliente.' _'.$refere.'<br>';
			
			if($existe == 0)
			{
   
 
	$sql="
		INSERT INTO subreferencia(
		id ,
  		nombre ,
  		refe,
		cliente )
 
 
		VALUES(
 		'$num_subref' ,
 		'$nom_subref' ,
 		'$refere',
		'$cliente'
		 
 		
	)" ;

//die();
       			//if (!$query = pg_Exec ($co, $sql))
				if (!$query = mysql_query ($sql,$co))
                {

                 $mensaje= 'Problemas al intentar guardar el do  '; 
                }
              	else{}
		}//fin si existe
		else{
		       
			   ?>
			   <script>
			   alert('El cliente ingrezado ya existe en la BD ');
			   </script>
			    <?
			    }
	}//fin funcion
	
	
	function actualiza_datos_do($do,$id,$operacion,$observaciones,$fecha, $co) 
   			{ 

    		$sql="
			UPDATE ordenes
			set tipo_ingreso 	=$operacion,
				cliente		 	='$id'
				
				
  			WHERE codigo='$do'
			" ;
			//echo $sql;
			
           //if (!$query = pg_Exec ($co, $sql))
		   if (!$query = mysql_query ($sql,$co))
            {
            $mensaje= 'Problemas al intentar guardar el do  '; 
            }else{}
	}
	
	
	function capturar_error($type, $msg, $file, $line, $context)
{
        switch($type)
        {
                case E_NOTICE:
				       $mensaje =' N Lo sentimos la aplicación ha reportado un procedimiento no valido, intente de nuevo y si el error persiste comuniquese con el administrador  ';
                        break;
 
                case E_WARNING:
				$error=1;
						
						$mensaje =' N Lo sentimos la aplicación ha reportado un procedimiento no valido, intente de nuevo y si el error persiste comuniquese con el administrador  ';
                 break;
                case E_ERROR:
				//$error=1;
						$mensaje ='E Lo sentimos la aplicación ha reportado un procedimiento no valido, intente de nuevo y si el error persiste comuniquese con el administrador  ';
					    break;
		

        }
		if($error==1)
		{
		
	 ?>
<div align="center">
  <table width="75%" border="0">
    <tr bgcolor="#CCFFFF"> 
      <td colspan="2"><font color="#FF0000" face="Verdana, Arial, Helvetica, sans-serif">ERROR</font><font face="Verdana, Arial, Helvetica, sans-serif"><? echo "[$type]" ?></font></td>
    </tr>
    <tr> 
      <td width="7%" height="34"><b><font color="#32378C" size="1" face="Verdana, Arial, Helvetica, sans-serif"><font color="#0000FF"><img src="../imagenes/alert_50x50.gif" width="50" height="50" /></font></font></b></td>
      <td width="93%"> <? echo $mensaje ?> 
        <div align="center"></div>
        <div align="center"></div></td>
    </tr>
  </table>
  <?
   die();
  }
 	
}

	function nombre_tipo_documento($id,$co)
   {
   $sql="
						SELECT *
						FROM  tipos_documentos
						WHERE  codigo  ='$id'
						";
				
				   //$result = pg_Exec ($co, $sql);
				   $result = mysql_query ($sql,$co);
				   //$row = pg_fetch_array($result);
				   $row = mysql_fetch_array($result);
				 
       return $row ['nombre'];
   }
   function nombre_departamento($id,$co)
   {
   $sql="
						SELECT *
						FROM  departamentos
						WHERE  codigo  ='$id'
						";
				
				   	//$result = pg_Exec ($co, $sql);
				   	$result = mysql_query ($sql,$co);
					//$row = pg_fetch_array($result);
					$row = mysql_fetch_array($result);
				 
       return $row ['nombre'];
   }
   function nombre_deposito($id,$co)
   {
   $sql="
						SELECT *
						FROM  depositos
						WHERE  codigo  ='$id'
						";
				
				   	//$result = pg_Exec ($co, $sql);
				   	$result = mysql_query ($sql,$co);
					//$row = pg_fetch_array($result);
					$row = mysql_fetch_array($result);
				//echo $sql; 
       return $row ['nombre'];
   }
      
	function insertar_ciudad($codigo,$codigo_departamento,$nombre,$conexion)
   {

    $cantidad= ya_existe_en_BD('codigo','departamento',$codigo,$codigo_departamento,'ciudades',$conexion);   
	if($cantidad > 0)//validacion para verificar que el registro ha insertar no existe
	 {
	 ?>
	  <script>
	  		alert('El codigo ingrezado ya existe  en la BD')
	  		location="regiones_ciudades.php"
	  </script>
	  <? 
	 }
	 $sql="
		INSERT INTO ciudades(
		codigo, 
       	nombre,
		departamento
		
		)
		VALUES(
		        '$codigo', 
       			'$nombre',
				'$codigo_departamento'
				
				)" ;
	
//echo $sql;	
		//if (!$query = pg_Exec ($conexion, $sql))
		if (!$query = mysql_query ($sql,$conexion))
 		{  ?>
     	 <script> alert('Problemas al Insertar datos')</script>
    	<? 
		exit();
 		}
        else{?> <? }
   }
   function insertar_departamento($codigo,$codigo_pais,$nombre,$conexion)
   {
   $cantidad=ya_existe_en_BD('codigo','pais',$codigo,$codigo_pais,'departamentos',$conexion);
	 
	 if($cantidad > 0)//validacion para verificar que el registro ha insertar no existe
	 {
	 ?>
	  <script>
	  		alert('El codigo ingrezado ya existe  en la BD')
	  		location="regiones_ciudades.php"
	  </script>
	  <? 
	 }
	 $sql="
		INSERT INTO departamentos(
		codigo, 
       	nombre,
		pais
		
		)
		VALUES(
		        '$codigo', 
       			'$nombre',
				'$codigo_pais'
				
				)" ;
	
//echo $sql;	
		//if (!$query = pg_Exec ($conexion, $sql))
		if (!$query = mysql_query ($sql,$conexion))
 		{  ?>
     	 <script> alert('Problemas al Insertar datos')</script>
    	<? 
		exit();
 		}
        else{?> <? }
   }
    function ya_existe_en_BD($pk_name1,$pk_name2,$pk1,$pk2,$tabla,$co)//funcion para verificar la llave compuesta de una tabla antes de insertar un registro 
   { 
    
	$sql="
						SELECT * 
						FROM  ".$tabla."
						WHERE    ".$pk_name1." =$pk1 AND
						".$pk_name2."=$pk2";
						//echo $sql;
				
				   //$result = pg_Exec ($co, $sql);
				   $result = mysql_query($sql,$co);
				   //$cantidad = pg_numrows($result); 
				   $cantidad = mysql_numrows($result); 
				 
       return $cantidad;
  
	}
	
	
	function consecutivo_salidas($sede,$anio,$co)
   {
   				$sql="
				SELECT consecutivo + 1 as nuevo_consecutivo
				FROM  consecutivo_salidas
				WHERE  sede  ='$sede' AND 
				anio=$anio
						";
						
				//$result   = pg_Exec ($co, $sql);
				$result   =mysql_query($sql,$co);		   
				//$row      =pg_fetch_array($result);
			     $row      =mysql_fetch_array($result); 
				$nuevo=$row [nuevo_consecutivo];
				$sql="
				UPDATE consecutivo_salidas
				set consecutivo =$nuevo
  				WHERE  sede  ='$sede' AND 
				anio=$anio 
				
				" ;
			//if (!$query = pg_Exec ($co, $sql))
           if (!$query=mysql_query($sql,$co))
            {
            $mensaje= 'Problemas al intentar guardar   '; 
            }else{} 
				 
       return $row ['nuevo_consecutivo'];
   }
   
   
   function traer_refrencias_ingreso($orden,$ingreso,$embalaje,$co)
   {
   				$sql="SELECT  *
						FROM  entradas_ordenes_ingresos
						WHERE entradas_ordenes_ingresos.indica_salida =0
						AND orden= '$orden'
						AND num_ingreso ='$ingreso'
						AND embalaje=$embalaje

						";
						
				  $result=mysql_query($sql,$co);
				   $suma=0;
				  while($row=mysql_fetch_array($result)){
					$cuanto=unidades_disponibles($ingreso,$row ['num_referencia'],1,$co,$orden) ; 
					
				    $suma=$suma + $cuanto;
				  } 
				 
				  return $suma;
				
   }
   
   function cantidadEnProductoTerminado($arregloDatos,$co)
   {
      $sede = $_SESSION['sede'];
   			$sql="SELECT sum(cantidad) as cantidad
			  	  FROM productos_terminados_detalle
			      WHERE orden			='$arregloDatos[orden]'
				      AND ingreso		='$arregloDatos[ingreso]'
				      AND  referencia	= $arregloDatos[referencia]
					  AND sede          ='$sede'
					  ";
	
		$result   =mysql_query($sql,$co);		   
		 $row     =mysql_fetch_array($result); 
		 return   $row ['cantidad'];		
    }
   
   function	cantidadEnProductoTerminadoRetirada($arregloDatos,$co){
   
    $sede = $_SESSION['sede'];
   	$sql="SELECT  sum(ptd.cantidad) as cantidad 
			  FROM productos_terminados_detalle ptd,
			  		productos_terminados pt		
			  WHERE ptd.producto=pt.codigo 
			  AND   ptd.orden		='$arregloDatos[orden]'
			  AND 	ptd.ingreso		= $arregloDatos[ingreso]
			  AND   ptd.referencia	= $arregloDatos[referencia]
			  AND pt.sede           = '$sede'
			  AND ptd.sede          = '$sede'
			  AND   pt.cantidad_nal + pt.cantidad_ext <=  0
			  GROUP BY  ptd.orden,ptd.ingreso, ptd.referencia
			  ";
		$result   =mysql_query($sql,$co);		   
		 $row     =mysql_fetch_array($result); 
		 return   $row ['cantidad'];
    }
	 
	 function cantidadEnProductoTerminadoR1($arregloDatos,$co) {
   			$sql="SELECT sum(cantidad) as cantidad
			  		FROM productos_terminados_detalle ptd,
						 entradas_ordenes_ingresos eoi,
						 ordenes
					WHERE ptd.orden  			= ordenes.codigo
					AND   ptd.orden				= eoi.orden
					AND   ptd.ingreso			= eoi.num_ingreso
					AND   ptd.referencia		= num_referencia
					AND   eoi.id_referencia		='$arregloDatos[referencia]' 
			        AND   ordenes.cliente		='$arregloDatos[cliente]'";
		$result   =mysql_query($sql,$co);		   
		 $row     =mysql_fetch_array($result);
		 return   $row ['cantidad'];
		 
    }
	 
	 function	cantidadEnProductoTerminadoRetiradaR($arregloDatos,$co){
	
		$sql="SELECT  sum(ptd.cantidad) as cantidad 
			  FROM    productos_terminados_detalle ptd,
					  productos_terminados pt,
					  entradas_ordenes_ingresos eoi,
					  ordenes		
			  WHERE ptd.producto			= pt.codigo 
			  AND   ptd.orden  				= ordenes.codigo
			  AND   ptd.orden				= eoi.orden 
			  AND   ptd.ingreso				= eoi.num_ingreso
			  AND   ptd.referencia			= num_referencia
			  AND   pt.cantidad_nal + pt.cantidad_ext  <=  0
			  AND   eoi.id_referencia		='$arregloDatos[referencia]' 
			  AND   ordenes.cliente			='$arregloDatos[cliente]'
			  GROUP BY ordenes.cliente, ptd.referencia";
			
	$result   =mysql_query($sql,$co);		   
	$row      =mysql_fetch_array($result); 
	return    $row ['cantidad'];
    
	}
	
   
   function consecutivo_remesas($sede,$anio,$co)
   {
   				$sql="
				SELECT consecutivo + 1 as nuevo_consecutivo
				FROM  consecutivo_remesas
				WHERE  sede  ='$sede' AND 
				anio= $anio
						";
						
	$result   =mysql_query($sql,$co);		   
				//$row      =pg_fetch_array($result);
			     $row      =mysql_fetch_array($result); 
				$nuevo=$row ['nuevo_consecutivo'];
				$sql="
				UPDATE consecutivo_remesas
				set consecutivo =$nuevo
  				WHERE  sede  ='$sede' AND 
				anio=$anio
				" ;
			
			//if (!$query = pg_Exec ($co, $sql))
           if (!$query=mysql_query($sql,$co))
            {
            $mensaje= 'Problemas al intentar guardar   '; 
            }else{} 
				 
       return $row ['nuevo_consecutivo'];
	}					
	?>
</div>