<?php

class dao{
        public static function LeerObjetosJson($path,$clase)
        {
            $file=fopen($path,"r");
            $arrayListaObjetos=array();
            while(!feof($file)){            
                $objetoLeido=json_decode(fgets($file),true);             
                if(feof($file))
                {
                    break;
                }
                $objeto = new $clase();  
                $objeto->miConstructorGenerico($objetoLeido);
                array_push($arrayListaObjetos,$objeto);
            }        
            fclose($file);
            return $arrayListaObjetos;
        }
  
        public static function verificarExistenciaDelObjetoDobleCriterio($arrayObjetos,$datoABuscarUno,$datoABuscarDos,$campoDelObjetoUno,$campoDelObjetoDos)
        {
            $existeObjeto=false;
            foreach($arrayObjetos as $auxObjeto)
            {         
                if(strcasecmp($auxObjeto->$campoDelObjetoUno,$datoABuscarUno)==0 && strcasecmp($auxObjeto->$campoDelObjetoDos,$datoABuscarDos)==0)      
                {
                    $existeObjeto=true;
                    break;
                }
            }
                return $existeObjeto;
        }


        public static function verificarExistenciaDelObjeto($arrayObjetos,$datoABuscar,$campoDelObjeto)
        {
            $existeObjeto=false;
            foreach($arrayObjetos as $auxObjeto)
            {   
                if(strcasecmp($auxObjeto->$campoDelObjeto,$datoABuscar)==0)//id -> dato
                {
                    $existeObjeto=true;
                    break;
                }
            }
                return $existeObjeto;
        }

        public static function verificarExistenciaDelObjetoDobleDato($arrayObjetos,$datoABuscar,$datoABuscar2,$campoDelObjeto,$campoDelObjeto2)
        {
            $existeObjeto=false;
            foreach($arrayObjetos as $auxObjeto)
            {   
                if(strcasecmp($auxObjeto->$campoDelObjeto,$datoABuscar)==0 && strcasecmp($auxObjeto->$campoDelObjeto2,$datoABuscar2)==0)//id -> dato
                {
                    $existeObjeto=true;                           
                    break;
                }
            }
                return $existeObjeto;
        }
        public static function obtenerPizza($arrayObjetos,$datoABuscar,$datoABuscar2,$campoDelObjeto,$campoDelObjeto2)
        {
            $objeto=null;
            foreach($arrayObjetos as $auxObjeto)
            {   
                if(strcasecmp($auxObjeto->$campoDelObjeto,$datoABuscar)==0 && strcasecmp($auxObjeto->$campoDelObjeto2,$datoABuscar2)==0)//id -> dato
                {
                    $objeto=$auxObjeto;                           
                    break;
                }
            }
                return $objeto;
        }

        public static function GuardarObjetoJson($path,$objetoAGuardar)
        {
            $retorno=false;
            if(file_exists($path))
            {            
              $file = fopen($path,"a");  
              fwrite($file,$objetoAGuardar->RetornarJson()."\r\n");
              $retorno=true;      
                
            }
            else
            {
                $file = fopen($path,"w");
                fwrite($file,$objetoAGuardar->RetornarJson()."\r\n");
                $retorno=true;
            }   
            fclose($file);  
            return $retorno;       
        }
        /**
         * Trae una lista de objetos filtrados por un dato en especifico comparando contra todos los campos del objeto(leidos de un archivo).   
         * @param string $path es la direccion donde esta el archivo a ser leido.
         * @param string $dato es el dato que se tiene que buscar.
         * @param object $clase del objeto es la clase del tipo de objeto que estamos trabajando Ejemplo: Alumno, Proveedor, Vehiculos etc.
         * @return array devuelve una lista de objetos 
         */
        public static function LeerObjetosPorDatosJson($path,$dato,$dato2,$claseDelObjeto)
        {
            $arrayObjetos=array();        //lista de objetos filtrada
            $contadorDeCoincidencias=0;// contador de coincidencias encontradas.
            $keys = array_keys((array)$claseDelObjeto);//creo un array con todos las keys del objeto
            $auxObjeto = new $claseDelObjeto();    //insatancio un abjeto de esa clase
            if(isset($dato))
            {
                $arrayListaObjetos=dao::LeerObjetosJson($path,$auxObjeto);//leo todos los objetos antes de filtrar
                foreach($arrayListaObjetos as $objetoDeLista)
                {
                    $auxObjeto->miConstructorGenerico((array)$objetoDeLista);//
                    foreach($keys as $key)//recorro todos los campos del objeto para buscar coincidencias
                    {
                        if(strcasecmp ($auxObjeto->$key,$dato)==0)//si hay coincidencia.
                        {
                            foreach($keys as $key)
                            {
                                if(strcasecmp ($auxObjeto->$key,$dato2)==0)//si hay coincidencia.
                                {
                                    array_push($arrayObjetos,$objetoDeLista);//agrego a la lista filtrada el objeto
                                    $contadorDeCoincidencias++;
                                }
                            }
                        }
                    }
                }
                    
            }                
            if($contadorDeCoincidencias==0)
            {
                if(isset($dato))
                {
                    echo "No hay pizza de: ".$dato." y ".$dato2;
                }          
            }
            return $arrayObjetos;
            
        }
        public static function changeImgName($nameImg,$arrayNewName)
        {
            $arrayNameImg=explode('.',$nameImg);//separo el nombre de la imagen por el '.' y creo un array
            $arrayNameImg[0]="";//borramos el nombre de la imagen
            foreach($arrayNewName as $auxName)
            {
                $arrayNameImg[0].=$auxName;//en la parte izquierda del punto ponemos el nombre
            }
            $nameImg=$arrayNameImg[0].".".$arrayNameImg[1];            
            return $nameImg;
        }
       
