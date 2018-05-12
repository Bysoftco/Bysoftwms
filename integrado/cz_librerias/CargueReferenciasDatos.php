<?php

require_once("MYDB.php"); 

class CargueReferencias extends MYDB {

    function CargueReferencias() {
        $this->estilo_error = "ui-state-error";
        $this->estilo_ok = "ui-state-highlight";

        $this->mensaje_error = " error al guardar el registro";
        $this->mensaje_ok = " Se guardo correctamente el registro1x";
    }

    function filtro($arregloDatos) {
        
    }

   function validarCliente($arregloDatos) {
   		$sql = "SELECT * 
				FROM clientes 
				WHERE numero_documento='$arregloDatos[cliente]'
				";
		$this->query($sql);
		if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
		//echo 	$sql."<br>";
        return $this->N;
   }
   
    function validarCliente1(&$arregloDatos) {
   		$sql = "SELECT numero_documento
				FROM clientes
				WHERE  numero_documento='$arregloDatos[cliente]'
				";
		$arregloDatos[sql]=$sql;		
		$this->query($sql);
		if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
		//echo 	$sql."<br>";
        return $this->N;
   }
   
   function validarReferencia(&$arregloDatos) {
   		$sql = "SELECT * FROM tipos_referencias
				WHERE codigo='$arregloDatos[cliente]'
				";
		$arregloDatos[sql]=$sql;		
		$this->query($sql);
		if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
		//echo 	$sql."<br>";
        return $this->N;
   }
     function validarTipoReferencia(&$arregloDatos) {
   		$sql = "SELECT * FROM tipos_referencias
				WHERE codigo='$arregloDatos[cliente]'
				";
		$arregloDatos[sql]=$sql;		
		$this->query($sql);
		if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
		//echo 	$sql."<br>";
        return $this->N;
   }
    function validarUnidadInventaria(&$arregloDatos) {
   		$sql = "SELECT codigo FROM tipos_referencias
				WHERE codigo='$arregloDatos[tipo]'
				";
		$arregloDatos[sql]=$sql;		
		$this->query($sql);
		if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
		//echo 	$sql."<br>";
        return $this->N;
   }
   
   function validarUnidadComercial(&$arregloDatos) {
   		$sql = "SELECT id FROM unidades_medida 
				WHERE id='$arregloDatos[unidad_venta]'
				";
		$arregloDatos[sql]=$sql;		
		$this->query($sql);
		if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
		//echo 	$sql."<br>";
        return $this->N;
   }
   
   function validarGrupoItem(&$arregloDatos) {
   		$sql = "SELECT codigo FROM grupo_items
				WHERE codigo='$arregloDatos[grupo_item]'
				";
		$arregloDatos[sql]=$sql;		
		$this->query($sql);
		if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
		//echo 	$sql."<br>";
        return $this->N;
   }
   
   
   
   
   
    function validarUnidad($arregloDatos) {
   		$sql = "SELECT * 
				FROM unidades_medida 
				WHERE codigo='$arregloDatos[cliente]'
				";
		$this->query($sql);
		if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
		//echo 	$sql."<br>";
        return $this->N;
   }
   
   

    function setReferencia($arregloDatos) {
        $sql = "INSERT INTO referencias 
                        (
                        codigo_ref, 
                        ref_prove,
						nombre,
						observaciones,
						cliente,
						parte_numero,
						unidad,
						unidad_venta,
						fecha_expira,
						vigencia,
						min_stock,
						lote_cosecha,
						alto,
						largo,
						ancho,
						serial,
						tipo,
						grupo_item,
						factor_conversion
						)
                        VALUES 
                        ('$arregloDatos[codigo_ref]',
						 '$arregloDatos[ref_prove]',
						 '$arregloDatos[nombre]',
						 '$arregloDatos[observaciones]',
						 '$arregloDatos[cliente]',
						 '$arregloDatos[parte_numero]',
						  '$arregloDatos[unidad]',
						  '$arregloDatos[unidad_venta]',
						  '$arregloDatos[fecha_expira]',
						  '$arregloDatos[vigencia]',
						  '$arregloDatos[min_stock]',
						  '$arregloDatos[lote_cosecha]',
						  '$arregloDatos[alto]',
						  '$arregloDatos[largo]',
						  '$arregloDatos[ancho]',
						  '$arregloDatos[serial]',
						  '$arregloDatos[tipo]',
						  '$arregloDatos[grupo_item]',
						  '$arregloDatos[factor_conversion]'
						)
                        ";
        $this->query($sql);
		//echo $sql."<BR>";
        if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
        
      
    }

    /*     * FIN* */
}

?>