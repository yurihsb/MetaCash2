<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!file_exists('data.php')) {
    die("ERRO: O arquivo data.php não foi encontrado na pasta: " . __DIR__);
}

include('data.php');

$cards = isset($cards) ? $cards : [];
$transacoes = isset($transacoes) ? $transacoes : [];

// Preparando dados para o JS
$labels_meses = isset($labels_meses) ? $labels_meses : ['Set', 'Out', 'Nov', 'Dez', 'Jan', 'Fev', 'Mar'];
$dados_receitas = isset($dados_receitas) ? $dados_receitas : [0,0,0,0,0,0,0];
$dados_despesas = isset($dados_despesas) ? $dados_despesas : [0,0,0,0,0,0,0];
$categorias_labels = isset($categorias_labels) ? $categorias_labels : [];
$categorias_valores = isset($categorias_valores) ? $categorias_valores : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MetaCash - Gestão</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="flex bg-gray-50">

<aside class="w-64 bg-[#0d1b2a] min-h-screen p-6 text-white flex flex-col border-r border-white/5">
    <div class="mb-10 flex items-center gap-3">
        <div class="text-white font-bold text-lg">MetaCash</div>
    </div>
    <nav class="space-y-2 flex-1">
        <a href="index.php" class="flex items-center gap-3 bg-[#1b434d] text-[#2ec4b6] p-3 rounded-xl font-medium">Dashboard</a>
    </nav>
</aside>

<main class="flex-1 p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Visão Geral Financeira</h1>
        <button onclick="document.getElementById('modalTransacao').classList.replace('hidden', 'flex')" class="bg-teal-500 text-white px-4 py-2 rounded-lg font-medium shadow-lg hover:bg-teal-600 transition">
            + Adicionar Transação
        </button>
    </div>

    <!-- SEÇÃO DE SALDO -->
    <section class="bg-[#0f1c30] rounded-2xl p-8 text-white mb-8 shadow-xl">
        <span class="text-xs text-slate-400 uppercase tracking-wider">Saldo Total</span>
        <div class="text-4xl font-bold mb-8">
            R$ <?php echo isset($dados['saldo_total']) ? number_format($dados['saldo_total'], 2, ',', '.') : '0,00'; ?>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white/5 border border-white/10 p-4 rounded-xl">
                <div class="text-xs text-slate-400">↗ Receitas</div>
                <div class="text-xl font-semibold">R$ <?php echo isset($dados['receitas_mes']) ? number_format($dados['receitas_mes'], 2, ',', '.') : '0,00'; ?></div>
            </div>
            <div class="bg-white/5 border border-white/10 p-4 rounded-xl">
                <div class="text-xs text-slate-400">↘ Despesas</div>
                <div class="text-xl font-semibold">R$ <?php echo isset($dados['despesas_mes']) ? number_format($dados['despesas_mes'], 2, ',', '.') : '0,00'; ?></div>
            </div>
        </div>
    </section>

    <!-- ÁREA DOS GRÁFICOS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-4"><span class="text-teal-500">Receitas</span> vs <span class="text-rose-500">Despesas</span></h3>
            <canvas id="chartLinha" height="200"></canvas>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-4">Despesas por Categoria</h3>
            <canvas id="chartPizza" height="200"></canvas>
        </div>
    </div>

    <!-- CARDS PEQUENOS -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <?php foreach($cards as $titulo => $info): ?>
            <div class="bg-white p-4 rounded-xl border border-slate-200">
                <div class="flex justify-between text-xs font-bold mb-2">
                    <span class="text-slate-500 uppercase"><?php echo $titulo; ?></span>
                    <span class="<?php echo $info['cor']; ?>"><?php echo $info['porcentagem']; ?></span>
                </div>
                <div class="text-2xl font-bold text-slate-800">R$ <?php echo $info['valor']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- TRANSAÇÕES RECENTES -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="font-bold text-slate-800 mb-4">Transações Recentes</h3>
        <div class="space-y-4">
            <?php foreach($transacoes as $tr): ?>
                <div class="flex justify-between items-center py-2 border-b border-slate-50 last:border-0">
                    <div>
                        <p class="font-semibold text-slate-700"><?php echo $tr['titulo']; ?></p>
                        <p class="text-xs text-slate-400"><?php echo isset($tr['cat']) ? $tr['cat'] : 'Geral'; ?> • <?php echo $tr['data']; ?></p>
                    </div>
                    <p class="font-bold <?php echo $tr['tipo'] == 'e' ? 'text-teal-500' : 'text-rose-500'; ?>">
                        <?php echo ($tr['tipo'] == 'e' ? '+' : '-') . (function_exists('formatarMoeda') ? formatarMoeda($tr['valor']) : number_format($tr['valor'], 2, ',', '.')); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- MODAL COM CORREÇÃO NO FORMULÁRIO -->
