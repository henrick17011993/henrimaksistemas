<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HenrimackSistema</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
    <img src="uploads/henrimack.png" alt="Descrição da Imagem" width="200" height="200">
        <form action="login.php" method="post">
            <label for="username">Login</label>
            <input type="text" id="username" name="username" placeholder="Insira seu Login" required>
            
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" placeholder="Insira sua Senha" required>
            
            <button type="submit">Entrar</button>
        </form>
        <div class="error" id="error"></div>
        <a href="register.php">Registrar</a>
        <a href="forgot_password.php">Esqueceu sua senha?</a>
    </div>
    <script src="js/login.js"></script>
</body>
</html>
