<?php
/*
CREATE TABLE colaboradores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome_completo VARCHAR(150) NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  endereco VARCHAR(255) NOT NULL,
  anos_experiencia INT NOT NULL,
  salario DECIMAL(10,2) NOT NULL
);
*/

require "db.php";
header("Content-Type: application/json");

$action = $_GET["action"] ?? $_POST["action"] ?? "";

if ($action === "list") {
    $result = $conn->query("SELECT * FROM colaboradores ORDER BY id DESC");
    $dados = [];
    while ($row = $result->fetch_assoc()) $dados[] = $row;
    echo json_encode($dados);
    exit;
}

if ($action === "create" || $action === "update") {
    $nome = trim($_POST["nome_completo"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $endereco = trim($_POST["endereco"] ?? "");
    $experiencia = (int) ($_POST["anos_experiencia"] ?? 0);
    $salario = (float) str_replace(",", ".", $_POST["salario"] ?? "0");

    $erros = [];
    if ($nome === "") $erros[] = "Nome é obrigatório.";
    if (!preg_match('/^[0-9\s\-\+\(\)]{8,20}$/', $telefone)) $erros[] = "Telefone inválido.";
    if ($endereco === "") $erros[] = "Endereço é obrigatório.";
    if ($experiencia < 0) $erros[] = "Anos de experiência inválido.";
    if ($salario <= 0) $erros[] = "Salário inválido.";

    if (!empty($erros)) {
        echo json_encode(["success" => false, "erros" => $erros]);
        exit;
    }

    if ($action === "update") {
        $stmt = $conn->prepare("UPDATE colaboradores SET nome_completo=?, telefone=?, endereco=?, anos_experiencia=?, salario=? WHERE id=?");
        $stmt->bind_param("sssidi", $nome, $telefone, $endereco, $experiencia, $salario, $_POST["id"]);
    } else {
        $stmt = $conn->prepare("INSERT INTO colaboradores (nome_completo, telefone, endereco, anos_experiencia, salario) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssid", $nome, $telefone, $endereco, $experiencia, $salario);
    }
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit;
}

if ($action === "delete") {
    $stmt = $conn->prepare("DELETE FROM colaboradores WHERE id=?");
    $stmt->bind_param("i", $_POST["id"]);
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit;
}

echo json_encode(["success" => false, "erros" => ["Ação inválida."]]);
