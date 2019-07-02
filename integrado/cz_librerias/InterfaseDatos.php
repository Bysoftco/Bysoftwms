<?php
require_once("MYDB.php"); 

class Interfase extends MYDB {
  function Interfase() { 
    $this->estilo_error = "ui-state-error";
    $this->estilo_ok = "ui-state-highlight";
  } 
  
  function listarInterfases($arregloDatos) {
    $sql = "SELECT DISTINCT facturas_maestro.fecha_interfase,facturas_maestro.interfase,
              SUM(facturas_detalle.valor) AS valor
            FROM facturas_maestro,facturas_detalle
              WHERE facturas_maestro.codigo=facturas_detalle.factura";
    if(!empty($arregloDatos[interfase_filtro])) {
      $sql .=" AND facturas_maestro.interfase='$arregloDatos[interfase_filtro]'";
    }
    if(!empty($arregloDatos[fechaDesde]) AND !empty($arregloDatos[fechaHasta])) {
      $sql .=" AND facturas_maestro.fecha_interfase >= '$arregloDatos[fechaDesde]' AND facturas_maestro.fecha_interfase <= '$arregloDatos[fechaHasta]'";
    }

    $sql .=" GROUP BY facturas_maestro.fecha_interfase,facturas_maestro.interfase 
                 ORDER BY fecha_interfase DESC";
    $this->query($sql);
    if($this->_lastError) {
      $this->mensaje = "error al borrar mercancia del levante ";
      $this->estilo = $this->estilo_error;
      return TRUE;
    }
  }
    
  function cerrarInterfase($arregloDatos) {
    $fecha = date('Y-m-d:a');
    $sede = $_SESSION['sede'];
    $sede = 11;
    $sql = "UPDATE facturas_maestro SET interfase = '$arregloDatos[interfase_filtro]',
              fecha_interfase	= '$fecha'
            WHERE interfase	= '0' AND sede = '$sede' AND cerrada	= 1 AND anulada= 0";

    $this->query($sql);
    $arregloDatos[sql] = htmlentities($sql, ENT_QUOTES);

    if($this->_lastError) {
      $this->auditoria($arregloDatos, 'error', 'cerrarInterfase');
      echo "<div class=error align=center> :( Error al Crear la Interfase <br>$sql</div>.";  
      return FALSE;
    }
  }
	
  function existeInterfase($arregloDatos) {
	  $sede = $_SESSION['sede'];
    
	  $sql = "SELECT interfase FROM facturas_maestro
            WHERE interfase	= '$arregloDatos[interfase_filtro]'
              AND sede = '$sede'";

		$this->query($sql);
		$arregloDatos[sql] = htmlentities($sql, ENT_QUOTES);
		
		if($this->_lastError) {
      $arregloDatos[mensaje] = "Error al Buscar la Interfase";
    }
  }      

  function getInterfase($arregloDatos) {
    $sede = $_SESSION['sede'];
    $sql="SELECT DISTINCT fd.codigo, fd.factura, fm.numero_oficial, fm.fecha_factura,
            fm.fecha_salida, fm.iva as ivam, fm.rte_fuente AS rte_fuentem,
            fm.rte_ica AS rte_icam, fm.rte_iva AS rte_ivam, fm.anticipo, fm.valor_anticipo,
            fm.efectivo, fm.cheque, fm.banco, fm.recibo, fm.vendedor, fm.interfase,
            fm.fecha_interfase, fm.centro_costo, fm.subcentro_costo, fm.orden, fm.pedido,
            fm.intermediario, servicios.codigo AS idservicio, servicios.tipo AS tipo_tercero,
            fd.concepto, fd.base, fd.valor, fd.valor_unitario, fm.subtotal, fm.total,
            servicios.cuenta, fd.iva, fd.rte_fuente AS rte_fuented, fm.efectivo, fm.banco,
            fm.credito, fm.cheque, fd.rte_iva, fd.rte_ica, clientes.numero_documento AS nit,
            clientes.razon_social, clientes.razon_social AS nombre_cliente, clientes.tipo, 
            clientes.cuenta AS cuenta_filial, servicios.nombre AS nombreservicio,
            servicios.naturaleza
          FROM facturas_detalle fd, facturas_maestro fm, servicios, clientes
          WHERE fd.concepto = servicios.codigo
            AND clientes.numero_documento = fm.cliente		
            AND fm.codigo = fd.factura
            AND fm.sede	= servicios.sede
            AND fm.sede = '$sede'";

    // 07032009   Las facturas en dolares no van a la interface
    if(!empty($arregloDatos['num_interfase'])) {
      $sql.=" AND fm.interfase='$arregloDatos[num_interfase]' ORDER BY fd.factura";
    }

    $this->query($sql);
    $arregloDatos[sql] = htmlentities($sql, ENT_QUOTES);

    if($this->_lastError) {
      echo "<div class=error align=center> :( Error al Consultar Factura para interfase <br>$sql</div>.";
      $this->_lastError = 0;
      $this->auditoria($arregloDatos, 'error', 'interfase');
      return FALSE;
    }
    return TRUE;
	}
        
