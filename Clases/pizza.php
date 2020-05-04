<?php
class pizza{
    
    public $tipo;
    public $precio;
    public $stock;
    public $sabor;
    public $foto;
    
    function RetornarJson()
    {
        return json_encode($this);
    }
    
    function miConstructorGenerico($params)
    {
        $keys = array_keys((array)$this);
        foreach($keys as $key)
        {
            if(array_key_exists($key,(array)$params))
            {
                $this->$key=$params[$key];
            }
        }            
    }
    
    /**
     * muestro el objeto por pantalla
     */
    public function MostrarObjeto()
    {
            $keys = array_keys((array)$this);//creo un array con todos las keys del objeto
            foreach($keys as $key)
            {
                echo ucwords($key).": ".$this->$key."<br/>";//propiedad "clave : valor" ucwords lo usamos para poner mayuscula la primera Letra de cada "clave"
            }
    }   
    /**
     * Modifica el objeto de una lista, que se encuantra dentro de un archivo, lo busca por un campo en especifico y modifica sus campos si lo encuentra
     * @param string $pathObjetoTxt direccion de donde se encuentra el archivo.
     * @param string $strCampoABuscar es el campo del objeto que se va a buscar y comparar contra el objetoActual(que contiene los datos actualizados)
     * @param object $objetoActual es el objeto que le pasamos con los campos actualizados.
     * @param string $claseDelObjeto instancia del objeto que vamos a trabajar(empleado,persona,vehiculo,etc).
     */
    public static function Modificar($pathObjetoTxt,$objetoActual,$strCampoABuscar,$claseDelObjeto)
    {
        $newArrayObjetos=array();
        $keys = array_keys((array)$claseDelObjeto);//creo un array con todos las keys del objeto

        //$vehiculo=new $claseDelObjeto();
        $arrayObjetos=dao::LeerObjetosJson($pathObjetoTxt,$claseDelObjeto);
        foreach($arrayObjetos as $auxObjeto)
        {            
            if(strcasecmp($auxObjeto->$strCampoABuscar,$objetoActual->$strCampoABuscar)==0)
            {
                foreach($keys as $key)
                {
                    $auxObjeto->$key=$objetoActual->$key;
                }
                echo "<br>se modifico el ". get_class($claseDelObjeto) ." con el campo '".$strCampoABuscar."' : ".$objetoActual->$strCampoABuscar;
            }
           array_push($newArrayObjetos,$auxObjeto);    //creo un nuevo array con el proveedor modificado
        }
        unlink($pathObjetoTxt);//borro el archivo fisico

        foreach($newArrayObjetos as $auxObjeto)
        {
            //$vehiculo=new $claseDelObjeto();
            $claseDelObjeto->miConstructorGenerico((array)$auxObjeto);
            dao::GuardarObjetoJson($pathObjetoTxt,$claseDelObjeto);//creamos un nuevo archivo con todos los elementos del "newArray"
        }
    }
    /**
     * Carga una foto y la renombre con los campos del array que le pasemos como argumento
     * @param array $arrayNombre es el array que contendra los datos para el nombre de la imagen
     */
    public function cargarFoto($arrayNombre)
    {
        $pathImagen=$_FILES['foto']['tmp_name'];                                
        if(isset($_FILES['logo']))//opcional por si cargamos un logo
        {
            $pathLogo=$_FILES['logo']['tmp_name'];
        }
        else
        {
            $pathLogo="./FotosPng/utn.png";//logo por default
        }
        $nameImg=$_FILES['foto']['name'];        
        $nameImg=dao::changeImgName($nameImg,$arrayNombre);
        $pathNewImg="./Fotos/".$nameImg;
        
        if(file_exists($pathNewImg))
        {      
            $auxNewName=dao::AddDateTimeImg($nameImg);
            $pathBackUpImg="./ImagenesDePizza/".$auxNewName;
            
            if(rename($pathNewImg,$pathBackUpImg))//mueve la foto vieja a otra carpeta (backup)
            {
                dao::CrearImgConMarca($pathImagen,$pathLogo,$pathNewImg);    
               // echo "foto Actualizada";
            }
            else
            { 
                echo "No se pudo crear la foto";
            }
        }
        else
        {
            dao::CrearImgConMarca($pathImagen,$pathLogo,$pathNewImg);                    
            //echo "Subio la foto";
            
        }  
        $params["foto"]=$pathNewImg;   //agrego un elemento(foto) mas al array de parametros para la instacia de Proveedor.  
       
    }
    
}