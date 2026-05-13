/**
 * SISTEMA DE GESTÃO FINANCEIRA - SCRIPT CONSOLIDADO OTIMIZADO
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. GESTÃO DE MODAIS
    const setupModal = (btnId, modalId) => {
        const btn = document.getElementById(btnId);
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const toggle = (show) => {
            modal.classList.toggle('hidden', !show);
            modal.classList.toggle('flex', show);
        };

        if (btn) btn.addEventListener('click', () => toggle(true));

        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.classList.contains('btn-fechar')) {
                toggle(false);
            }
        });
    };

    // IDs atualizados conforme os botões da sua Sidebar e Main
    setupModal('btnAbrirModal', 'modalTransacao'); 
    setupModal('btnAbrirRelatorio', 'modalRelatorio');

    // 2. INICIALIZAÇÃO DE GRÁFICOS
    if (typeof chartData !== 'undefined') {
        renderizarGraficos(chartData);
    }
});

function renderizarGraficos(data) {
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false
    };

    // --- Gráfico de Linha (Receitas vs Despesas Negativas vs Lucro) ---
    const ctxLinha = document.getElementById('chartLinha')?.getContext('2d');
    if (ctxLinha) {
        new Chart(ctxLinha, {
            type: 'line',
            data: {
                labels: data.labelsMeses,
                datasets: [
                    {
                        label: 'Receitas',
                        data: data.receitas,
                        borderColor: '#2ec4b6',
                        backgroundColor: 'rgba(46, 196, 182, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Despesas',
                        data: data.despesas, // Valores negativos vindos do PHP
                        borderColor: '#e71d36',
                        backgroundColor: 'rgba(231, 29, 54, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Lucro Líquido',
                        // Soma (pois despesa já é negativa: 1000 + (-400) = 600)
                        data: data.receitas.map((rec, i) => rec + data.despesas[i]),
                        borderColor: '#3a86ff',
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: { 
                ...commonOptions, 
                plugins: { 
                    legend: { display: true, position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: (value) => 'R$ ' + value.toLocaleString('pt-BR')
                        }
                    }
                }
            }
        });
    }

    // --- Gráfico de Rosca (Distribuição de Gastos) ---
    const ctxPizza = document.getElementById('chartPizza')?.getContext('2d');
    if (ctxPizza) {
        new Chart(ctxPizza, {
            type: 'doughnut',
            data: {
                labels: data.catLabels,
                datasets: [{
                    // Math.abs garante que o gráfico desenhe mesmo com valores negativos
                    data: data.catValores.map(v => Math.abs(v)),
                    backgroundColor: ['#0d1b2a', '#1b434d', '#2ec4b6', '#3a86ff', '#8338ec', '#ff9f1c', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                ...commonOptions,
                cutout: '70%',
                plugins: {
                    legend: { position: 'right' },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                // Recupera o valor original (negativo) para exibir no texto
                                const valorOriginal = data.catValores[ctx.dataIndex];
                                return ` ${ctx.label}: R$ ${valorOriginal.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                            }
                        }
                    }
                }
            }
        });
    }
}