<img src="uploads/henrimack.png" alt="Descrição da Imagem" width="250" height="250">
<link rel="stylesheet" type="text/css" href="css/header.css" />

<nav>   
    <div class="user-info">
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $nomeUsuario = $_SESSION['username']; 
        $cargoUsuario = $_SESSION['usercargo']; 
        echo "<span class='user-name'>$nomeUsuario</span> | <span class='user-role'>$cargoUsuario</span>";
        ?>
    </div>
    <form action="logout.php" method="post" class="logout-form">
        <button type="submit" class="logout-btn">
            <span class="logout-icon">⎋</span> Logout
        </button>
    </form>    
    <ul>
        <li><a href="dashboard.php">Home</a></li>
            <li class="dropdown">
                <a href="#">Cadastros</a>
                <div class="dropdown-content">
                    <ul>
                        <li><a href="produto.php" class="modal-link">Cadastro de Produtos</a></li>
                        <li><a href="estoque.php" class="modal-link">Cadastro no Estoque</a></li>
                        <li><a href="cadastrocliente.php" class="modal-link">Cadastro de Cliente</a></li>
                    </ul>
                </div>
            <li class="dropdown">
                <a href="#">Tabelas/Listas</a>
                <div class="dropdown-content">
                    <ul>
                        <li><a href="listaorcamentos.php">Lista de Orçamentos</a></li>
                        <li><a href="orcamento.php">Orçamentos</a></li>
                        <li><a href="tabelaentrega.php">Tabela Entrega</a></li>
                        <li><a href="catalogo.php">Catálogo</a></li>
                        <li>
                        <!-- <form action="logout.php" method="post">
                            <button type="submit">Logout</button>
                        </form> -->
                    </li>
                    </ul>
                </div>
            </li>                   
        </li>
        <!-- <li><a href="contato.php">Contate-nos</a></li>
        <li><a href="sobrenos.php">Sobre Nós</a></li> -->
    </ul>
</nav>