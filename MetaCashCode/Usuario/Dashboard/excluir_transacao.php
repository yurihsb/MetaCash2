<?php
$arquivo_db = 'banco.json';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $storage = json_decode(file_get_contents($arquivo_db), true);

    if (isset($storage['transacoes'][$id])) {
        $tr = $storage['transacoes'][$id];

        // Ajusta o saldo e os totais antes de remover
        if ($tr['tipo'] == 'e') {
            $storage['saldo_total'] -= $tr['valor'];
            $storage['receitas_mes'] -= $tr['valor'];
        } else {
            $storage['saldo_total'] += $tr['valor'];
            $storage['despesas_mes'] -= $tr['valor'];
        }

        // Remove a transação do array
        unset($storage['transacoes'][$id]);
        
        // Reorganiza os índices do array para não quebrar o PHP
        $storage['transacoes'] = array_values($storage['transacoes']);

        file_put_contents($arquivo_db, json_encode($storage, JSON_PRETTY_PRINT));
    }
}

header("Location: index.php");
exit();