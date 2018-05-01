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