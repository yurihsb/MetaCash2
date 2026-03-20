<?php
$host = "localhost";
$dbname = "metacash";
$user = "postgres";
$pass = "suasenha";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
        
        $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO empresas (nome_empresa, nome_responsavel, email, telefone, cnpj, cpf, senha) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['nome_empresa'], $_POST['nome_responsavel'], $_POST['email'], 
            $_POST['telefone'], $_POST['cnpj'], $_POST['cpf'], $senha_hash
        ]);
        
        // Redireciona com sucesso
        header("Location: index.php?sucesso=1");
    } catch (PDOException $e) {
        // Redireciona com erro
        header("Location: index.php?erro=" . urlencode($e->getMessage()));
    }
}