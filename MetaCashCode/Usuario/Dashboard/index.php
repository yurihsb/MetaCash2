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
    <!-- Adicionando FontAwesome para os ícones do botão -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex bg-gray-50">

<aside class="w-64 bg-[#0d1b2a] min-h-screen p-6 text-white flex flex-col border-r border-white/5">
    <div class="mb-10 flex items-center gap-3">
        <div class="text-white font-bold text-lg">MetaCash</div>
    </div>
    <nav class="space-y-2 flex-1">
        <a href="index.php" class="flex items-center gap-3 bg-[#1b434d] text-[#2ec4b6] p-3 rounded-xl font-medium">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a href="../Transacoes.php/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:bg-slate-800 transition">
            <i class="fas fa-exchange-alt"></i><span class="font-medium">Transações</span>
        </a>
        
        <!-- NOVO BOTÃO DE RELATÓRIO NA SIDEBAR -->
        <button onclick="toggleRelatorioModal()" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 transition border border-transparent hover:border-slate-700">
            <i class="fas fa-file-pdf"></i><span class="font-medium">Baixar Relatório</span>
        </button>
    </nav>
</aside>

<main class="flex-1 p-8">
    <!-- Cabeçalho -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight">Visão Geral Financeira</h1>
                <p class="text-lg text-[#334155] mt-2">Acompanhe o desempenho financeiro da sua empresa</p>
            </div>
            <button onclick="document.getElementById('modalTransacao').classList.replace('hidden', 'flex')" class="bg-teal-500 text-white px-4 py-2 rounded-lg font-medium shadow-lg hover:bg-teal-600 transition">
                + Adicionar Transação
            </button>
        </div>
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

    <!-- GRÁFICOS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-4"><span class="text-teal-500">Receitas</span> vs <span class="text-rose-500">Despesas</span></h3>
            <canvas id="chartLinha" height="200"></canvas>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-4">Composição Financeira</h3>
            <canvas id="chartPizza" height="200"></canvas>
        </div>
    </div>

    <!-- CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
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

    <!-- TRANSAÇÕES -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="font-bold text-slate-800 mb-4">Transações Recentes</h3>
        <div class="space-y-4">
            <?php foreach($transacoes as $id => $tr): ?>
                <div class="flex justify-between items-center py-2 border-b border-slate-50 last:border-0 group">
                    <div class="flex items-center gap-4">
                        <a href="excluir_transacao.php?id=<?php echo $id; ?>" onclick="return confirm('Excluir esta transação?')" class="opacity-0 group-hover:opacity-100 text-rose-500 transition-opacity">
                           <i class="fas fa-trash"></i>
                        </a>
                        <div>
                            <p class="font-semibold text-slate-700"><?php echo $tr['titulo']; ?></p>
                            <p class="text-xs text-slate-400"><?php echo isset($tr['cat']) ? $tr['cat'] : 'Geral'; ?> • <?php echo $tr['data']; ?></p>
                        </div>
                    </div>
                    <p class="font-bold <?php echo $tr['tipo'] == 'e' ? 'text-teal-500' : 'text-rose-500'; ?>">
                        <?php echo ($tr['tipo'] == 'e' ? '+' : '-') . (function_exists('formatarMoeda') ? formatarMoeda($tr['valor']) : number_format($tr['valor'], 2, ',', '.')); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- MODAL NOVA TRANSAÇÃO -->
<div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
        <h3 class="text-xl font-bold mb-4">Nova Transação</h3>
        <form action="salvar_transacao.php" method="POST" class="space-y-4">
            <input type="text" name="titulo" required placeholder="Título" class="w-full border rounded-xl px-4 py-2">
            <input type="number" step="0.01" name="valor" required placeholder="Valor" class="w-full border rounded-xl px-4 py-2">
<!-- Dentro do MODAL NOVA TRANSAÇÃO -->
<select name="cat" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-teal-500">
    <option value="Geral">Geral</option>
    <option value="Vendas">Vendas</option> <!-- Nova Categoria -->
    <option value="Administrativo">Administrativo</option>
    <option value="Salários">Salários</option>
    <option value="Marketing">Marketing</option>
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

<!-- MODAL BAIXAR RELATÓRIO (Adicionado conforme solicitação) -->
<div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6 text-slate-800">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-xl font-bold">Baixar Relatório</h3>
            <button onclick="toggleRelatorioModal()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        
        <form action="../Transacoes.php/gerar_pdf.php" method="GET" target="_blank" class="space-y-6">
            <div>
                <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Tipo de Transação</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer"><input type="radio" name="tipo" value="e" class="hidden peer"><div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Receita</div></label>
                    <label class="cursor-pointer"><input type="radio" name="tipo" value="s" class="hidden peer"><div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Despesa</div></label>
                    <label class="cursor-pointer"><input type="radio" name="tipo" value="todos" checked class="hidden peer"><div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Ambos</div></label>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Período</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer"><input type="radio" name="periodo" value="mensal" checked class="hidden peer" onclick="document.getElementById('mesDiv').style.display='block'"><div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Mensal</div></label>
                    <label class="cursor-pointer"><input type="radio" name="periodo" value="anual" class="hidden peer" onclick="document.getElementById('mesDiv').style.display='none'"><div class="text-sm text-center p-2 rounded-lg border bg-blue-50 text-blue-600 peer-checked:bg-slate-800 peer-checked:text-white transition">Anual</div></label>
                </div>
            </div>

            <div id="mesDiv">
                <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Mês</label>
                <select name="mes" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="05" selected>Maio</option>
                    <option value="06">Junho</option>
                    <!-- Adicione outros conforme necessário -->
                </select>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Ano</label>
                <select name="ano" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="2026" selected>2026</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="toggleRelatorioModal()" class="flex-1 py-3 border border-slate-300 text-slate-600 font-bold rounded-xl">Cancelar</button>
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-slate-800 to-teal-600 text-white font-bold rounded-xl shadow-lg">Baixar PDF</button>
            </div>
        </form>
    </div>
</div>

<script>
// FUNÇÕES DOS MODAIS
function toggleRelatorioModal() {
    const modal = document.getElementById('modalRelatorio');
    modal.classList.toggle('hidden');
    modal.classList.toggle('flex');
}

// CONFIGURAÇÃO DOS GRÁFICOS (Mantida conforme original)
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
    options: { responsive: true, cutout: '70%', plugins: { legend: { position: 'top' } } }
});
</script>
</body>
</html>