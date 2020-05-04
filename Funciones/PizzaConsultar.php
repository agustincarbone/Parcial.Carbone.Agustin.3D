<?php
include_once "./Clases/pizza.php";
include_once "./Clases/dao.php";

$path="./Archivos/pizza.txt";

$tipoDeDato=new pizza();
if(isset($_GET["tipo"]))
{
    if(isset($_GET["sabor"]))
    {
        $dato=$_GET["tipo"];
        $dato2=$_GET["sabor"];
        $arrayObjetos=dao::LeerObjetosPorDatosJson($path,$dato,$dato2,$tipoDeDato);
        foreach($arrayObjetos as $auxObjeto)
        {
            echo "<br>";
            if(strcasecmp ($auxObjeto->tipo,$dato)==0 && strcasecmp ($auxObjeto->sabor,$dato2)==0)
            {
                echo "Si Hay";
            }
        break;   
        }
    }
    else
    {
        echo "falta el campo sabor en GET";
    }
}
else
{
    echo "falta el campo tipo en GET";
}
?>