<div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
        <h3 class="text-xl font-bold mb-4">Nova Transação</h3>
        <form action="salvar_transacao.php" method="POST" class="space-y-4">
            <input type="text" name="titulo" required placeholder="Título" class="w-full border rounded-xl px-4 py-2">
            <input type="number" step="0.01" name="valor" required placeholder="Valor" class="w-full border rounded-xl px-4 py-2">
            
            <!-- CAMPO DE CATEGORIA ADICIONADO -->
            <select name="cat" class="w-full border rounded-xl px-4 py-2">
                <option value="Geral">Geral</option>
                <option value="Administrativo">Administrativo</option>
                <option value="Manutenção">Manutenção</option>
                <option value="Marketing">Marketing</option>
                <option value="Compras">Compras</option>
                <option value="Salários">Salários</option>
            </select>

            <select name="tipo" class="w-full border rounded-xl px-4 py-2">
                <option value="e">Entrada (+)</option>
                <option value="s">Saída (-)</option>
            </select>
            <div class="flex gap-3">
                <button type="button" onclick="this.closest('#modalTransacao').classList.replace('flex', 'hidden')" class="flex-1 py-2 text-slate-500">Cancelar</button>
                <button type="submit" class="flex-1 py-2 bg-teal-500 text-white font-bold rounded-xl">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
// GRÁFICO DE LINHA
const ctxLinha = document.getElementById('chartLinha').getContext('2d');
new Chart(ctxLinha, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($labels_meses); ?>,
        datasets: [{
            label: 'Receitas',
            data: <?php echo json_encode($dados_receitas); ?>,
            borderColor: '#2ec4b6',
            backgroundColor: 'rgba(46, 196, 182, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Despesas',
            data: <?php echo json_encode($dados_despesas); ?>,
            borderColor: '#e71d36',
            backgroundColor: 'rgba(231, 29, 54, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

// GRÁFICO DE PIZZA (APENAS UM ÚNICO SCRIPT)
const ctxPizza = document.getElementById('chartPizza').getContext('2d');
new Chart(ctxPizza, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($categorias_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($categorias_valores); ?>,
            backgroundColor: ['#0d1b2a', '#1b434d', '#2ec4b6', '#3a86ff', '#8338ec', '#ff9f1c']
        }]
    },
    options: { 
        responsive: true, 
        cutout: '70%',
        plugins: {
            legend: { position: 'top' }
        }
    }
});

<div class="flex gap-2">
    <!-- Botão de Reset -->
    <a href="reset.php" onclick="return confirm('Tem certeza que deseja apagar todos os dados?')" class="bg-rose-500/10 text-rose-500 border border-rose-500/20 px-4 py-2 rounded-lg font-medium hover:bg-rose-500 hover:text-white transition">
        Limpar Dados
    </a>

    <!-- Seu botão de adicionar transação atual -->
    <button onclick="document.getElementById('modalTransacao').classList.replace('hidden', 'flex')" class="bg-teal-500 text-white px-4 py-2 rounded-lg font-medium shadow-lg hover:bg-teal-600 transition">
        + Adicionar Transação
    </button>
</div>
</script>
</body>
</html>