<?php
require_once "funcoes.php";

function conectarAoBanco() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "henrimack";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

$conn = conectarAoBanco();
$sql = "SELECT * FROM orcamentohenr";
$result = $conn->query($sql);
?>


