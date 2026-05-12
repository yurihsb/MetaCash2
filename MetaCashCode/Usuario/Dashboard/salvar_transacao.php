<?php
$arquivo_db = 'banco.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Carrega o banco ou inicia do zero
    $storage = file_exists($arquivo_db) ? json_decode(file_get_contents($arquivo_db), true) : [
        'saldo_total' => 0, 
        'receitas_mes' => 0, 
        'despesas_mes' => 0, 
        'transacoes' => []
    ];

    // 2. Pega os dados do formulário
    $titulo = $_POST['titulo'] ?? 'Sem título';
    $valor  = (float)($_POST['valor'] ?? 0);
    $tipo   = $_POST['tipo'] ?? 'e';
    $cat    = $_POST['cat'] ?? 'Geral';
    $data   = date('d/m/Y');

    // 3. ATUALIZA OS TOTAIS (A matemática que não estava refletindo)
    if ($tipo === 'e') {
        $storage['saldo_total'] += $valor;
        $storage['receitas_mes'] += $valor;
    } else {
        $storage['saldo_total'] -= $valor;
        $storage['despesas_mes'] += $valor;
    }

    // 4. Cria o registro da transação
    $nova_tr = [
        'titulo' => $titulo,
        'valor'  => $valor,
        'tipo'   => $tipo,
        'cat'    => $cat,
        'data'   => $data
    ];

    // 5. Adiciona ao topo da lista
    array_unshift($storage['transacoes'], $nova_tr);

    // 6. Salva o arquivo com formatação para evitar erros de leitura
    file_put_contents($arquivo_db, json_encode($storage, JSON_PRETTY_PRINT));

    // 7. Volta para a página inicial
    header("Location: index.php");
    exit();
}