        public static function AddDateTimeImg($nameImg)
        {
            $arrayNameImg=explode('.',$nameImg);//creo un array y separo
            $auxNewName=$arrayNameImg[0].date("-Y-m-d").date("-h-i-sa").".".$arrayNameImg[1];//creo el nuevo nombre de la imagen agregando fecha y hora     
            return $auxNewName;
        }
    
        public static function CrearImgConMarca($path,$pathLogo,$pathNewImg)
        {
            $marca = imagecreatefrompng($pathLogo);//creamos el sello
            $img = imagecreatefromjpeg($path);//creamos la imagen
    
            $right =10;
            $bottom = 10;
            $jx = imagesx($marca);
            $jy = imagesY($marca);
    
            imagecopy($img, $marca, imagesx($img) - $jx - $right, imagesy($img) - $jy - $bottom, 0, 0, imagesx($marca), imagesy($marca));
    
    
            move_uploaded_file($path,$pathNewImg);
            imagepng($img, $pathNewImg);//guarda la imagen que creamos con el sello de agua en el pathNewImg
        }


        //ARMAR TABLA
        public static function insertarHeaderDeTabla($objeto){
            
            $keys = array_keys((array)$objeto);//creo un array con todos las keys del objeto
            $retornoHeader="<table border='2px'>
            <tr>";
            foreach($keys as $key)
            {
                $retornoHeader.="<th>".$key."</th>";
            }
            $retornoHeader.="</tr>";
            return $retornoHeader;
        }        
        public static function insertarFilaObjeto($objeto)
        {
            $keys = array_keys((array)$objeto);//creo un array con todos las keys del objeto
            $retornoFila="<tr>";
            foreach($keys as $key)
            {
                if(strcasecmp($key,'imagen')==0 || strcasecmp($key,'foto')==0)//si es una imagen cambia la etiqueta para poder mostrarla
                {
                   $retornoFila.="<td><img src='".$objeto->$key."' alt='".get_class($objeto)."' height=50 width=50 border=2>"."</td>";
                }
                else
                {
                    $retornoFila.="<td>".$objeto->$key."</td>";
                }
            }                
            $retornoFila.="</tr>";
            return $retornoFila;
        }   

        public static function insertarPieDeTabla(){
            $retornoPie="</table>";
            return $retornoPie;
        }    

        public static function LeerObjetosPorUnDatosJson($path,$dato,$claseDelObjeto)
        {
            $arrayObjetos=array();        //lista de objetos filtrada
            $contadorDeCoincidencias=0;// contador de coincidencias encontradas.
            $keys = array_keys((array)$claseDelObjeto);//creo un array con todos las keys del objeto
            $auxObjeto = new $claseDelObjeto();    //insatancio un abjeto de esa clase
            if(isset($dato))
            {
                $arrayListaObjetos=dao::LeerObjetosJson($path,$auxObjeto);//leo todos los objetos antes de filtrar
                foreach($arrayListaObjetos as $objetoDeLista)
                {
                    $auxObjeto->miConstructorGenerico((array)$objetoDeLista);//
                    foreach($keys as $key)//recorro todos los campos del objeto para buscar coincidencias
                    {
                        if(strcasecmp ($auxObjeto->$key,$dato)==0)//si hay coincidencia.
                        {
                            array_push($arrayObjetos,$objetoDeLista);//agrego a la lista filtrada el objeto
                            $contadorDeCoincidencias++;
                        }
                    }
                }
                    
            }                
            if($contadorDeCoincidencias==0)
            {
                if(isset($dato))
                {
                    echo "No hay coincidencias con: ".$dato;
                }          
            }
            return $arrayObjetos;
            
        }
}