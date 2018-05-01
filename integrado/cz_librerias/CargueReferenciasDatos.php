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

    function setReferencia($arregloDatos) {
        $sql = "INSERT INTO referencias 
                        (
                        codigo_ref, 
                        ref_prove,
						nombre
						)
                        VALUES 
                        ('$arregloDatos[codigo_ref]',
						 '$arregloDatos[ref_prove]',
						 '$arregloDatos[nombre]'
						)
                        ";
        $this->query($sql);
		//echo $sql;
        if ($this->_lastError) {
            echo "<div class=error>" . 'Error al crear el documento<BR>' . $sql . "<BR></div>";
            $this->_lastError = FALSE;
        }
        
      
    }

    /*     * FIN* */
}

?>