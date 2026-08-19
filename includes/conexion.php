<?php

$servidor_principal = "127.0.0.1"; 
$usuario_principal = "root"; 
$contrasena_principal = ""; 
$base_datos = "promotian"; 


$servidor_respaldo = "192.168.1.119"; 
$usuario_respaldo = "root"; 
$contrasena_respaldo = ""; 

try {
    
    $opciones = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 2 
    ];

     
    $conexion = new PDO("mysql:host=$servidor_principal;dbname=$base_datos;charset=utf8mb4", $usuario_principal, $contrasena_principal, $opciones);
    
} catch(PDOException $e) {
    
    try {
        
        $conexion = new PDO("mysql:host=$servidor_respaldo;dbname=$base_datos;charset=utf8mb4", $usuario_respaldo, $contrasena_respaldo, $opciones);
        
    } catch(PDOException $e2) {
        
        die("Error crítico en el marketplace: Ningún servidor de base de datos está disponible.");
    }
}
?>