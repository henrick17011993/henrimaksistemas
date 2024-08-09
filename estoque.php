<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="uploads/henrimack.png">
    <title>HenrimackSistema</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="./js/main.js"></script>
    <script src="./js/estoque.js"></script>
</head>
<body>
    <?php 
        require "php/funcoes.php";
        $arrayResultados = show();
    ?>        
    <header>
        <?php include 'php/header.php'; ?>
    </header>

        <main>
        <div class ="show">
            <div class="card-head">
                <h3>Estoque</h3>
                <li><input type="text" id="searchInput" placeholder="Pesquisar produtos..."></li>
        </div>
       
        <?php
            $produtosAgrupados = [];
            foreach ($arrayResultados as $produto) {
                $tipo = $produto["TIPO"];
                if (!isset($produtosAgrupados[$tipo])) {
                    $produtosAgrupados[$tipo] = [];
                }
                $produtosAgrupados[$tipo][] = $produto;
            }
            ?>

            <?php foreach ($produtosAgrupados as $tipo => $produtos): ?>
                <div class="tipo-produto">
                    <h2 style="text-align: center; background-color: white; width: 100%; color: #6a11cb; border-radius: 10px; padding: 5px;"><?= $tipo ?></h2>
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Tipo</th>
                                <th>Modelo</th>
                                <th>Cor</th>
                                <th>Estoque</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($produtos as $produto): ?>
                            <tr>
                                <td>
                                    <div class="image-container">
                                        <img src="<?= htmlspecialchars($produto["IMAGEM"], ENT_QUOTES, 'UTF-8') ?>" alt="Imagem">
                                    </div>
                                </td>
                                <td><?= $produto["TIPO"] ?></td>
                                <td><?= $produto["MODELOS"] ?></td>
                                <td><?= $produto["COR"] ?></td>
                                <td class="div-input" style="width: 80px;height: 0px;margin-left: 12px;margin-top: 27px;">
                                    <?php
                                    $estoque = getPuxarEstoque($produto['ID']);
                                    if ($estoque !== false) {
                                        echo '<input type="number" name="QuantidadeEstoque" id="divQuantidadeEstoque_' . $produto['ID'] . '" value="' . $estoque . '">';
                                    } else {
                                        echo '<input type="number" name="QuantidadeEstoque" id="divQuantidadeEstoque_' . $produto['ID'] . '" value=" ">';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>     
    </main>
    
    <footer>
        <span>&copy; Feito por Henrique</span>           

    </footer>
    
</body>
</html>

