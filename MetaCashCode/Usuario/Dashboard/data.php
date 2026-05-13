<?php
$arquivo_db = 'banco.json';

// 1. Carrega os dados salvos
if (!file_exists($arquivo_db)) {
    $storage = ['saldo_total' => 0, 'receitas_mes' => 0, 'despesas_mes' => 0, 'transacoes' => []];
} else {
    $storage = json_decode(file_get_contents($arquivo_db), true);
}

// No data.php, verifique se está assim:
$dados = [
    'saldo_total'  => $storage['saldo_total'] ?? 0,
    'receitas_mes' => $storage['receitas_mes'] ?? 0,
    'despesas_mes' => $storage['despesas_mes'] ?? 0
];
$transacoes = $storage['transacoes'];

// 2. LÓGICA PARA O GRÁFICO DE LINHA
$labels_meses = ['Set', 'Out', 'Nov', 'Dez', 'Jan', 'Fev', 'Mar'];
$dados_receitas = [0, 0, 0, 0, 0, 0, (float)$dados['receitas_mes']]; 
$dados_despesas = [0, 0, 0, 0, 0, 0, (float)$dados['despesas_mes']];

// 3. LÓGICA PARA O GRÁFICO DE PIZZA
$categorias = [
    'Administrativo' => 0,
    'Manutenção' => 0,
    'Marketing' => 0,
    'Compras' => 0,
    'Salários' => 0,
    'Geral' => 0
];

foreach ($transacoes as $tr) {
    if ($tr['tipo'] == 's') { 
        $cat = isset($tr['cat']) ? $tr['cat'] : 'Geral';
        if (isset($categorias[$cat])) {
            $categorias[$cat] += (float)$tr['valor'];
        } else {
            $categorias['Geral'] += (float)$tr['valor'];
        }
    }
}

// Exemplo de como deve estar no seu data.php para o gráfico funcionar:
$categorias_temp = [];

// No loop do data.php
foreach ($transacoes as $tr) {
    $cat = $tr['cat'];
    $valor = (float)$tr['valor'];
    
    if (!isset($categorias_temp[$cat])) $categorias_temp[$cat] = 0;
    
    // Se for entrada soma, se for saída subtrai
    if ($tr['tipo'] == 'e') {
        $categorias_temp[$cat] += $valor;
    } else {
        $categorias_temp[$cat] -= $valor;
    }
}

$categorias_labels = array_keys($categorias_temp);
$categorias_valores = array_values($categorias_temp);

// Se não houver despesas, adiciona um valor fictício para o gráfico não sumir
if (array_sum($categorias_valores) == 0) {
    $categorias_labels = ['Sem despesas'];
    $categorias_valores = [1];
}

// 4. Cards e Formatação
$cards = [
    'Lucros' => ['valor' => number_format($dados['saldo_total'], 1, ',', '.'), 'porcentagem' => '+0%', 'cor' => 'text-teal-500'],
    'Total de Receitas' => ['valor' => count(array_filter($transacoes, fn($t) => $t['tipo'] == 'e')), 'porcentagem' => '', 'cor' => 'text-teal-500'],
    'Total de Despesas' => ['valor' => count(array_filter($transacoes, fn($t) => $t['tipo'] == 's')), 'porcentagem' => '', 'cor' => 'text-rose-500'],
    'Saldo' => ['valor' => number_format($dados['saldo_total'], 1, ',', '.'), 'porcentagem' => '', 'cor' => 'text-teal-500'],
];

function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
} // Chave que faltava aqui