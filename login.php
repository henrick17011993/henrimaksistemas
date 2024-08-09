<?php
require_once 'php/funcoes.php';

$response = ['success' => false, 'message' => 'Unknown error occurred'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username =strval($_POST['username']);
    $password = strval($_POST['password']);

    try {
        $pdo = conect();
        $query = "SELECT * FROM usershenr WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['usercargo'] = $user['usercargo']; 
                echo "Login bem-sucedido!";
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Senha incorreta!";
                $response = ['success' => false, 'message' => 'Invalido a Senha ou Usuário'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Usuário não encontrado!'];
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
};
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
    <img src="uploads/henrimack.png" alt="Descrição da Imagem" width="250" height="250">
        <?php if (!$response['success'] && !empty($response['message'])): ?>
            <div class="error"><?= htmlspecialchars($response['message']) ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username">Login</label>
            <input type="text" id="username" name="username" placeholder="Insira seu Login" required>
            
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" placeholder="Insira sua Senha" required>
            
            <button type="submit">Entrar</button>
        </form>
        <a href="register.php">Registrar</a> 
        <a href="forgot_password.php">Esqueceu sua senha?</a>
    </div>
</body>
</html>
