<?php
/*
CREATE TABLE carros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  marca VARCHAR(60) NOT NULL,
  modelo VARCHAR(60) NOT NULL,
  ano INT NOT NULL,
  cor VARCHAR(30) NOT NULL,
  placa VARCHAR(10) NOT NULL,
  quilometragem INT NOT NULL,
  valor_venda DECIMAL(10,2) NOT NULL
);
*/

require "db.php";
header("Content-Type: application/json");

$action = $_GET["action"] ?? $_POST["action"] ?? "";

if ($action === "list") {
    $result = $conn->query("SELECT * FROM carros ORDER BY id DESC");
    $dados = [];
    while ($row = $result->fetch_assoc()) $dados[] = $row;
    echo json_encode($dados);
    exit;
}

if ($action === "create" || $action === "update") {
    $marca = trim($_POST["marca"] ?? "");
    $modelo = trim($_POST["modelo"] ?? "");
    $ano = (int) ($_POST["ano"] ?? 0);
    $cor = trim($_POST["cor"] ?? "");
    $placa = strtoupper(trim($_POST["placa"] ?? ""));
    $km = (int) ($_POST["quilometragem"] ?? 0);
    $valor = (float) str_replace(",", ".", $_POST["valor_venda"] ?? "0");

    $erros = [];
    if ($marca === "") $erros[] = "Marca é obrigatória.";
    if ($modelo === "") $erros[] = "Modelo é obrigatório.";
    if ($ano < 1950 || $ano > (int) date("Y") + 1) $erros[] = "Ano inválido.";
    if ($cor === "") $erros[] = "Cor é obrigatória.";
    if (!preg_match('/^[A-Z0-9\-]{6,8}$/', $placa)) $erros[] = "Placa inválida.";
    if ($km < 0) $erros[] = "Quilometragem inválida.";
    if ($valor <= 0) $erros[] = "Valor inválido.";

    if (!empty($erros)) {
        echo json_encode(["success" => false, "erros" => $erros]);
        exit;
    }

    if ($action === "update") {
        $stmt = $conn->prepare("UPDATE carros SET marca=?, modelo=?, ano=?, cor=?, placa=?, quilometragem=?, valor_venda=? WHERE id=?");
        $stmt->bind_param("ssisiidi", $marca, $modelo, $ano, $cor, $placa, $km, $valor, $_POST["id"]);
    } else {
        $stmt = $conn->prepare("INSERT INTO carros (marca, modelo, ano, cor, placa, quilometragem, valor_venda) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisiid", $marca, $modelo, $ano, $cor, $placa, $km, $valor);
    }
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit;
}

if ($action === "delete") {
    $stmt = $conn->prepare("DELETE FROM carros WHERE id=?");
    $stmt->bind_param("i", $_POST["id"]);
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit;
}

echo json_encode(["success" => false, "erros" => ["Ação inválida."]]);
