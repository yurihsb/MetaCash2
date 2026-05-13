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
            if (e.target === modal || e.target.classList.contains('btn-fechar') || e.target.innerText === 'Cancelar') {
                toggle(false);
            }
        });
    };

    setupModal('btnAbrirModal', 'modalTransacao'); 
    setupModal('btnAbrirRelatorio', 'modalRelatorio');

    // 2. INICIALIZAÇÃO DE GRÁFICOS
    const data = (typeof CHART_DATA !== 'undefined') ? CHART_DATA : (typeof chartData !== 'undefined' ? chartData : null);
    
    if (data) {
        renderizarGraficos(data);
    }
});

function renderizarGraficos(data) {
    // Adicionado resizeDelay para evitar loops de redimensionamento em containers flexíveis
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        resizeDelay: 50 
    };

    // --- Gráfico de Linha ---
    const ctxLinha = document.getElementById('chartLinha')?.getContext('2d');
    if (ctxLinha) {
        let chartStatus = Chart.getChart("chartLinha");
        if (chartStatus !== undefined) { chartStatus.destroy(); }

        new Chart(ctxLinha, {
            type: 'line',
            data: {
                labels: data.labels || data.labelsMeses,
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
            options: { 
                ...commonOptions, 
                plugins: { 
                    legend: { display: true, position: 'top' }
                }
            }
        });
    }

    // --- Gráfico de Rosca ---
    const ctxPizza = document.getElementById('chartPizza')?.getContext('2d');
    if (ctxPizza) {
        let chartStatus = Chart.getChart("chartPizza");
        if (chartStatus !== undefined) { chartStatus.destroy(); }

        new Chart(ctxPizza, {
            type: 'doughnut',
            data: {
                labels: data.catLabels,
                datasets: [{
                    data: data.catValores.map(v => Math.abs(v)),
                    backgroundColor: ['#0d1b2a', '#1b434d', '#2ec4b6', '#3a86ff', '#8338ec', '#ff9f1c'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                ...commonOptions,
                cutout: '70%',
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    }
}

function toggleModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    if (modal.classList.contains('hidden')) {
        modal.classList.replace('hidden', 'flex');
    } else {
        modal.classList.replace('flex', 'hidden');
    }
}

function toggleRelatorioModal() {
    toggleModal('modalRelatorio');
}