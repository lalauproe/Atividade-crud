<?php
 
$servername = "localhost:3306";
$username = "root";
$password = "root";
$dbname = "tasks";
 
$conn = new mysqli($servername, $username, $password, $dbname);
 
if ($conn->connect_error) {
    die("Conexão falhou " . $conn->connect_error);
   
}

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(250) NOT NULL UNIQUE,
    password VARCHAR(250) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === false) {
    echo "Erro ao criar tabela: " . $conn->error;
}