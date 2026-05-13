<!-- MODAL NOVA TRANSAÇÃO -->
<div id="modalTransacao" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
        <h3 class="text-xl font-bold mb-4 text-slate-800">Nova Transação</h3>
        <form action="salvar_transacao.php" method="POST" class="space-y-4">
            <input type="text" name="titulo" required placeholder="Título" class="w-full border rounded-xl px-4 py-2">
            <input type="number" step="0.01" name="valor" required placeholder="Valor" class="w-full border rounded-xl px-4 py-2">
            <select name="cat" class="w-full border rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-teal-500">
                <option value="Geral">Geral</option>
                <option value="Vendas">Vendas</option>
                <option value="Administrativo">Administrativo</option>
                <option value="Salários">Salários</option>
                <option value="Marketing">Marketing</option>
            </select>
            <select name="tipo" class="w-full border rounded-xl px-4 py-2">
                <option value="e">Entrada (+)</option>
                <option value="s">Saída (-)</option>
            </select>
            <div class="flex gap-3">
                <button type="button" onclick="toggleModal('modalTransacao')" class="flex-1 py-2 text-slate-500">Cancelar</button>
                <button type="submit" class="flex-1 py-2 bg-teal-500 text-white font-bold rounded-xl">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL BAIXAR RELATÓRIO -->
<div id="modalRelatorio" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
    <!-- ... Cole aqui o código do modal de relatório que estava no seu index original ... -->
</div>