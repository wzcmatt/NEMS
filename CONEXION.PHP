<?php
$conexion = null;
$servidor = "localhost";
$bd = "nems";
$user = "root";
$pass = "";
try{
     $conexion = new PDO('mysql:host='.$servidor.';dbname=' .$bd, $user, $pass);
}catch (PDOException $e){
    echo "error de conexion!";
    exit;
}
return $conexion;
?>
