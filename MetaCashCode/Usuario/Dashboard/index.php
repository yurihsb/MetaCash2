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
    <title>MetaCash - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex min-h-screen">

<!-- SIDEBAR PADRONIZADA (FIXA) -->
<aside class="w-64 bg-[#0f172a] text-white p-4 flex flex-col fixed h-screen shrink-0 z-40">
    <div class="flex items-center gap-3 mb-10 px-2 pt-2">
        <div class="bg-[#2dd4bf] p-2 rounded-lg text-[#0f172a]">
            <i class="fas fa-chart-line text-xl"></i>
        </div>
        <div class="flex flex-col">
            <span class="font-bold text-xl leading-tight text-white">MetaCash</span>
            <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Gestão Empresarial</span>
        </div>
    </div>

    <nav class="flex-1 space-y-2">
        <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#29aeb0] text-white shadow-lg transition">
            <i class="fas fa-th-large"></i><span class="font-medium">Dashboard</span>
        </a>
        <a href="../Transacoes.php/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
            <i class="fas fa-exchange-alt"></i><span class="font-medium">Transações</span>
        </a>
        <button onclick="toggleModal('modalRelatorio')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition border border-transparent hover:border-slate-700 text-left">
            <i class="fas fa-file-pdf"></i><span class="font-medium">Baixar Relatório</span>
        </button>
    </nav>

    <!-- PARTE INFERIOR DA SIDEBAR ATUALIZADA -->
    <div class="mt-auto pt-6 border-t border-slate-800 space-y-4 pb-2">
        <a href="../Perfil.php/index.php" class="bg-[#1e3a5f]/40 p-3 rounded-2xl flex items-center gap-3 border border-slate-700/50 hover:bg-[#1e3a5f]/60 transition block group">
            <div class="w-10 h-10 bg-[#2dd4bf] rounded-full flex items-center justify-center text-[#0f172a] font-bold text-lg shrink-0 group-hover:scale-105 transition-transform">U</div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-bold truncate">Usuário</span>
                <span class="text-[10px] text-gray-400 truncate">usuario@exemplo.com</span>
            </div>
        </a>
        <a href="../logout.php" class="flex items-center gap-3 px-4 py-2 text-gray-400 hover:text-white transition group">
            <i class="fas fa-sign-out-alt rotate-180 group-hover:text-red-400 transition-colors"></i>
            <span class="font-medium">Sair</span>
        </a>
    </div>
</aside>

