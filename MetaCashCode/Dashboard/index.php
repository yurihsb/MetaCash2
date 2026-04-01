<?php include('data.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MetaCash - Gestão</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="flex">

<aside class="w-64 bg-slate-900 min-h-screen p-6 text-white flex flex-col">
    <div class="mb-10 flex items-center gap-3">
        <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center">
            <img src="img/logoCyano.png" alt="MetaCash" onerror="this.style.display='none';">
            <span class="text-white font-bold text-xl"></span>
        </div>
        <div>
            <div class="text-white font-bold text-lg leading-none">MetaCash</div>
            <div class="text-slate-500 text-[10px] uppercase tracking-wider">Gestão Empresarial</div>
        </div>
    </div>

    <nav class="space-y-2 flex-1 text-slate-400">
        <a href="#" class="flex items-center gap-3 bg-teal-500/10 text-teal-400 p-3 rounded-xl font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            Dashboard
        </a>
        <a href="#" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white p-3 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            Transações
        </a>
        <a href="#" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white p-3 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m10 10V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2z" /></svg>
            Relatórios
        </a>
        <a href="#" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white p-3 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Equipe
        </a>
        <a href="#" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white p-3 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Dados Financeiros
        </a>
    </nav>

    <div class="mt-auto border-t border-slate-800 pt-6">
        <div class="bg-slate-800/50 p-4 rounded-2xl flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center font-bold text-white">
                E
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold truncate">Empresa LTDA</p>
                <p class="text-[10px] text-slate-500 truncate">empresa@exemplo.com</p>
            </div>
        </div>
        <a href="#" class="flex items-center gap-3 text-slate-400 hover:text-rose-400 transition text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
            Sair
        </a>
    </div>
</aside>

    <main class="flex-1 p-8 bg-gray-50">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Visão Geral Financeira</h1>
                <p class="text-slate-500 text-sm">Acompanhe o desempenho financeiro da sua empresa</p>
            </div>
            <button class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg transition font-medium flex items-center gap-2">
                <span>+</span> Adicionar Transação
            </button>
        </div>

        <section class="bg-[#0f1c30] rounded-2xl p-8 text-white mb-8 shadow-xl">
            <div class="flex items-center gap-2 text-slate-400 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-medium uppercase tracking-wider">Saldo Total</span>
            </div>

            <div class="text-4xl font-bold mb-8">
                R$ <?php echo number_format($dados['saldo_total'], 2, ',', '.'); ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white/5 border border-white/10 p-4 rounded-xl backdrop-blur-sm">
                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                        <span class="text-teal-400 font-bold">↗</span> Receitas
                    </div>
                    <div class="text-xl font-semibold text-white">
                        R$ <?php echo number_format($dados['receitas_mes'], 2, ',', '.'); ?>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 p-4 rounded-xl backdrop-blur-sm">
                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                        <span class="text-rose-400 font-bold">↘</span> Despesas
                    </div>
                    <div class="text-xl font-semibold text-white">
                        R$ <?php echo number_format($dados['despesas_mes'], 2, ',', '.'); ?>
                    </div>
                </div>
            </div>
        </section>

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

        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <h3 class="font-bold mb-4 text-slate-800">Receitas vs Despesas</h3>
                <div class="h-64"><canvas id="graficoLinha"></canvas></div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <h3 class="font-bold mb-4 text-slate-800">Despesas por Categoria</h3>
                <div class="h-64"><canvas id="graficoPizza"></canvas></div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex justify-between mb-4">
                <h3 class="font-bold text-slate-800">Transações Recentes</h3>
                <a href="#" class="text-teal-500 text-sm font-medium">Ver todas</a>
            </div>
            <div class="space-y-4">
                <?php foreach($transacoes as $tr): ?>
                <div class="flex justify-between items-center py-2 border-b border-slate-50 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center <?php echo $tr['tipo'] == 'e' ? 'bg-teal-50 text-teal-500' : 'bg-slate-100 text-slate-500'; ?>">
                            <?php echo $tr['tipo'] == 'e' ? '↑' : '↓'; ?>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-700"><?php echo $tr['titulo']; ?></p>
                            <p class="text-xs text-slate-400"><?php echo $tr['cat']; ?> • <?php echo $tr['data']; ?></p>
                        </div>
                    </div>
                    <p class="font-bold <?php echo $tr['tipo'] == 'e' ? 'text-teal-500' : 'text-rose-500'; ?>">
                        <?php echo ($tr['tipo'] == 'e' ? '+' : '-') . formatarMoeda($tr['valor']); ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script id="financeData" type="application/json">
    {
        "labels": <?php echo json_encode($labels_meses); ?>,
        "receitas": <?php echo json_encode($dados_receitas); ?>,
        "despesas": <?php echo json_encode($dados_despesas); ?>,
        "categorias": <?php echo json_encode($categorias); ?>
    }
    </script>

    <script src="Script/script.js"></script>
</body>
</html>