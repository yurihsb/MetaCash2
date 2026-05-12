function toggleModal() {
    const modal = document.getElementById('modalTransacao');
    modal.classList.toggle('hidden');
}

// Controle do Modal
const modal = document.getElementById('modalTransacao');
const btnAbrir = document.getElementById('btnAbrirModal');
const btnFechar = document.getElementById('btnFecharModal');

btnAbrir.addEventListener('click', () => {
    modal.classList.replace('hidden', 'flex');
});

btnFechar.addEventListener('click', () => {
    modal.classList.replace('flex', 'hidden');
});

// Fechar ao clicar fora do modal
window.addEventListener('click', (e) => {
    if (e.target === modal) modal.classList.replace('flex', 'hidden');
});

// Inicialização dos Gráficos (Lendo o JSON do PHP)
const rawData = document.getElementById('financeData').textContent;
const dadosFinanceiros = JSON.parse(rawData);

// Exemplo de uso dos dados no Chart.js
console.log("Dados carregados:", dadosFinanceiros.labels);