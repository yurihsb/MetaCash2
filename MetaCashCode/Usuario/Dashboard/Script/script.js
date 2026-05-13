/**
 * SISTEMA DE GESTÃO FINANCEIRA - SCRIPT CONSOLIDADO
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

        // Abrir modal
        if (btn) btn.addEventListener('click', () => toggle(true));

        // Fechar modal (clicando fora ou em botões de fechar)
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.classList.contains('btn-fechar')) {
                toggle(false);
            }
        });
    };

    // Inicializa os modais da página
    setupModal('btnAbrirModal', 'modalTransacao');
    setupModal('btnAbrirRelatorio', 'modalRelatorio');


    // 2. INICIALIZAÇÃO DE GRÁFICOS
    // Verifica se os dados globais existem (passados pelo PHP/Backend)
    if (typeof chartData !== 'undefined') {
        renderizarGraficos(chartData);
    }
});

/**
 * Função principal para renderização de gráficos usando Chart.js
 * @param {Object} data - Objeto contendo labelsMeses, receitas, despesas, catLabels, catValores
 */
function renderizarGraficos(data) {
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false
    };

    // --- Gráfico de Linha (Receitas vs Despesas) ---
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
                        data: data.despesas,
                        borderColor: '#e71d36',
                        backgroundColor: 'rgba(231, 29, 54, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: { ...commonOptions, plugins: { legend: { display: true, position: 'top' } } }
        });
    }

    // --- Gráfico de Rosca (Composição por Categoria) ---
    const ctxPizza = document.getElementById('chartPizza')?.getContext('2d');
    if (ctxPizza) {
        new Chart(ctxPizza, {
            type: 'doughnut',
            data: {
                labels: data.catLabels,
                datasets: [{
                    data: data.catValores.map(v => Math.abs(v)), // Garante valores positivos para o desenho
                    backgroundColor: ['#0d1b2a', '#1b434d', '#2ec4b6', '#3a86ff', '#8338ec', '#ff9f1c'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                ...commonOptions,
                cutout: '70%',
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const valor = data.catValores[ctx.dataIndex];
                                return ` ${ctx.label}: ${valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    
    
    
}

