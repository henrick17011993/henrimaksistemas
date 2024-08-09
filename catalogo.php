<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="uploads/henrimack.png">
    <title>HenrimackSistema</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-masker/1.1.0/vanilla-masker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light-border.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/material.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/translucent.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css">
    <script src="./js/main.js"></script>
    <script src="./js/catalogo.js"></script>
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
                <h3>Produtos Disponiveis</h3>
                <li><input type="text" id="searchInput" placeholder="Pesquisar produtos..."></li>
        </div>
        <div>
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
                                <th>Valor</th>
                                <th>Ações</th>
                                <?php if ($tipo === array_key_first($produtosAgrupados)): ?>
                                    <th>
                                        <label for="checkboxSemValores" class="tooltip-container">
                                            <input type="checkbox" id="checkboxSemValores" class="tooltip-trigger" />
                                            <div class="tooltip" data-tooltip="Meu Tooltip">Imprimir S/Valor</div>
                                        </label>
                                    </th>
                                    <th><a id="btnPDF">🖶</a></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos as $produto): ?>
                                <tr>
                                    <td>
                                        <div class="image-container">
                                            <img src="<?= htmlspecialchars($produto["IMAGEM"], ENT_QUOTES, 'UTF-8') ?>" alt="Imagem" onerror="this.onerror=null; this.src='/caminho/para/imagem-padrao.jpg'; this.src='uploads/henrimack.png';">
                                        </div>
                                    </td>
                                    <td><?= $produto["TIPO"] ?></td>
                                    <td><?= $produto["MODELOS"] ?></td>
                                    <td><?= $produto["COR"] ?></td>
                                    <td><?= $produto["VALOR"] ?></td>
                                    <td>
                                        <div class="actions">
                                            <a id="btnR_<?= $produto['ID'] ?>" href="produto.php?id=<?= $produto['ID'] ?>&acao=r" class="btn btn-primary" data-bs-toggle="popover" data-bs-content="Visualizar e Editar">🔍</a>
                                            <a id="Cadastro" href="produto.php?" class="btn btn-secondary" data-bs-toggle="popover" data-bs-content="Cadastrar Produtos">✎</a>
                                            <a id="btnD_<?= $produto['ID'] ?>" data-id="<?= $produto['ID'] ?>" class="btn btn-danger" data-bs-toggle="popover" data-bs-content="Excluir">🗑</a>
                                        </div>
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