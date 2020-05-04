<?php

ini_set('max_input_vars', 3000);
$dato = $_SERVER['REQUEST_METHOD'];
if($dato=="POST")
{
    if($_POST["caso"]=="Usuario")
    {
        require_once "./Funciones/RegistrarUsuario.php";
    }
    else if($_POST["caso"]=="login")
    {
        require_once "./Funciones/Login.php";
    }
    else if($_POST["caso"]=="pizzas")
    {
        require_once "./Funciones/Login.php";
    }
    else if($_POST["caso"]=="Ventas")
    {
        require_once "./Funciones/Venta.php";
    }
    
}
else if($dato=="GET")
{
    if($_GET["caso"]=="pizzas")
    {
        require_once "./Funciones/PizzaConsultar.php";
    }
    else if($_GET["caso"]=="Ventas")
    {
        require_once "./Funciones/ListadoDePizza.php";
    }
}
else if($dato=="DELETE")
{
    if(["caso"]=="BorrarItem")
    {
        require_once "./Funciones/BorrarItem.php";
    }
   
}
?>