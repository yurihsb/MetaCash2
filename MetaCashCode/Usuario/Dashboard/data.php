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

// 2. LÓGICA PARA O GRÁFICO DE LINHA (Receitas vs Despesas Negativas)
$labels_meses = ['Set', 'Out', 'Nov', 'Dez', 'Jan', 'Fev', 'Mar'];

$dados_receitas = [0, 0, 0, 0, 0, 0, (float)$dados['receitas_mes']]; 
// Alterado para negativo aqui
$dados_despesas = [0, 0, 0, 0, 0, 0, -(float)$dados['despesas_mes']]; 

// 3. LÓGICA PARA O GRÁFICO DE PIZZA (Gastos por Categoria com valores negativos)
$categorias_temp = [];
foreach ($transacoes as $tr) {
    if ($tr['tipo'] == 's') {
        $cat = $tr['cat'] ?? 'Geral';
        $valor = (float)$tr['valor'];
        
        if (!isset($categorias_temp[$cat])) {
            $categorias_temp[$cat] = 0;
        }
        // Armazenamos como negativo
        $categorias_temp[$cat] -= $valor;
    }
}

if (empty($categorias_temp)) {
    $categorias_labels = ['Sem despesas'];
    $categorias_valores = [0];
} else {
    $categorias_labels = array_keys($categorias_temp);
    $categorias_valores = array_values($categorias_temp);
}

// 4. PREPARAÇÃO DO OBJETO PARA O JAVASCRIPT
$chartData = [
    'labelsMeses' => $labels_meses,
    'receitas'    => $dados_receitas,
    'despesas'    => $dados_despesas,
    'catLabels'   => $categorias_labels,
    'catValores'  => $categorias_valores
];

// 5. CARDS E FORMATAÇÃO (Exibindo sinal de menos nos cards)
$cards = [
    'Lucro Mensal' => [
        'valor' => number_format($dados['receitas_mes'] - $dados['despesas_mes'], 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => 'text-teal-500'
    ],
    'Total de Receitas' => [
        'valor' => count(array_filter($transacoes, fn($t) => $t['tipo'] == 'e')), 
        'porcentagem' => '', 
        'cor' => 'text-teal-500'
    ],
    'Total de Despesas' => [
        // Adicionado sinal de menos visualmente aqui
        'valor' => '-' . number_format($dados['despesas_mes'], 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => 'text-rose-500'
    ],
    'Saldo Total' => [
        'valor' => number_format($dados['saldo_total'], 2, ',', '.'), 
        'porcentagem' => '', 
        'cor' => 'text-blue-500'
    ],
];

function formatarMoeda($valor) {
    // Se o valor for menor que zero, o number_format já coloca o sinal de menos
    return 'R$ ' . number_format($valor, 2, ',', '.');
}
?>

<script>
    // Importante: O JS consolidado agora receberá valores negativos em 'despesas' e 'catValores'
    const chartData = <?php echo json_encode($chartData); ?>;
</script>