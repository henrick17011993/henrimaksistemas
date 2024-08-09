<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="uploads/henrimack.png">
    <title>HenrimackSistema</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <link rel="stylesheet" type="text/css" href="css/logininput.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="./js/main.js"></script>
</head>
<body>
    <?php 
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: dashboard.php");
        exit;
    }
        require "php/funcoes.php";
        $arrayResultados = show();
    ?>        
    <header>
        <?php include 'php/header.php'; ?>
    </header>

    <main>        
        <div class ="show">
            <div style="display: flex; justify-content: center !important;" class="card-head">
                <h3>Bem vindo <?php echo htmlspecialchars($_SESSION['username']); ?> á Henrimack Sistema</h3>
            </div>
        </div>
        
    </main>
    
    <footer>
        <span>&copy; Feito por Henrique</span>           

    </footer>
    
</body>
</html>