  function otrosConceptos($arregloDatos) {
    $sede = $_SESSION['sede'];
    set_time_limit(0);
		$sql = "SELECT codigo, nombre, cuenta, tipo, naturaleza, iva, rte_ica, rte_fuente, rte_cree
            FROM servicios
            WHERE (tipo	>= 1 OR codigo = 8)
              AND sede = '$sede'
              AND tipo = $arregloDatos[tipo_servicio]
			      ORDER BY codigo ASC";

		$this->query($sql);
		if($this->_lastError) {
      $arregloDatos[sql] = htmlentities($sql, ENT_QUOTES);
			echo "<div class=error align=center> :( Error al Consultar Conceptos del tipo $arregloDatos[tipo_servicio] <br>$arregloDatos[sql]</div>.";
			return FALSE;
		}
	}
        
  function conceptosTipos($arregloDatos) {
	  $sede = $_SESSION['sede'];
	  set_time_limit(0);
	  $sql = "SELECT DISTINCT tipo FROM servicios
            WHERE (tipo	>= 1 AND tipo <= 9)
              AND sede = '$sede'
            ORDER BY tipo ASC";
	
    $this->query($sql);
    $arregloDatos[sql] = htmlentities($sql, ENT_QUOTES);
		 
		if($this->_lastError) {
			$this->auditoria($arregloDatos, 'error', 'conceptosAdicionales');
			echo "<div class=error align=center> :( Error al Consultar Conceptos Adicionales <br>$sql</div>.";
			return FALSE;
		}
	}
        
  function traerIvaTerceros($arregloCampos) {	
    $sede = $_SESSION['sede'];
    $sql = "SELECT dr.nit_tercero, dr.factura_detalle,
              SUM(dr.iva) AS iva,
              MAX(clientes.tipo) as tipo,
              SUM(dr.valor) AS valor
            FROM detalle_retenciones dr, facturas_maestro fm, clientes
            WHERE fm.factura = dr.factura
              AND clientes.numero_documento = dr.nit_tercero 
              AND fm.numero_oficial = '$arregloCampos[numero_oficial]'
              AND fm.sede	= '$sede'
            GROUP BY dr.nit_tercero, dr.factura_detalle" ;
					
    $this->query($sql);
    $arregloDatos[sql] = htmlentities($sql, ENT_QUOTES);
			
    if($this->_lastError) {
      $this->auditoria($arregloDatos, 'error', 'traerIvaTerceros');
      echo "<div class=error align=center> :( Error al Consultar Retenciones en la Factura <br>$sql</div>.";
      return FALSE;
    }		
  }
		
  function traerDetalle($arregloCampos) {
    set_time_limit(0);
    $sede = $_SESSION['sede'];
    $sql = "SELECT SUM(valor) AS valor, SUM(fd.iva) AS iva
			      FROM facturas_detalle fd, facturas_maestro fm 
            WHERE fd.factura = fm.codigo
              AND fm.numero_oficial = '$arregloCampos[numero_oficial]' 
              AND fm.sede = fd.sede
              AND fm.sede = '$sede'";
				   
		if(!empty($arregloCampos[prorcentaje_iva])) {
		  $sql.=" AND fd.iva = $arregloCampos[prorcentaje_iva]";
		}
		if(!empty($arregloCampos[prorcentaje_rte])) {
		  $sql.=" AND fd.rte_fuente	= $arregloCampos[prorcentaje_rte]";
		}
		if(!empty($arregloCampos[prorcentaje_ica])) {
		  $sql.=" AND fd.rte_ica = $arregloCampos[prorcentaje_ica]";
		}
    if($arregloCampos[cal_riva]) {
      $sql.=" GROUP BY fd.codigo ";
		}
		if($arregloCampos[prorcentaje_cree] > 0) {
      echo $sql."<BR>";
		}

		$this->query($sql);
		
		if($arregloCampos[cal_riva]) {
      // Cuando es Rete Iva no se devuelve valores pues se calculan en el método
      return 0;
		} else {
			$this->fetch();
		}
		
		if(empty($this->valor)) {
			$this->valor = 0;
		}

		$arregloDatos[sql] = htmlentities($sql, ENT_QUOTES);
    if($this->_lastError) {
		  $this->auditoria($arregloDatos, 'error', 'traerDetalle');
		  echo "<div class=error align=center> :( Error al Consultar Detalle Factura <br>$sql</div>.";
		  return FALSE;
		}
                //echo "xx".$this->valor."<br>";
		return $this->valor;
	}

