<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Senha</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Cadastro</h2>
        <form action="register_process.php" method="post">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            
            <button type="submit">Se Cadastrar</button>
        </form>
        <div class="error" id="error"></div>
        <a href="index.php">Voltar ao Login</a>
    </div>
</body>
</html>
