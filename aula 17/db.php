<?php
$host = "localhost";
$user = "root";
$pass = "Art152535@";
$dbname = "empresa";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
