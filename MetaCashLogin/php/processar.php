<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    // Consulta usando Prepared Statements para evitar SQL Injection
    $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    // Verificação (Se estiver usando password_hash no cadastro, use password_verify)
    if ($usuario && $senha === $usuario['senha']) {
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: index.php?erro=1");
        exit;
    }
}