<?php
/**
 * MetaCash - Lógica de Processamento de Dados
 * Este arquivo lê o banco JSON e prepara as variáveis para a View.
 */

// 1. Configurações de erro para desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Definição do caminho do banco de dados JSON
// O caminho ../Dashboard/banco.json assume que a pasta Dashboard 
// está no mesmo nível da pasta Transacoes.php
$caminho_json = '../Dashboard/banco.json';

// 3. Carregamento e decodificação dos dados
if (file_exists($caminho_json)) {
    $conteudo = file_get_contents($caminho_json);
    $storage = json_decode($conteudo, true);
    
    // Caso o JSON esteja corrompido ou vazio, inicializa a estrutura
    if (json_last_error() !== JSON_ERROR_NONE || !$storage) {
        $storage = [
            'saldo_total' => 0, 
            'receitas_mes' => 0, 
            'despesas_mes' => 0, 
            'transacoes' => []
        ];
    }
} else {
    // Caso o arquivo não exista fisicamente ainda
    $storage = [
        'saldo_total' => 0, 
        'receitas_mes' => 0, 
        'despesas_mes' => 0, 
        'transacoes' => []
    ];
}

// 4. Preparação das variáveis globais para o index.php
$transacoes = $storage['transacoes'] ?? [];
$dados_financeiros = [
    'receitas_mes' => $storage['receitas_mes'] ?? 0,
    'despesas_mes' => $storage['despesas_mes'] ?? 0,
    'saldo_total' => $storage['saldo_total'] ?? 0
];

/**
 * Função Auxiliar: Formata valores numéricos para o padrão de moeda Real (R$)
 * @param float $valor
 * @return string
 */
if (!function_exists('formatarMoeda')) {
    function formatarMoeda($valor) {
        return 'R$ ' . number_format(abs($valor), 2, ',', '.');
    }
}
?>