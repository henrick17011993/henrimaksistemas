<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Esqueceu a Senha?</h2>
        <form action="forgot_password_process.php" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            
            <button type="submit">entrar</button>
        </form>
        <div class="error" id="error"></div>
        <a href="index.php">Voltar no Login</a>
    </div>
</body>
</html>
