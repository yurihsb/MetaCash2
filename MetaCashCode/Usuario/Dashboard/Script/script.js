/**
 * SISTEMA DE GESTÃO FINANCEIRA - METACASH
 * SCRIPT DE INTERFACE E RENDERIZAÇÃO DE GRÁFICOS
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. GESTÃO DE MODAIS (Transações e Relatórios)
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
            if (e.target === modal || e.target.closest('.btn-fechar') || e.target.innerText === 'Cancelar') {
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

/**
 * LÓGICA DO FORMULÁRIO DE RELATÓRIO
 */
function selecionarOpcao(elemento, grupo) {
    const container = elemento.closest('.grid');
    const botoes = container.querySelectorAll('.btn-relatorio');
    
    botoes.forEach(btn => {
        btn.classList.remove('ativo', 'bg-[#1e3a5f]', 'text-white', 'shadow-md');
        btn.classList.add('bg-[#dcf1f9]', 'text-[#1e293b]');
    });

    elemento.classList.remove('bg-[#dcf1f9]', 'text-[#1e293b]');
    elemento.classList.add('ativo', 'bg-[#1e3a5f]', 'text-white', 'shadow-md');

    if (grupo === 'periodo') {
        alternarCamposRelatorio(elemento.getAttribute('data-value'));
    }
}

function alternarCamposRelatorio(periodo) {
    const divMes = document.getElementById('divSelectMes');
    if (divMes) divMes.style.display = (periodo === 'anual') ? 'none' : 'block';
}

/**
 * RENDERIZAÇÃO DOS GRÁFICOS (CHART.JS)
 */
function renderizarGraficos(data) {
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        resizeDelay: 50 
    };

    // --- GRÁFICO DE LINHA ---
    const ctxLinha = document.getElementById('chartLinha')?.getContext('2d');
    if (ctxLinha) {
        let chartStatus = Chart.getChart("chartLinha");
        if (chartStatus) chartStatus.destroy();

        new Chart(ctxLinha, {
            type: 'line',
            data: {
                labels: data.labels || data.labelsMeses,
                datasets: [
                    {
                        label: 'Receitas',
                        data: data.receitas,
                        borderColor: '#2dd4bf',
                        backgroundColor: 'rgba(45, 212, 191, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3
                    },
                    {
                        label: 'Despesas',
                        data: data.despesas,
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3
                    }
                ]
            },
            options: { ...commonOptions }
        });
    }

    // --- GRÁFICO DE ROSCA (DIFERENCIANDO RECEITA/DESPESA) ---
   // --- GRÁFICO DE ROSCA (DIFERENCIANDO RECEITA/DESPESA) ---
    const ctxPizza = document.getElementById('chartPizza')?.getContext('2d');
    if (ctxPizza) {
        let chartStatus = Chart.getChart("chartPizza");
        if (chartStatus) chartStatus.destroy();

        new Chart(ctxPizza, {
            type: 'doughnut',
            data: {
                labels: data.catLabels, 
                datasets: [{
                    data: data.catValores.map(v => Math.abs(v)),
                    backgroundColor: ['#0f172a', '#2dd4bf', '#3b82f6', '#8b5cf6', '#f43f5e'],
                    borderWidth: 0,
                    spacing: 2
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { 
                            usePointStyle: true,
                            generateLabels: (chart) => {
                                const labelsOriginais = Chart.defaults.plugins.legend.labels.generateLabels(chart);
                                return labelsOriginais.map((label, i) => {
                                    const valorReal = data.catValores[i];
                                    
                                    // AJUSTE AQUI: Se for 0 ou maior, coloca '+'. Se for menor que 0, coloca '-'.
                                    const sinal = valorReal >= 0 ? '+' : '-';
                                    
                                    const valorFormatado = Math.abs(valorReal).toLocaleString('pt-BR', { 
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                    
                                    label.text = `${data.catLabels[i]}: ${sinal} R$ ${valorFormatado}`;
                                    return label;
                                });
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const valorReal = data.catValores[context.dataIndex];
                                
                                // AJUSTE AQUI TAMBÉM: Garante a lógica correta no balão ao passar o mouse
                                const tipo = valorReal >= 0 ? 'Receita' : 'Despesa';
                                const sinal = valorReal >= 0 ? '+' : '-';
                                
                                const valorFormatado = Math.abs(valorReal).toLocaleString('pt-BR', { 
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                                
                                return ` ${tipo}: ${sinal} R$ ${valorFormatado}`;
                            }
                        }
                    }
                }
            }
        });
    }

    /**
 * EXECUÇÃO DA EXPORTAÇÃO
 * Captura os filtros da interface e envia para o processamento PHP
 */
function gerarRelatorio() {
    // 1. Identifica o formato (PDF ou CSV)
    const formatoAtivo = document.querySelector('input[name="formato"]:checked');
    const formato = formatoAtivo ? formatoAtivo.value : 'pdf';

    // 2. Identifica o tipo de período (Mensal ou Anual)
    const btnPeriodoAtivo = document.querySelector('.btn-relatorio.ativo[data-value]');
    const periodoTipo = btnPeriodoAtivo ? btnPeriodoAtivo.getAttribute('data-value') : 'mensal';

    // 3. Captura os valores dos filtros
    const tipo = document.getElementById('export_tipo')?.value || 'ambos';
    const mes = document.getElementById('export_mes')?.value || '05'; // Padrão Maio
    const ano = document.getElementById('export_ano')?.value || '2026';

    // 4. Monta a URL de consulta
    const url = `gerar_relatorio.php?formato=${formato}&tipo=${tipo}&periodo_tipo=${periodoTipo}&mes=${mes}&ano=${ano}`;
    
    // 5. Redireciona para gerar o arquivo
    window.location.href = url;
}
}