<main class="flex-1 p-8 ml-64 w-full">
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-4xl font-extrabold text-[#0f172a] tracking-tight">Visão Geral Financeira</h1>
                <p class="text-lg text-[#334155] mt-2">Acompanhe o desempenho financeiro da sua empresa</p>
            </div>
            <button onclick="toggleModal('modalTransacao')" class="bg-[#2dd4bf] text-[#0f172a] px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-teal-400 transition">
                + Adicionar Transação
            </button>
        </div>
    </div>

    <!-- SEÇÃO DE SALDO -->
    <section class="bg-[#0f1c30] rounded-3xl p-8 text-white mb-8 shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <span class="text-xs text-slate-400 uppercase tracking-widest font-bold">Saldo Total Disponível</span>
            <div class="text-5xl font-bold mt-2 mb-8 tracking-tighter">
                R$ <?php echo isset($dados['saldo_total']) ? number_format($dados['saldo_total'], 2, ',', '.') : '0,00'; ?>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white/5 border border-white/10 p-5 rounded-2xl flex items-center gap-4">
                    <div class="w-10 h-10 bg-teal-500/20 text-teal-400 rounded-full flex items-center justify-center"><i class="fas fa-arrow-up text-sm"></i></div>
                    <div>
                        <div class="text-xs text-slate-400 font-bold uppercase">Receitas do Mês</div>
                        <div class="text-xl font-bold">R$ <?php echo isset($dados['receitas_mes']) ? number_format($dados['receitas_mes'], 2, ',', '.') : '0,00'; ?></div>
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 p-5 rounded-2xl flex items-center gap-4">
                    <div class="w-10 h-10 bg-rose-500/20 text-rose-400 rounded-full flex items-center justify-center"><i class="fas fa-arrow-down text-sm"></i></div>
                    <div>
                        <div class="text-xs text-slate-400 font-bold uppercase">Despesas do Mês</div>
                        <div class="text-xl font-bold">R$ <?php echo isset($dados['despesas_mes']) ? number_format($dados['despesas_mes'], 2, ',', '.') : '0,00'; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GRÁFICOS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fas fa-chart-line text-slate-400"></i> Desempenho Mensal</h3>
            <div class="relative h-[300px] w-full"><canvas id="chartLinha"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fas fa-chart-pie text-slate-400"></i> Distribuição por Categorias</h3>
            <div class="relative h-[300px] w-full"><canvas id="chartPizza"></canvas></div>
        </div>
    </div>

    <!-- CARDS DE MÉTRICAS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <?php foreach($cards as $titulo => $info): ?>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-[#2dd4bf] transition group">
                <div class="flex justify-between text-xs font-bold mb-3">
                    <span class="text-slate-400 uppercase tracking-wider"><?php echo $titulo; ?></span>
                    <span class="px-2 py-0.5 rounded-lg bg-gray-50 <?php echo $info['cor']; ?>"><?php echo $info['porcentagem']; ?></span>
                </div>
                <div class="text-2xl font-black text-slate-800">R$ <?php echo $info['valor']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- TABELA -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Transações Recentes</h3>
            <a href="../Transacoes.php/index.php" class="text-xs font-bold text-teal-600 hover:underline uppercase">Ver Extrato Completo</a>
        </div>
        <div class="divide-y divide-slate-50 px-6">
          <?php foreach($transacoes as $id => $tr): ?>
<div class="flex items-center justify-between p-4 border-b last:border-0 hover:bg-gray-50 transition-colors">
    <div class="flex items-center">
        <!-- Ícone dinâmico baseado no tipo -->
        <div class="w-10 h-10 rounded-full flex items-center justify-center <?php echo $tr['tipo'] == 'e' ? 'bg-teal-50' : 'bg-rose-50'; ?>">
            <i class="fas <?php echo $tr['tipo'] == 'e' ? 'fa-arrow-up text-teal-500' : 'fa-arrow-down text-rose-500'; ?> text-xs"></i>
        </div>
        
        <div class="ml-4">
            <p class="font-bold text-[#1e293b] leading-tight">
    <?php echo $tr['descricao'] ?? 'Sem descrição'; ?>
</p>
            <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">
                <?php echo $tr['cat']; ?> • <?php echo date('d/m/Y', strtotime($tr['data'])); ?>
            </p>
        </div>
    </div>
    
    <div class="flex items-center">
        <span class="font-bold text-sm <?php echo $tr['tipo'] == 'e' ? 'text-teal-500' : 'text-rose-500'; ?>">
            <?php echo ($tr['tipo'] == 'e' ? '+ ' : '- ') . 'R$ ' . number_format($tr['valor'], 2, ',', '.'); ?>
        </span>
        
        <!-- BOTÃO DE APAGAR (Corrigido para usar $id) -->
        <a href="excluir_transacao.php?id=<?php echo $id; ?>" 
           onclick="return confirm('Deseja realmente excluir esta transação?')"
           class="ml-6 text-gray-300 hover:text-rose-500 transition-all duration-200">
            <i class="fas fa-trash-alt"></i>
        </a>
    </div>
</div>
<?php endforeach; ?>
        </div>
    </div>
</main>

<!-- MODAL ADICIONAR TRANSAÇÃO -->
<!-- MODAL ADICIONAR TRANSAÇÃO ATUALIZADO -->
<div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-md overflow-hidden shadow-2xl">
        <!-- Header -->
        <div class="p-6 flex justify-between items-center">
            <h3 class="font-bold text-[#0f172a] text-2xl">Nova Transação</h3>
            <button onclick="toggleModal('modalTransacao')" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="processamento.php" method="POST" class="p-6 pt-0 space-y-5">
            <!-- Seletor Tipo (IDÊNTICO À IMAGEM) -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Tipo de Transação</label>
                <div class="grid grid-cols-2 gap-3 p-1 bg-slate-100 rounded-xl">
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo" value="e" checked class="peer hidden">
                        <div class="py-3 text-center rounded-lg font-bold text-slate-500 peer-checked:bg-[#1e3a5f] peer-checked:text-white transition-all">
                            Receita
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo" value="s" class="peer hidden">
                        <div class="py-3 text-center rounded-lg font-bold text-slate-500 peer-checked:bg-[#1e3a5f] peer-checked:text-white transition-all">
                            Despesa
                        </div>
                    </label>
                </div>
            </div>

            <!-- Descrição -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" placeholder="Ex: Venda para Cliente XYZ" required 
                    class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-sky-500 text-slate-700">
            </div>

            <!-- Valor -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Valor (R$) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="valor" placeholder="1000.00" required 
                    class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-sky-500 text-slate-700">
            </div>

            <!-- Categoria -->
<!-- Categoria -->
<div>
    <label class="block text-xs font-bold text-slate-500 mb-1">Categoria <span class="text-red-500">*</span></label>
    <div class="relative">
        <select name="categoria" required class="w-full appearance-none border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-sky-500 text-slate-700 bg-white">
            <option value="" disabled selected>Selecione uma categoria</option>
            <option value="Geral">Geral</option>
            <option value="Vendas">Vendas</option>
            <option value="Administrativo">Administrativo</option>
            <option value="Salários">Salários</option>
            <option value="Marketing">Marketing</option>
        </select>
        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
    </div>
</div>

            <!-- Data -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Data <span class="text-red-500">*</span></label>
                <input type="date" name="data" required 
                    class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-sky-500 text-slate-400">
            </div>

            <!-- Botões Inferiores -->
            <div class="grid grid-cols-2 gap-4 pt-2">
                <button type="button" onclick="toggleModal('modalTransacao')" 
                    class="w-full py-4 rounded-xl border-2 border-[#1e3a5f] text-[#1e3a5f] font-bold hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="submit" 
                    class="w-full py-4 rounded-xl bg-gradient-to-r from-[#1e3a5f] to-[#29aeb0] text-white font-bold shadow-lg hover:opacity-90 transition">
                    Adicionar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL BAIXAR RELATÓRIO -->
<!-- MODAL BAIXAR RELATÓRIO -->
<!-- MODAL BAIXAR RELATÓRIO ATUALIZADO -->
<div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-md overflow-hidden shadow-2xl">
        <!-- Header -->
        <div class="p-6 flex justify-between items-center border-b border-slate-100">
            <h3 class="font-bold text-[#0f172a] text-2xl">Baixar Relatório</h3>
            <button onclick="toggleModal('modalRelatorio')" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="gerar_relatorio.php" method="GET" class="p-6 space-y-6">
            <!-- Tipo de Transação -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Tipo de Transação</label>
                <div class="grid grid-cols-3 gap-2 p-1 bg-slate-100 rounded-xl">
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo" value="receita" class="peer hidden">
                        <div class="py-2 text-center rounded-lg text-sm font-bold text-slate-600 peer-checked:bg-[#1e3a5f] peer-checked:text-white transition-all bg-sky-100/50">Receita</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo" value="despesa" class="peer hidden">
                        <div class="py-2 text-center rounded-lg text-sm font-bold text-slate-600 peer-checked:bg-[#1e3a5f] peer-checked:text-white transition-all bg-sky-100/50">Despesa</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo" value="ambos" checked class="peer hidden">
                        <div class="py-2 text-center rounded-lg text-sm font-bold text-slate-600 peer-checked:bg-[#1e3a5f] peer-checked:text-white transition-all bg-sky-100/50">Ambos</div>
                    </label>
                </div>
            </div>

            <!-- Período (Mensal / Anual) -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Período</label>
                <div class="grid grid-cols-2 gap-2 p-1 bg-slate-100 rounded-xl">
                    <label class="cursor-pointer">
                        <input type="radio" name="periodo_tipo" value="mensal" checked onclick="togglePeriodoView('mensal')" class="peer hidden">
                        <div class="py-2 text-center rounded-lg text-sm font-bold text-slate-600 peer-checked:bg-[#1e3a5f] peer-checked:text-white transition-all bg-sky-100/50">Mensal</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="periodo_tipo" value="anual" onclick="togglePeriodoView('anual')" class="peer hidden">
                        <div class="py-2 text-center rounded-lg text-sm font-bold text-slate-600 peer-checked:bg-[#1e3a5f] peer-checked:text-white transition-all bg-sky-100/50">Anual</div>
                    </label>
                </div>
            </div>

            <!-- Mês e Ano -->
            <div class="grid grid-cols-2 gap-4">
                <div id="container-mes">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Mês</label>
                    <div class="relative">
                        <select name="mes" class="w-full appearance-none border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-sky-500 text-slate-700 bg-white">
                            <option value="01">Janeiro</option>
                            <option value="02">Fevereiro</option>
                            <option value="03">Março</option>
                            <option value="04">Abril</option>
                            <option value="05" selected>Maio</option>
                            <option value="06">Junho</option>
                            <option value="07">Julho</option>
                            <option value="08">Agosto</option>
                            <option value="09">Setembro</option>
                            <option value="10">Outubro</option>
                            <option value="11">Novembro</option>
                            <option value="12">Dezembro</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Ano</label>
                    <div class="relative">
                        <select name="ano" class="w-full appearance-none border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-sky-500 text-slate-700 bg-white">
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026" selected>2026</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- FORMATO DO ARQUIVO (ADICIONADO AQUI) -->
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-tighter">Formato do Arquivo</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer group">
                        <input type="radio" name="formato" value="pdf" checked class="peer hidden">
                        <div class="flex items-center justify-center gap-2 p-3 border-2 border-slate-100 rounded-xl text-slate-500 peer-checked:border-red-500 peer-checked:text-red-600 peer-checked:bg-red-50 transition-all">
                            <i class="fas fa-file-pdf"></i> <span class="font-bold">PDF</span>
                        </div>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="formato" value="csv" class="peer hidden">
                        <div class="flex items-center justify-center gap-2 p-3 border-2 border-slate-100 rounded-xl text-slate-500 peer-checked:border-green-500 peer-checked:text-green-600 peer-checked:bg-green-50 transition-all">
                            <i class="fas fa-file-csv"></i> <span class="font-bold">CSV</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Botões -->
            <div class="grid grid-cols-2 gap-4 pt-2">
                <button type="button" onclick="toggleModal('modalRelatorio')" 
                    class="w-full py-3 rounded-xl border-2 border-[#1e3a5f] text-[#1e3a5f] font-bold hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="submit" 
                    class="w-full py-3 rounded-xl bg-gradient-to-r from-[#0f172a] to-[#29aeb0] text-white font-bold shadow-lg hover:opacity-90 transition flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                    <i class="fas fa-download"></i> Baixar Relatório
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.classList.toggle('hidden');
    modal.classList.toggle('flex');
}

window.onload = function() {
    // --- GRÁFICO DE LINHA ---
    const ctxLinha = document.getElementById('chartLinha').getContext('2d');
    new Chart(ctxLinha, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labels_meses); ?>,
            datasets: [{
                label: 'Receitas',
                data: <?php echo json_encode($dados_receitas); ?>,
                borderColor: '#2dd4bf',
                backgroundColor: 'rgba(45, 212, 191, 0.1)',
                fill: true, tension: 0.4
            }, {
                label: 'Despesas',
                data: <?php echo json_encode($dados_despesas); ?>,
                borderColor: '#f43f5e',
                backgroundColor: 'rgba(244, 63, 94, 0.05)',
                fill: true, tension: 0.4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // --- GRÁFICO DE ROSCA (AJUSTADO PARA DINAMISMO +/-) ---
    const ctxPizza = document.getElementById('chartPizza').getContext('2d');
    // Recebe os valores reais (positivos e negativos) do PHP
    const dadosPuros = <?php echo json_encode($categorias_valores); ?>; 
    
    new Chart(ctxPizza, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($categorias_labels); ?>,
            datasets: [{
                // Math.abs desenha a fatia corretamente no gráfico
                data: dadosPuros.map(v => Math.abs(v)),
                backgroundColor: ['#0f172a', '#2dd4bf', '#3b82f6', '#8b5cf6', '#f43f5e'],
                borderWidth: 0
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false, 
            cutout: '75%',
            plugins: {
                legend: {
                    labels: {
                        generateLabels: (chart) => {
                            const original = Chart.defaults.plugins.legend.labels.generateLabels(chart);
                            return original.map((label, i) => {
                                const valorReal = dadosPuros[i];
                                
                                // LÓGICA DE SINAL: Define se é + ou - baseado no valor real
                                const sinal = valorReal >= 0 ? '+' : '-';
                                
                                const valorFormatado = Math.abs(valorReal).toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });

                                // Atualiza o texto da legenda dinamicamente
                                label.text = `${label.text}: ${sinal} R$ ${valorFormatado}`;
                                return label;
                            });
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const valorReal = dadosPuros[context.dataIndex];
                            const sinal = valorReal >= 0 ? '+' : '-';
                            const tipo = valorReal >= 0 ? 'Receita' : 'Despesa';
                            
                            const valorFormatado = Math.abs(valorReal).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2
                            });
                            
                            return ` ${tipo}: ${sinal} R$ ${valorFormatado}`;
                        }
                    }
                }
            }
        }
    });

    // --- FUNÇÃO DE RELATÓRIO ---
    window.gerarRelatorio = function() {
        const formato = document.querySelector('input[name="formato"]:checked')?.value || 'csv';
        const periodo = document.querySelector('select[name="periodo"]')?.value || 'atual';
        console.log("Iniciando download...", { formato, periodo });
        window.location.href = `gerar_relatorio.php?formato=${formato}&periodo=${periodo}`;
    }
};
</script>
<script src="Script/scripts.js"></script>
</script>
</body>
</html>