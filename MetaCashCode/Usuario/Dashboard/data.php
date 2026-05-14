<?php
$arquivo_db = 'banco.json';

// 1. CARREGA OS DADOS
if (!file_exists($arquivo_db)) {
    $storage = ['saldo_total' => 0, 'receitas_mes' => 0, 'despesas_mes' => 0, 'transacoes' => []];
} else {
    $storage = json_decode(file_get_contents($arquivo_db), true);
}

$dados = [
    'saldo_total'  => $storage['saldo_total'] ?? 0,
    'receitas_mes' => $storage['receitas_mes'] ?? 0,
    'despesas_mes' => $storage['despesas_mes'] ?? 0
];
$transacoes = $storage['transacoes'] ?? [];

// 2. LÓGICA PARA O GRÁFICO DE LINHA (Histórico)
$labels_meses = ['Set', 'Out', 'Nov', 'Dez', 'Jan', 'Fev', 'Mar'];
$dados_receitas = [0, 0, 0, 0, 0, 0, (float)$dados['receitas_mes']]; 
$dados_despesas = [0, 0, 0, 0, 0, 0, -(float)$dados['despesas_mes']]; 

// 3. LÓGICA PARA O GRÁFICO DE PIZZA/ROSCA
$categorias_temp = [
    'Administrativo' => 0,
    'Vendas'         => 0,
    'Marketing'      => 0,
    'Salários'       => 0,
    'Geral'          => 0
];

foreach ($transacoes as $tr) {
    $cat = $tr['cat'] ?? 'Geral';
    $valor = (float)$tr['valor'];
    
    if (isset($tr['tipo'])) {
        if ($tr['tipo'] == 's') {
            // DESPESA: subtrai para ficar negativo
            $categorias_temp[$cat] = ($categorias_temp[$cat] ?? 0) - $valor;
        } else if ($tr['tipo'] == 'e') {
            // RECEITA: soma para ficar positivo
            $categorias_temp[$cat] = ($categorias_temp[$cat] ?? 0) + $valor;
        }
    }
}

// CORREÇÃO CRUCIAL: Enviamos os valores REAIS (com sinal) para o JS
$categorias_valores = array_values($categorias_temp);
$categorias_labels = array_keys($categorias_temp);

// 4. PREPARAÇÃO PARA O JAVASCRIPT
$chartData = [
    'labelsMeses' => $labels_meses,
    'receitas'    => $dados_receitas,
    'despesas'    => $dados_despesas,
    'catLabels'   => $categorias_labels,
    'catValores'  => $categorias_valores // Agora vai com os sinais corretos
];

// 5. CARDS DO DASHBOARD
$lucro_mes = (float)$dados['receitas_mes'] - (float)$dados['despesas_mes'];

$cards = [
    'Lucro Mensal' => [
        'valor' => ($lucro_mes > 0 ? '+' : '') . number_format($lucro_mes, 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => $lucro_mes >= 0 ? 'text-teal-500' : 'text-rose-500'
    ],
    'Total de Receitas' => [
        'valor' => '+' . number_format($dados['receitas_mes'], 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => 'text-teal-500'
    ],
    'Total de Despesas' => [
        'valor' => '-' . number_format($dados['despesas_mes'], 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => 'text-rose-500'
    ],
    'Saldo Total' => [
        'valor' => ($dados['saldo_total'] > 0 ? '+' : '') . number_format($dados['saldo_total'], 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => $dados['saldo_total'] >= 0 ? 'text-blue-500' : 'text-rose-500'
    ],
];