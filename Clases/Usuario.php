<?php
class Usuario{

    public $email;
    public $clave;
    public $tipo;
    
    function RetornarJson()
    {
        return json_encode($this);
    }

    function GuardarUsuarioJson($path)
    {
        $retorno=false;
        if(file_exists($path))
        {            
          $file = fopen($path,"a");
          $listaUsuarios=usuario::LeerUsuariosJson($path);
         
            if(!Usuario::verificarExistenciaDelUsuario($listaUsuarios,$this->id))
            {
                fwrite($file,$this->RetornarJson()."\r\n");
                $retorno=true;
            }
            else
            {                
                echo "el usuario con el id: ".$_POST['id']." ya existe";
            }            
        }
        else
        {
            $file = fopen($path,"w");
            fwrite($file,$this->RetornarJson()."\r\n");
            $retorno=true;
        }   
        fclose($file);  
        return $retorno;       
    }
    
    function miConstructor($params)
    {
        if(array_key_exists("email",(array)$params))
        {
            $this->nombre=$params['email'];
        }
        
        if(array_key_exists("clave",(array)$params))
        {
            $this->email=$params['clave'];
        }
        if(array_key_exists("tipo",(array)$params))
        {
            $this->tipo=$params['tipo'];
        }
    }

    public function MostrarUsuario()
    {
        echo "Email: ".$this->email."<br/>";
        echo "Clave: ".$this->clave."<br/>";
        echo "Tipo: ".$this->tipo."<br/><br/>";
    }

    public static function LeerUsuariosPorEmailJson($path)
    {
        $arrayUsuarios=array();        
        $contadorDeCoincidencias=0;
        foreach(Usuario::LeerUsuariosJson($path) as $auxUser)
        {
            $auxUsuario = new Usuario();    
            $auxUsuario->miConstructor((array)$auxUser);
            if(strcasecmp ($auxUsuario->email,$_GET["email"])==0)
            {
                array_push($arrayUsuarioss,$auxUsuario);
                $contadorDeCoincidencias++;
            }
        } 

        if($contadorDeCoincidencias==0)
        {
            echo "No existe Usuario: ".$_GET["email"];
        }
        return $arrayUsuarios;
        
    }

    public static function LeerUsuariosJson($path)
    {
        $file=fopen($path,"r");
        $arrayUsuarios=array();
        while(!feof($file)){            
            $UsuarioLeido=json_decode(fgets($file),true);             
            if(feof($file))
            {
                break;
            }
            $Usuario = new Usuario();  
            $Usuario->miConstructor($UsuarioLeido);
            array_push($arrayUsuarios,$Usuario);
        }        
        fclose($file);
        return $arrayUsuarios;
    }

    public static function verificarExistenciaDelUsuario($arrayUsuarios,$id)
    {
        $existeIdUsuario=false;
        foreach($arrayUsuarios as $auxUsuario)
        {   
            if($auxUsuario->id==$id)
            {
                $existeIdUsuario=true;
                break;
            }
        }
            return $existeIdUsuario;
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

    public static function ModificarUsuario($id,$usuarioActual)
    {
        $newArrayUsuarios=array();
        $pathUsuarios="./Archivos/usuarios.txt";
        $arrayUsuarios=Usuario::LeerUsuariosJson($pathUsuarios);
        foreach($arrayUsuarios as $auxUsuario)
        {            
            if($auxUsuario->id==$usuarioActual->id)
            {
                $auxUsuario->nombre=$usuarioActual->nombre;
                $auxUsuario->email=$usuarioActual->email;
                $auxUsuario->foto=$usuarioActual->foto;
                echo "<br>se modifico el usuario con el id: ".$usuarioActual->id;
            }
           array_push($newArrayUsuarios,$auxUsuario);    //creo un nuevo array con el Usuario modificado
        }
        unlink($pathUsuarios);//borro el archivo fisico

        foreach($newArrayUsuarios as $auxUsuario)
        {
            $usuario=new Usuario();
            $usuario->miConstructor((array)$auxUsuario);
            $usuario->GuardarUsuarioJson($pathUsuarios);//creamos un nuevo archivo con todos los elementos del "newArray"
        }
    }
    
    public static function obtenerDatoPorId($id,$dato){
        $path="./Archivos/usuarios.txt";
        $retorno=-1;
        foreach(Usuario::LeerUsuariosJson($path) as $usuario)
        {
            if($usuario->id==$id)
            {
                $retorno=$usuario->$dato;
            }
        }
        return $retorno;
    }
}
?>