<?php
// Configurações do PostgreSQL
$host     = 'localhost';
$port     = '5432'; // Porta padrão do Postgres
$db_name  = 'metacash_db';
$usuario  = 'postgres'; // Usuário padrão do Postgres
$senha    = '0369'; // A senha que você definiu na instalação do Postgres

try {
    // A string de conexão muda para pgsql:
    $dsn = "pgsql:host=$host;port=$port;dbname=$db_name";
    
    $pdo = new PDO($dsn, $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

} catch (PDOException $e) {
    die("Erro na conexão com PostgreSQL: " . $e->getMessage());
}
?>