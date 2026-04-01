<?php
// Inclui o api.php para ter acesso à variável $data
include_once(__DIR__ . "/api.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Transações</title>
    <link rel="stylesheet" href="../Transaçoes.php/transaçoes.css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo-area">
            <div class="logo-icon">
                <img src="../Transaçoes.php/img/logoCyano.png" alt="Logo MetaCash">
            </div>
            <div>
                <h2>MetaCash</h2>
                <small>Gestão Empresarial</small>
            </div>
        </div>
    </div>
    
<nav class="sidebar-nav">
    <a href="/MetaCashCode/Dashboard/index.php" class="nav-btn">
        <i class="fa-solid fa-table-cells-large nav-icon"></i>
        <span>Dashboard</span>
    </a>
    <a href="#" class="nav-btn active">
        <i class="fa-solid fa-money-bill-transfer nav-icon"></i>
        <span>Transações</span>
    </a>
    <a href="#" class="nav-btn">
        <i class="fa-regular fa-file-lines nav-icon"></i>
        <span>Relatórios</span>
    </a>
    <a href="#" class="nav-btn">
        <i class="fa-solid fa-users nav-icon"></i>
        <span>Equipe</span>
    </a>
    <a href="#" class="nav-btn">
        <i class="fa-solid fa-dollar-sign nav-icon"></i>
        <span>Dados Financeiros</span>
    </a>
</nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="avatar">E</div>
            <div>
                <strong>Empresa LTDA</strong><br>
                <small>empresa@exemplo.com</small>
            </div>
        </div>
        <p class="logout">Sair</p>
    </div>
</aside>

<main class="content">
    <header class="main-header">
        <div class="title-area">
            <h1>Transações</h1>
            <p>Gerencie todas as transações financeiras</p>
        </div>
        <button class="btn-add">+ Adicionar Transação</button>
    </header>

<div class="search-container">
    <div class="search-input-wrapper">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" placeholder="Buscar transações...">
    </div>
    <button class="btn-filter">
        <i class="fa-solid fa-filter"></i>
        Filtros
    </button>
</div>

    <div class="cards">
        <div class="card">
            <h3>Total de Receitas</h3>
            <div class="valor receita">R$ <?php echo $data['resumo']['receitas']; ?></div>
            <small>4 transações este mês</small>
        </div>
        <div class="card">
            <h3>Total de Despesas</h3>
            <div class="valor despesa">R$ <?php echo $data['resumo']['despesas']; ?></div>
            <small>4 transações este mês</small>
        </div>
        <div class="card">
            <h3>Saldo do Período</h3>
            <div class="valor saldo">R$ <?php echo $data['resumo']['saldo']; ?></div>
            <small>Lucro Líquido</small>
        </div>
    </div>

<div class="lista-transacoes">
    <div class="lista-header">
        <h3>Transações Recentes</h3>
        <a href="#">Ver todas</a>
    </div>
    
    <?php foreach ($data['transacoes'] as $t): 
        // Lógica para definir a classe e o ícone baseados no nome ou categoria
        $iconClass = ($t['tipo'] == 'entrada') ? 'icon-green' : 'icon-blue';
        $iconSvg = '';

        if (stripos($t['nome'], 'Venda') !== false) {
            $iconSvg = '<svg viewbox="0 0 24 24"><path d="M5 19L19 5M19 5H11M19 5V13" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        } elseif (stripos($t['nome'], 'Fornecedor') !== false) {
            $iconSvg = '<svg viewbox="0 0 24 24"><path d="M19 8H5M5 19H19M7 8V6A2 2 0 1 1 17 6V8M3 8V21H21V8" stroke-width="2"/></svg>';
        } elseif (stripos($t['nome'], 'Aluguel') !== false) {
            $iconSvg = '<svg viewbox="0 0 24 24"><path d="M3 12L12 3L21 12M5 12V21H19V12M9 21V15H15V21" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        } elseif (stripos($t['nome'], 'Folha') !== false) {
            $iconSvg = '<svg viewbox="0 0 24 24"><path d="M5 8V18C5 20.2 6.8 22 9 22H15C17.2 22 19 20.2 19 18V8M5 8H19M5 8V6C5 4.9 5.9 4 7 4H17C18.1 4 19 4.9 19 6V8M7 11V15" stroke-width="2"/><path d="M11 11V15M15 11V15"/></svg>';
        } elseif (stripos($t['nome'], 'Manutenção') !== false) {
            $iconSvg = '<svg viewbox="0 0 24 24"><path d="M19 17H5M5 17C4.4 17 3.9 16.6 3.7 16.1L2 11H22L20.3 16.1C20.1 16.6 19.6 17 19 17ZM5 17C5.6 17 6 17.4 6 18C6 18.6 5.6 19 5 19C4.4 19 4 18.6 4 18C4 17.4 4.4 17 5 17ZM19 17C19.6 17 20 17.4 20 18C20 18.6 19.6 19 19 19C18.4 19 18 18.6 18 18C18 17.4 18.4 17 19 17Z" stroke-width="2"/></svg>';
        } else {
            // Ícone padrão caso nenhum combine
            $iconSvg = '<svg viewbox="0 0 24 24"><path d="M12 4V20M4 12H20" stroke-width="2"/></svg>';
        }
    ?>
        <div class="item-transacao">
            <div class="item-main">
                <div class="icon-pill <?php echo $iconClass; ?>">
                    <?php echo $iconSvg; ?>
                </div>
                <div>
                    <strong><?php echo $t['nome']; ?></strong><br>
                    <small><?php echo $t['cat']; ?> • <?php echo $t['data']; ?></small>
                </div>
            </div>
            <div class="item-valor <?php echo $t['tipo'] == 'entrada' ? 'receita' : 'despesa'; ?>">
                <?php 
                    $prefixo = ($t['tipo'] == 'entrada') ? '+' : '-';
                    echo $prefixo . 'R$ ' . number_format(abs($t['valor']), 2, ',', '.'); 
                ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</main>

</body>
</html>