  function traerBaseCree($arregloCampos) {
    set_time_limit(0);
    $sede = $_SESSION['sede'];
    $sql = "SELECT SUM(valor) AS valor FROM facturas_detalle fd, facturas_maestro fm 
            WHERE fd.factura = fm.codigo
              AND fm.numero_oficial = '$arregloCampos[numero_oficial]' 
              AND fm.sede = fd.sede
              AND fm.sede = '$sede'
              AND fd.rte_cree = $arregloCampos[prorcentaje_cree]" ;
	
    $this->query($sql);
    $this->fetch();
    if(empty($this->valor)) {
      $this->valor = 0;
    }

		$arregloDatos[sql] = htmlentities($sql, ENT_QUOTES);
    if($this->_lastError) {
		  $this->auditoria($arregloDatos, 'error', 'traerDetalle');
		  echo "<div class=error align=center> :( Error al Consultar Detalle Factura <br>$sql</div>.";
		  return FALSE;
		}
		return $this->valor;
	}
	
	function traerRteIca($arregloCampos) {
	  $sede = $_SESSION['sede'];
	  $sql = "SELECT fd.* FROM facturas_detalle fd, facturas_maestro fm
            WHERE fd.factura = fm.factura
              AND fm.numero_oficial = '$arregloCampos[numero_oficial]'
              AND fd.rte_ica = $arregloCampos[porcentaje_rete_ica] 
              AND fm.sede = '$sede'";
		
		$this->query($sql); 
		$arregloDatos[sql] = htmlentities($sql, ENT_QUOTES);
		
    if($this->_lastError) {	
		  $this->auditoria($arregloDatos, 'error', 'traerRteIca');
		  echo "<div class=error align=center> :( Error al Consultar Detalle Factura <br>$sql</div>.";
		  return FALSE;
		}
	}

	function traerRteFuente($arregloCampos) {
    $sede = $_SESSION['sede'];
	  set_time_limit(0);
	  $sql = "SELECT fd.* FROM facturas_detalle fd, 
            WHERE fd.factura = fm.factura
              AND fm.numero_oficial = '$arregloCampos[numero_oficial]'
              AND fd.rte_fuente > 0 
              AND fm.sede = '$sede'";
    
    $this->query($sql); 
    if($this->_lastError) {	
			echo 'error';
			echo "<div class=error align=center> :( Error al Consultar Detalle Factura <br>$sql</div>.";
			return FALSE;
		}
	}
        
  function conceptosAdicionales($arregloDatos) {
    $sede = $_SESSION['sede'];

    $sql="SELECT DISTINCT naturaleza, tipo FROM servicios
          WHERE (tipo	>=1 OR codigo = 8)
            AND sede = '$sede'
          ORDER BY codigo ASC";
	
    $this->query($sql);
		if($this->_lastError) {
			$this->warn('conceptosAdicionales'.htmlentities($sql, ENT_QUOTES));
			$this->logger->warn($this->_lastError->getMessage());
			echo "<div class=error align=center> :( Error al Consultar Conceptos Adicionales <br>$sql</div>.".$this->getMessage();
			return FALSE;
		}
	}
	
  function getBase($arregloDatos) {
    $sede = $_SESSION['sede'];
    $sql = "SELECT iva,rte_iva FROM facturas_maestro where numero_oficial= '$arregloDatos[numero_oficial_ant]'";
	
    $this->query($sql);
		if($this->_lastError) {
			$this->warn('conceptosAdicionales'.htmlentities($sql, ENT_QUOTES));
			$this->logger->warn($this->_lastError->getMessage());
			echo "<div class=error align=center> :( Error al Consultar TETE IVA <br>$sql</div>.".$this->getMessage();
			return FALSE;
		}
  }
}  
?>