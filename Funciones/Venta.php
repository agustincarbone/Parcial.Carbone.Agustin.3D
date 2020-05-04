<?php
 include "./Clases/pizza.php";   
 include "./Clases/dao.php";   

     if(isset($_POST['sabor']))
     {
         if(isset($_POST['email']))
         {
            if(isset($_POST['tipo']))
            {
                if(isset($_POST['cantidad']))
                {               
                    $sabor=$_POST['sabor'];
                    $email=$_POST['email'];
                    $tipo=$_POST['tipo'];
                    $cantidad=$_POST['cantidad'];  
                    $pizza = new pizza();                   
                                        
                    $path="./Archivos/pizza.txt";
                    
                    $arrayPizzas=array();
                    if(file_exists($path))
                    {
                        $arrayPizzas=dao::LeerObjetosJson($path,$pizza);
                        
                        $bandera=0;
                        $pizzaVendida=dao::obtenerPizza($arrayPizzas,$tipo,$sabor,'tipo','sabor');
                        if($pizzaVendida!= null)
                        {
                            if($pizzaVendida->stock<$cantidad)
                            {
                                echo "no hay tanto stock disponible";
                            }
                            else
                            {
                                $pizzaVendida->stock -=1;                            
                            }
                                                        
                        }     
                        else
                        {
                            echo "no tenemos disponible ese tipo y sabor";
                        }         
                    
                    }
                    else
                    {                            
                        echo "no hay pizzas en el archivo";
                    }
                }
                else
                {
                    echo "falta el campo cantidad en POST";
                }
            }
            else
            {
                echo "falta el campo tipo en POST";
            }
         }
         else
         {
             echo "falta el campo email en POST";
         }
     }
     else
     {
         echo "falta el campo sabor en POST";
     }

?>