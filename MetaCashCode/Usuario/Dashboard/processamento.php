<?php
include('data.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $valor  = $_POST['valor'] ?? 0;
    $tipo   = $_POST['tipo'] ?? 'e';

    // 1. Aqui você executa o seu SQL INSERT ou salva no array
    // Exemplo: $db->query("INSERT INTO transacoes...");

    // 2. Redireciona de volta para o dashboard
    header("Location: dashboard.php?status=sucesso");
    exit();
}