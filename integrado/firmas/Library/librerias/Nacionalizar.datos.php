<?php

	require_once("CLIENTE.php"); 
	require_once(LOGERRORES . "/LoggerManager.php");
	class Nacionalizar extends  DOCliente {
	var $logger;
		function Nacionalizar() {
			$user=$_SESSION['usuario'];
			$this->logger =& LoggerManager::getLogger('Nacionalizar '.$user);
    	} 
		
		
	    
	 function lista($tabla,$codigo,$nombre,$orden='nombre') {
		$sede=$_SESSION['sede'];
		$unaLista= new Nacionalizar();
        $sql = "SELECT $codigo,$nombre 
				FROM $tabla";
				
				
		if($tabla=='subcentro_costo')	
		{
		  $sql.=" WHERE sede = $sede";
		}	
		$sql.=" order by $orden";	
        
    	$unaLista->query($sql);
		$arreglo = array();
		while($unaLista->fetch())
		{
		  $arreglo[$unaLista->$codigo] = $unaLista->$nombre.'['.trim($unaLista->$codigo).']';
		}
		return $arreglo;
        
    }
	
	function verDetalleLevante($arregloDatos){
	
		$sql="SELECT  lm.levante,
					  lm.fecha,
					  ld.orden,
					  ld.ingreso,
					  eoi.subreferencia as lote,
					  ld.cantidad,
					  ordenes.documento,
					  clientes.razon_social as sia,
					  ref.nombre as nombre_referencia
				  FROM levantes_maestro lm,levantes_detalle ld,
				  	   clientes,ordenes,entradas_ordenes_ingresos eoi,
					   ref_articulos ref
				  WHERE   lm.levante=ld.levante
				  AND clientes.numero_documento=lm.sia
				  AND ld.orden=ordenes.codigo
				  AND eoi.orden			=ld.orden 
				  AND eoi.num_ingreso 	=ld.ingreso
				  AND eoi.num_referencia	=ld.referencia
				  AND ref.id		=eoi.id_referencia
				  AND ref.cliente	=ordenes.cliente
	        	  ";
			if(!empty($arregloDatos[fechaDesde]) and !empty($arregloDatos[fechaHasta])){
				$sql.=" AND lm.fecha >= '$arregloDatos[fechaDesde]'  AND lm.fecha <= '$arregloDatos[fechaHasta]'";
			}
			if(!empty($arregloDatos[levante])){
				$sql.=" AND ld.levante like  CONVERT( _utf8 '%$arregloDatos[levante]%' USING latin1 ) ";
			}
			if(!empty($arregloDatos[id])){
				$sql.=" AND ordenes.cliente =$arregloDatos[id] ";
			}
			  
			//echo $sql;
	  		$this->query($sql);
	  		if($this->_lastError)
	  		{
	      		echo "<div class=error align=center> :( Error al consultar Levantes <br> $sql </div>";
		  		return FALSE;
	  		}
			
	}
		
	function listaLevantes($arregloDatos){
	  		
			$sql="SELECT DISTINCT  lm.levante,
						 lm.fecha,
						 lm.fmmn,
						 clientes.razon_social as sia
				  FROM levantes_maestro lm,levantes_detalle ld,
				  	   clientes,ordenes
				  WHERE   lm.levante=ld.levante
				  AND clientes.numero_documento=lm.sia
				  AND ld.orden=ordenes.codigo
	        	  ";
			if(!empty($arregloDatos[fechaDesde]) and !empty($arregloDatos[fechaHasta])){
				$sql.=" AND lm.fecha >= '$arregloDatos[fechaDesde]'  AND lm.fecha <= '$arregloDatos[fechaHasta]'";
			}
			if(!empty($arregloDatos[levante])){
				$sql.=" AND ld.levante like  CONVERT( _utf8 '%$arregloDatos[levante]%' USING latin1 ) ";
			}
			if(!empty($arregloDatos[id])){
				$sql.=" AND ordenes.cliente =$arregloDatos[id] ";
			}
			  
			//echo $sql;
	  		$this->query($sql);
	  		if($this->_lastError)
	  		{
	      		echo "<div class=error align=center> :( Error al consultar Levantes <br> $sql </div>";
		  		return FALSE;
	  		}
			 		 
	}
	
	function reportarLevanteCabeza($arregloDatos){
		$fecha=FECHA;
		$sql="INSERT INTO levantes_maestro
						  (levante,fecha,sia,fmmn,fecha_levante,stiker)
				    VALUES('$arregloDatos[levante]','$fecha',$arregloDatos[sia],'$arregloDatos[fmmn]','$arregloDatos[fecha_levante]','$arregloDatos[stiker]')";
		$this->query($sql);
     	if($this->_lastError){
	      echo "<div class=error align=center> :( Error al reportar cabeza de levante.<br> $sql </div>";
		  return FALSE;
	    }	
	}
	
	
	function reportarLevanteCuerpo($arregloDatos){
	//Temporal
		if(empty($arregloDatos[cifSN])){
			$arregloDatos[cifSN]=0;
		}
		$sql="INSERT INTO levantes_detalle
					      (orden,ingreso,referencia,levante,cantidad,peso,cif)
					 VALUES($arregloDatos[num_do],$arregloDatos[arribo],$arregloDatos[num_referencia],'$arregloDatos[levante]',$arregloDatos[cantidadSN],$arregloDatos[pesoSN],$arregloDatos[cifSN])";
		$this->query($sql);
     	if($this->_lastError){
	      echo "<div class=error align=center> :( Error al reportar el cuerpo del levante.<br> $sql </div>";
		  return FALSE;
	    }	
	}
	
	function reportarCantidadNacionalizadaReferencias($arregloDatos){
		//var_dump($arregloDatos);
		
		$sql="UPDATE entradas_ordenes_ingresos 
					SET disponibles			=disponibles		+$arregloDatos[cantidadSN],
						no_disponibles		=no_disponibles		-$arregloDatos[cantidadSN],
						peso_disponible 	=peso_disponible	+$arregloDatos[pesoSN],
						peso_guia 			=peso_guia			-$arregloDatos[pesoSN],
						fob_ref_extranjero	=fob_ref_extranjero	-$arregloDatos[cifSN],
						fob_ref_nacional	=fob_ref_nacional	+$arregloDatos[cifSN]
						
			   WHERE orden 					=$arregloDatos[num_do]
				      AND  num_ingreso      =$arregloDatos[arribo]
					  AND  num_referencia 	=$arregloDatos[num_referencia]			";
		$this->query($sql);
     	if($this->_lastError)
	    {
	      echo "<div class=error align=center> :( Error al reportar nacionalizacion en el inventario.<br> $sql </div>";
		  return FALSE;
	    }
	}
	
	function reportarCantidadNacionalizadaInventario($arregloDatos){
		if(empty($arregloDatos[cantidadSN])){$arregloDatos[cantidadSN]=0;}
		
		$sql="UPDATE entradas_ordenes_ingresos
				SET   qen_rotacion_extrangera 	=	qen_rotacion_extrangera -$arregloDatos[cantidadSN],
					  qen_rotacion_nacional 	= 	qen_rotacion_nacional+$arregloDatos[cantidadSN],
					  peso_rotacion_extranjero	=	peso_rotacion_extranjero-$arregloDatos[pesoSN],
					  peso_rotacion_nacional	=	peso_rotacion_nacional+$arregloDatos[pesoSN],
					  cif_rotacion_extranjera	=	cif_rotacion_extranjera-$arregloDatos[cifSN],
					  cif_rotacion_nacional		=	cif_rotacion_nacional+$arregloDatos[cifSN]
				WHERE orden 				=$arregloDatos[num_do]
				      AND  num_ingreso      =$arregloDatos[arribo]
					  AND  num_referencia 	=$arregloDatos[num_referencia]";
		//echo $sql;
		$this->query($sql);
     	if($this->_lastError)
	    {
	      echo "<div class=error align=center> :( Error al reportar mercancia nacionalizada en el inventario.<br> $sql </div>";
		  return FALSE;
	    }	
	}
	
	
	function reportarCantidadNacionalizada($arregloDatos){
		if(empty($arregloDatos[cantidadSN])){$arregloDatos[cantidadSN]=0;}
		$this->reportarCantidadNacionalizadaInventario($arregloDatos);
		$sql="UPDATE rotaciones_movimientos
				SET cantidad_nacional	=cantidad_nacional		+$arregloDatos[cantidadSN],
					cantidad_extranjera =cantidad_extranjera	-$arregloDatos[cantidadSN],
					peso_nacional		=peso_nacional 			+$arregloDatos[pesoSN],
					peso_extranjero		=peso_extranjero 		-$arregloDatos[pesoSN],
					cif_nacional		=cif_nacional 			+$arregloDatos[cifSN],
					cif_extranjero		=cif_extranjero 		-$arregloDatos[cifSN]
				WHERE codigo 				='$arregloDatos[id_rotacion]'";
		//echo $sql;
		$this->query($sql);
     	if($this->_lastError)
	    {
	      echo "<div class=error align=center> :( Error al reportar mercancia nacionalizada.<br> $sql </div>";
		  return FALSE;
	    }	
	}
	
	function listadoParaNacionalizar($arregloDatos){
		
		$sql="SELECT rm.codigo as id_rotacion,
					 rm.orden,
					 rm.ingreso as num_ingreso,
					 rm.referencia as num_referencia,
					 rm.subreferencia,
					 rm.estado,
					 re.nombre as nombre_estado,
					 rm.ubicacion,
					 rm.cantidad_nacional,
					 rm.cantidad_extranjera,
					 rm.peso_nacional,
					 rm.peso_extranjero,
					 rm.cif_nacional,
					 rm.cif_extranjero,
					 rm.estatus,
					 rm.fecha,
					 eoi.id_referencia,
					 eoi.subreferencia as lote,
					 ordenes.cliente,
					 ordenes.documento,
					 bodegas.nombre as  nombre_ubicacion  
			  FROM rotaciones_movimientos  rm,
			  	   entradas_ordenes_ingresos eoi,
				   ordenes,
				   rotaciones_estados re,
				   bodegas
			  		
			  WHERE cantidad_extranjera > 0 
			    AND eoi.orden			=rm.orden 
				AND eoi.num_ingreso 	=rm.ingreso
				AND eoi.num_referencia	=rm.referencia
				AND ordenes.codigo		=rm.orden 
				AND rm.estado			=re.codigo
				AND bodegas.codigo		=rm.ubicacion";
		if(!empty($arregloDatos[num_do])){
			$sql.=" AND rm.orden= '$arregloDatos[num_do]'";
		}  
		//echo $sql;
		$this->query($sql);
     	if($this->_lastError)
	    {
	      echo "<div class=error align=center> :( Error al traer Mercancia en rotacion.<br> $sql </div>";
		  return FALSE;
	    }	  
	}
	
	function nombreReferencia($arregloDatos){
	  		
			$sql="SELECT nombre 
				  FROM ref_articulos 
	        	  WHERE  cliente  = '$arregloDatos[cliente]'
				  AND    id = '$arregloDatos[id_referencia]'";
			//echo $sql;
	  		$this->query($sql);
	  		if($this->_lastError)
	  		{
	      		echo "<div class=error align=center> :( Error al consultar el nombre de la referencia <br> $sql </div>";
		  		return FALSE;
	  		}
			$this->fetch();
			//echo 'xxx'.$sql;
			return $this->nombre ; 		 
	}

	
	
} 
?>