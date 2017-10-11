<?php
Class Archivo
{
    /* Variables publicas */
    var $nombreCompleto;    // Path + Filename + Ext , Nombre del archivo actual inclulle ruta   
    var $directorio;        // Path ruta absoluta
    var $nombre;            // Filename + Ext,  nombre del archivo con extención
    var $tamano;            // size en bytes  tamañ6 del archivo
    var $extension;         // Extencion
    var $fechaModificacion; // Tipo Date fecha de modificación
    var $tipo;              // TEXTO, BINARIO        
    var $contenido;         // Contenido texto del archivo
    var $fp;                // File pointer,  identificador del archivo
    var $permiso;           // Permite modificar los archivos
    var $_debug = false;    // TRUE, FALSE
    /* Variables privadas */
    var $_errorCode = 0;
    var $_error = '';
    var $_success = '';

    /* Este Arreglo representa las extenciones  de textovalidas del tipo de archivo que
    se pueden subir se utiliza en el metodo cargarInformacion y subir*/
   var $extensionesTexto = array('txt',
        'htm',
        'html',
        'hpml',
        'inc',
        'dat',
        'xml',
        'php'
        );

    /* Este Arreglo define las equivalencias de los caractere especiales a
    remplazar*/
   var  $arregloTildes = array ('á' => 'a',
        'é' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ú' => 'u',
        ' ' => '_',
        'Á' => 'A',
        'É' => 'E',
        'Í' => 'I',
        'Ó' => 'O',
        'Ú' => 'U',
        'ñ' => 'n',
        'Ñ' => 'N'
        );

    /*Methodos publicos.*/

    /**
     * Archivo::Archivo()
     * 
     * Contructora de la clase
     * 
     * @return Archivo 
     */
    function Archivo()
    {
        $this->nombreCompleto = '';
        $this->directorio = '';
        $this->nombre = '';
        $this->tamano = 0;
        $this->extension = '';
        $this->fechaModificacion;
        $this->tipo = '';
        $this->contenido = '';
        $this->fp = false;
        $this->permiso = '';
    } 

    /**
     * Archivo::desasignar()
     * Este metodo limpia o  inicializa  las variables de la clase
     * OPCIONAL
     * @return 
     */
    function desasignar()
    {
        $this->nombreCompleto = null;
        $this->directorio = null;
        $this->nombre = null;
        $this->tamano = null;
        $this->extension = null;
        $this->fechaModificacion = null;
        $this->tipo = null;
        $this->contenido = null;
        $this->fp = false;
        $this->permiso = null;
    } 

    /**
     * Archivo::cargarInformacion()
     * Carga la información del archivo es decir inicializa las variables que almacenan 
     * los parametros del archivo, debe ser el primer metodo ha invocar despues de 
     * instanciada la clase.
     * El metodo toma del nombre del archivo los datos necesarios para inicializar
     * variables que dan información sobre el archivo como la extención, el tamaño etc.
     * METODO REQUERIDO 
     * @param  $nombreArchivo debe incluir la ruta(path) 
     * @return 
     */
    function cargarInformacion($nombreArchivo)
    {
        $this->nombreCompleto = realpath($nombreArchivo); 
        // El archivo si existe carge la informacion
        if (is_file($this->nombreCompleto))
        { 
            // Obtenga los componentes basado en el nombre del archivo
            $pathParts = pathinfo($this->nombreCompleto);
            $this->directorio 			= $pathParts["dirname"];
            $this->nombre 				= $pathParts["basename"];
            $this->extension 			= $pathParts["extension"];
            $this->tamano 				= filesize($this->nombreCompleto);
            $this->fechaModificacion 	= filemtime($this->nombreCompleto);
            
            $this->permiso = fileperms($this->nombreCompleto);
            $this->_success = "Se cargo exitosamente la informacion de  $this->nombreCompleto.\n";
            $this->_debug();
            return true;
        } 
        else
        {
            $this->_errorCode = 2;
            $this->_error = "El $this->nombreCompleto no existe o no es un archivo.\n";
            $this->_debug();
            return false;
        } 
    } 

    /**
     * Archivo::cargarContenido()
     * Carga la informacion del contenido del archivo
     * 
     * @return 
     */
    
	
	
	function cargarContenido()
    { 
        // Cuando tengamos instalado PHP 4.3.0
        $this->fp = fopen($this->nombreCompleto, 'r+');
        if ($this->fp)
        {
            $this->contenido = fread ($this->fp, $this->tamano);
            fclose($this->fp);
        } 
        else
        {
            $this->_errorCode = 10;
            $this->_error = "No se pudo abrir el archivo $this->nombreCompleto.\n";
            $this->_debug();
            return false;
        } 

        if ($this->contenido)
        {
            $this->_success = "Se cargo exitosamente el contenido de  $this->nombreCompleto.\n";
            $this->_debug();
            return true;
        } 
        else
        {
            $this->_errorCode = 4;
            $this->_error = "No se cargo el contenido de $this->nombreCompleto.\n";
            $this->_debug();
            return false;
        } 
    } 

    /**
     * Archivo::cargarContenidoEnArreglo()
     * Carga la información del archivo en un arreglo
     * Este metodo se puede utilizar para volcar el contenido de un archivo a un
     * arreglo 
     * METODO OPCIONAL.
     * @param  $arreglo Arreglo donde se guardara el archivo
     * @return 
     */
    function cargarContenidoEnArreglo($arreglo)
    {
        $arreglo = file($this->nombreCompleto);
        $this->contenido = implode("", $arreglo);

        if (sizeof($arreglo) > 0)
        {
            $this->_success = "Se cargo exitosamente el arreglo de contenido de  $this->nombreCompleto.\n";
            $this->_debug();
            return true;
        } 
        else
        {
            $this->_errorCode = 5;
            $this->_error = "No se cargo el arreglo de contenido de $this->nombreCompleto.\n";
            $this->_debug();
            return false;
        } 
    } 

    /**
     * Archivo::eliminarCaracteresEspeciales()
     * Metodo que presta la utilidad de formatear el contenido de un archivo es decir 
     * remplaza caracteres especiales  especificamente las tildes
     * METODO OPCIONAL
     * @return 
     */
    function eliminarCaracteresEspeciales()
    {
        $htmlTranslationTable = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        $translationTable = array_merge (
            array_flip($htmlTranslationTable)
            );
        $this->contenido = strtr($this->contenido, $translationTable);
    } 

    /**
     * Archivo::formatearTamano()
     * Retorna el valor del tamano del archivo con la respectiva sigla de las unidades
     * en (B)ytes (K)ilobytes (M)egabytes
     * 
     * @return string tamano y unidad de medida.
     */
    function formatearTamano()
    {
        if ($this->tamano < 1024)
        {
            return $this->tamano . ' b';
        } elseif ($this->tamano < 1024000)
        {
            return ceil($this->tamano / 1024) . ' Kb';
        } 
        else
        { 
            // tamano > un mega
            return ceil($this->tamano / 1024000) . ' Mb';
        } 
    } 

    /**
     * Archivo::crear()
     * Metodo Que presta la utilidad de crear archivos recibe como parametro el 
     * nombre del archivo ha crear y guarda lo que exista en ese momento en la 
     * variable contenido, por esto debe llenarce  esta variable antes de invocar 
     * este metodo, ademas actualiza las variables que almacenan los datos del archivo 
     * utilizando el metodo cargarInformacion  para que la clase mantenga la información 
     * del archivo actual.
     * @param  $nombreCompleto 
     * @return 
     */
    function crear($nombreCompleto)
    {
       
        if ($this->fp = fopen($nombreCompleto, 'w'))
        {
            $this->tamano = fwrite($this->fp, $this->contenido);
            fclose($this->fp);
            if (get_class($this) == 'Archivo')
            {
                $this->cargarInformacion($nombreCompleto);
            } 
            else
            {
                Archivo::cargarInformacion($nombreCompleto);
            } 
            $this->_success = "Se creo exitosamente $this->nombreCompleto.\n";
            $this->_debug();
            return true;
        } 
        else
        {
            $this->_errorCode = 13;
            $this->_error = "No se pudo crear $this->nombreCompleto.\n";
            $this->_debug();
            return false;
        } 
    } 

    /**
     * Archivo::borrar()
     * Borra un archivo fisico
     * Clase que presta la utilidad de eliminación de archivos, elimina el archivo
     * actual por este motivo debe llenarce esta varible directamente o preferiblemente
     * con intanciando la clase y utilizando el metodo cargarInformacion.
     * @return 
     */
    function borrar()
    {
        if (@unlink($this->nombreCompleto))
        {
            $this->_success = "Se borro exitosamente $this->nombreCompleto.\n";
            $this->_debug();
            return true;
        } 
        else
        {
            $this->_errorCode = 6;
            $this->_error = "No se pudo borrar $this->nombreCompleto.\n";
            $this->_debug();
            return false;
        } 
    } 

    /**
     * Archivo::copiar()
     * Funcion utilizada  para crear una copia del archivo  
     * 
     * @param  nombreDestino nombre completo donde se hara la copia, el origen se
     * toma de las variables de la clase
     * @return 
     */
    function copiar($nombreDestino, $remplazar = false)
    {
        if (file_exists($nombreDestino) && !($remplazar))
        {
            $this->_errorCode = 7;
            $this->_error = "No se pudo copiar  el contenido de $this->nombreCompleto.\n"
             . "El archivo  ya existe en el directorio";
            $this->_debug();
            return false;
        } 
        else
        {
            if (copy($this->nombreCompleto, $nombreDestino))
            {
                $this->_success = "Se copio exitosamente el contenido de  $this->nombreCompleto.\n";
                $this->_debug();
                return true;
            } 
            else
            {
                $this->_errorCode = 7;
                $this->_error = "No se pudo copiar  el contenido de $this->nombreCompleto.\n";
                $this->_debug();
                return false;
            } 
        } 
    } 

    /**
     * Archivo::subir()
     * hace un upload de un archivo es decir lleva un archivo al directorio del servidor
     * que se especifique
     * 
     * @param  $variablePost  nombre de la variable en el formulario HTML de tipo file
     * @param  $nombreDirectorio  Directorio en donde se pondra el archivo
     * @param mixed $sobreEscribir 
     * @param mixed $modificarArchivo
     * @return 
     **/
    function subir($variablePost, $nombreDirectorio, $sobreEscribir = true, $modificarArchivo = false)
    {
        
		if (
			isset($_FILES[$variablePost]['tmp_name'],$_FILES[$variablePost]['name'])
			and
			$_FILES[$variablePost]['size'] > 0
		){
            $userfile = $_FILES[$variablePost]['tmp_name'];
            $userfile_name = $_FILES[$variablePost]['name'];        
        } else {
            $this->_error = 'No se encontro el archivo en el post';
            $this->_errorCode = 6;
            $this->_debug();
            return false;        
        }

        if (!$modificarArchivo)
        {
            $this->nombreCompleto = $nombreDirectorio . basename($userfile_name);
        } 

        if (!$sobreEscribir)
        {
            if (file_exists($this->nombreCompleto))
            {
                $this->_error = 'El Archivo que desea Subir ya existe.';
                $this->_errorCode = 7;
                $this->_debug();
                return false;
            } 
        } 

        if (move_uploaded_file($userfile, $this->nombreCompleto))
        {
            $pathParts = pathinfo($this->nombreCompleto);
            $this->directorio = $pathParts["dirname"];
            $this->nombre = $pathParts["basename"];
            $this->extension = $pathParts["extension"];
            $this->tamano = filesize($this->nombreCompleto);
            $this->fechaModificacion = filemtime($this->nombreCompleto);
            $this->tipo = (in_array($this->extension, $this->extensionesTexto)) ? 'Texto' : 'Binario' ;

            $this->_success = "Se copió exitosamente el contenido de  $this->nombreCompleto.\n";
            $this->_debug();
            return true;
        } 
        else
        {
            $this->_errorCode = 8;
            $this->_error = "No se pudo copiar  el contenido de $this->nombreCompleto.\n";
            $this->_debug();
            return false;
        } 
    } 

    /**
     * Archivo::renombrar()
     * Renombra un archivo es decir presta la utilidad para cambiar el nombre a 
     * un archivo, recibe como parametros el nombre del archivo a renombrar y el
     * nuevo nombre
     * 
     * @param  $nombreDestino Nombre completo nuevo del archivo
     * @return 
     */
    function renombrar($nombreDestino)
    {
        if (rename($this->nombreCompleto, $nombreDestino))
        { 
            // Recalcula las variables de la clase ya que se renombro el archivo
            $this->cargarInformacion($nombreDestino);
            $this->_success = "Se cambi&oacute; exitosamente el nombre.\n";
            $this->_debug();
            return true;
        } 
        else
        {
            $this->_errorCode = 9;
            $this->_error = "No se pudo cambiar el nombre a $this->nombreCompleto.\n";
            $this->_debug();
            return false;
        } 
    } 

    /**
     * Archivo::escribirContenido()
     * Pone una cadena a un archivo es decir adiciona contenido al archivo,
     * toma como archivo actual el que tenga la variable de la clase.
     * 
     * @param string $contenido Contenido del archivo
     * @return 
     */
    function escribirContenido($contenido)
    { 

        $this->fp = fopen($this->nombreCompleto, 'a');
        if ($this->fp)
        {
            $this->tamano = fwrite($this->fp, $contenido);
            if ($this->tamano)
            {
                $this->_success = "Se escibio el contenido exitosamente en $this->nombreCompleto.\n";
                $this->_debug();
            } 
            fclose($this->fp);
            return true;
        } 
        else
        {
            $this->_errorCode = 11;
            $this->_error = "No se pudo abrir el archivo $this->nombreCompleto.\n";
            $this->_debug();
            return false;
        } 
    } 

    /**
     * Archivo::escribirContenido()
     * Este metodo presta la funcionalidad de limpiar el archivo es decir borar su
     * contenido.
     * 
     * @param string $contenido Contenido del archivo
     * @return 
     */
    function limpiarContenido()
    {
        $this->fp = fopen($this->nombreCompleto, 'w');
        if ($this->fp)
        {
            $tamano = fwrite($this->fp, '');
            fclose($this->fp);
            $this->_success = "Se limpio el contenido exitosamente en $this->nombreCompleto.\n";
            $this->_debug();
            return true;
        } 
        else
        {
            $this->_errorCode = 12;
            $this->_error = "No se pudo abrir el archivo $this->nombreCompleto.\n";
            $this->_debug();
            return false;
        } 
    } 
 /**
     * Archivo:: mover()
     * Este metodo presta la funcionalidad de cambiar la ubicación de un archivo
     * 
     * @param string $contenido Contenido del archivo
     * @return 
     */
    function mover($nombreDestino, $remplazar = false)
    {
        if (!($this->copiar($nombreDestino, $remplazar)))
        {
            return false;
        } 
        else
        {
            $this->borrar();
            $this->_success = "Se movio con exito $nombreDestino";
            $this->_debug();
            return true;
        } 
    } 

    /**
     * Archivo::permisos()
     * Carga los permisos de un archivo es decir obtiene los parametros que dan
     * información sobre los permisos otorgados sobre el archivo.
     * 
     * @return 
     */
    function permisos()
    { 
        // permisos de escritura
        $this->permiso = (fileperms($this->nombreCompleto));
        if ($this->permiso)
        {
            $this->_success = "obtuvo exitosamente los permisos $this->nombreCompleto.\n";
            $this->_debug();
        } 
        else
        {
            $this->_errorCode = 13;
            $this->_error = " No se pudo obtener los permisos $this->nombreCompleto.\n";
            $this->_debug();
        } 
    } 

    /**
     * Archivo::mostrarPermisos()
     * Arma una cadena segun los permisos del archivo para mostrar esta información 
     * de manera que seha lejible.
     * 
     * @return 
     */
    function mostrarPermisos()
    {
        $p_bin = substr(decbin($this->permiso), -9) ;
        $p_arr = explode(".", substr(chunk_split($p_bin, 1, "."), 0, 17)) ;

        $perms = "";
        $i = 0;

        foreach ($p_arr as $tmp)
        {
            $p_char = ($i % 3 == 0 ? "r" : ($i % 3 == 1 ? "w" : "x"));
            $perms .= ($tmp == "1" ? $p_char : "-") . ($i % 3 == 2 ? " " : "");
            $i++;
        } 

        return $perms;
    } 

     /**
     * Archivo::_debug()
     * Se utiliza este metodo para accede a la varible que almacena los errores 
     * 
     * 
     * @return 
     */
    function _debug()
    {
        if ($this->_debug)
        {
            if ($this->_errorCode) echo ("Error    :: $this->_error");
            else echo ("Success  :: $this->_success");
        } 
    } 

    function _Archivo() {
   
        unset($this->contenido);
    } 
	function validaExtencion($extencion,$extencionesPermitidas) {
   		if(strpos($extencion, $extencionesPermitidas)){
			return TRUE;
		}else{
			return FALSE;
		}
        
    } 
} 

?>