<?php
 include "./Clases/usuario.php";   
     
 if(isset($_POST['email']))
 {
     if(isset($_POST['clave']))
     {
         if(isset($_POST['tipo']))
         {
                 $email=$_POST['email'];
                 $clave=$_POST['clave'];
                 $tipo=$_POST['tipo'];
                 $params=array("email"=>$email,"clave"=>$clave,"tipo"=>$tipo);

                 $usuario = new usuario();   
                 $usuario->miConstructor($params);
                 $path="./Archivos/users.txt";

                 $arrayUsuario=array();
                 if(file_exists($path)){

                     $arrayProveedor=usuario::LeerUsuariosJson($path);
                    }
                if($proveedor->GuardarUsuarioJson($path))
                {
                    echo "Guardamos el usuario";
                }
         }
         else
         {
             echo "falta el campo tipo en POST";
         }
     }
     else
     {
         echo "falta el campo clave en POST";
     }
 }
 else
 {
     echo "falta el campo email en POST";
 }

?>