<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCash - Cadastre sua empresa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>

    <div class="header-logo">
        <div class="icon"><i class="fa-solid fa-chart-line" style="color: white;"></i></div> 
        <h1>MetaCash</h1>
        <p>Cadastre sua empresa</p>
    </div>

    <div class="register-card">
        <h2>Criar Conta</h2>

        <?php if(isset($_SESSION['erro_cadastro'])): ?>
            <div class="error-msg" style="background:#fee2e2; color:#ef4444; padding:10px; border-radius:8px; margin-bottom:15px; font-size:13px;">
                <?php echo $_SESSION['erro_cadastro']; unset($_SESSION['erro_cadastro']); ?>
            </div>
        <?php endif; ?>
        
        <form action="processar_cadastro.php" method="POST">
            <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                
                <div class="form-group">
                    <label>Nome da Empresa <span style="color:red">*</span></label>
                    <div class="input-container">
                        <i class="fa-solid fa-building"></i>
                        <input type="text" name="nome_empresa" placeholder="Empresa LTDA" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nome do Responsável <span style="color:red">*</span></label>
                    <div class="input-container">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="nome_responsavel" placeholder="João Silva" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>E-mail Corporativo <span style="color:red">*</span></label>
                    <div class="input-container">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" placeholder="contato@empresa.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Telefone <span style="color:red">*</span></label>
                    <div class="input-container">
                        <i class="fa-solid fa-phone"></i>
                        <input type="text" name="telefone" placeholder="(11) 99999-9999" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>CNPJ <span style="color:red">*</span></label>
                    <div class="input-container">
                        <i class="fa-solid fa-id-card"></i>
                        <input type="text" name="cnpj" placeholder="00.000.000/0000-00" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>CPF <span style="color:red">*</span></label>
                    <div class="input-container">
                        <i class="fa-solid fa-address-card"></i>
                        <input type="text" name="cpf" placeholder="000.000.000-00" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Senha <span style="color:red">*</span></label>
                    <div class="input-container">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="senha" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirmar Senha <span style="color:red">*</span></label>
                    <div class="input-container">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="confirma_senha" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="password-requirements" style="grid-column: span 2; background: #f0f9ff; padding: 15px; border-radius: 12px; font-size: 12px; color: #334155; display: grid; grid-template-columns: 1fr 1fr; border: 1px solid #e2e8f0;">
                    <div class="req"><i class="fa-solid fa-xmark"></i> Mínimo de 8 caracteres</div>
                    <div class="req"><i class="fa-solid fa-xmark"></i> Letra maiúscula (A-Z)</div>
                    <div class="req"><i class="fa-solid fa-xmark"></i> Letra minúscula (a-z)</div>
                    <div class="req"><i class="fa-solid fa-xmark"></i> Número (0-9)</div>
                    <div class="req" style="grid-column: span 2;"><i class="fa-solid fa-xmark"></i> Caractere especial (!@#$...)</div>
                </div>

                <div class="terms" style="grid-column: span 2; margin-top: 10px;">
                    <label style="font-size: 13px; color: #64748b; font-weight: 400;">
                        <input type="checkbox" name="termos" required> Eu aceito os <a href="#" style="color:#0ea5e9; text-decoration:none;">Termos de Uso</a> e a <a href="#" style="color:#0ea5e9; text-decoration:none;">Política de Privacidade</a>
                    </label>
                </div>

                <button type="submit" class="btn-entrar" style="grid-column: span 2; margin-top: 10px;">Criar Conta</button>
            </div>
        </form>

        <p class="footer-text" style="text-align:center; margin-top:20px;">Já tem uma conta? <a href="index.php" style="color:#0ea5e9; font-weight:600; text-decoration:none;">Fazer login</a></p>
    </div>

    <p class="copyright" style="margin-top:30px; font-size:11px; color:#94a3b8;">© 2026 MetaCash. Todos os direitos reservados.</p>

</body>
</html>