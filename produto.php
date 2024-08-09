<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="uploads/henrimack.png">
    <title>HenrimackSistema</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <link rel="stylesheet" type="text/css" href="css/produto.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="./js/main.js"></script>
    <script src="./js/produto.js"></script>
</head>
<body>
    <header>
        <?php include 'php/header.php'; ?>
        <?php
            include 'php/getprodutocadastro.php';

            if ($dadosTabela === false || $dadosTabela === " ") {
                $conn = conect();
                
                $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                $SQL = "SELECT * FROM produtoshenr WHERE ID = :id";
                $query = $conn->prepare($SQL);
                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->execute();

                $dadosTabela = $query->fetch(PDO::FETCH_ASSOC);
            }
        ?>
    </header>
    

    <main>
        <div class="centralizar">
            <div class="showw">
                <div class="card-head">
                    <h3>Cadastro</h3>
                    <a href="catalogo.php" id="btnVoltar">voltar</a>
                </div>
                <fieldset>
                    <div>
                        <label for="imagemInput"> </label>
                        <span id="linkDinamico"></span>
                    </div>

                    <div class="div-input" id="divImagem">
                        <label for="imagemInput"></label>
                        <img id="imagemExibicao" src="<?= $dadosTabela ? $dadosTabela["IMAGEM"] : ''; ?>" alt=" ">
                        <input type="file" <?= $acao === 'r' ? 'readonly' : ''; ?> id="imagemInput" accept="image/*" <?= $acao === 'r' ? 'disabled' : ''; ?> style="display: none;">
                        <button id="uploadButton" <?= $acao === 'r' ? 'disabled' : ''; ?>>Upload Imagem</button>
                    </div>

                    <div class="div-input" id="divCategoria">
                        <form style="padding: 0px 0px 0px 0px; display: block;">
                            <label for="Tipo">Tipo</label>
                            <select name="Tipo" id="Tipo" <?= $acao === 'r' ? 'disabled class="disabled-select"' : ''; ?> required>
                                <?php if (empty($dadosTabela["TIPO"])): ?>
                                    <option value="" disabled selected hidden>Escolha a Categoria</option>
                                <?php else: ?>
                                    <option value="<?= htmlspecialchars($dadosTabela["TIPO"]) ?>" selected><?= htmlspecialchars($dadosTabela["TIPO"]) ?></option>
                                <?php endif; ?>
                                <?php include 'php/optionstipo.php'; ?>
                            </select>
                        </form>
                    </div>

                    <div class="div-input" id="divNome">
                        <label for="Modelos">Modelo</label>
                        <input type="text" value="<?= $dadosTabela ? $dadosTabela["MODELOS"] : ''; ?>" <?= $acao === 'r' ? 'readonly' : ''; ?> name="Modelos" id="Modelos" required="True">
                    </div>

                    <div class="div-input" id="divCor">
                        <label for="Cor">Cor</label>
                        <input type="text" value="<?= $dadosTabela ? $dadosTabela["COR"] : ''; ?>" <?= $acao === 'r' ? 'readonly' : ''; ?> name="Cor" id="Cor" required="True">
                    </div>
                    <div class="div-input" id="divValor">
                        <label for="Valor">Valor</label>
                        <input type="number" value="<?= $dadosTabela ? $dadosTabela["VALOR"] : ''; ?>" <?= $acao === 'r' ? 'readonly' : ''; ?> name="Valor" id="Valor" required="True">
                    </div>
                    <div class="div-input" style="display: none;">
                        <label for="IDCliente">ID do Cliente:</label>
                        <input type="text" value="<?= $dadosTabela["ID"] ?>" id="IDCliente">
                    </div>
                </div>
                    <?php if ($acao === 'r'): ?>
                            <button id="CadastroEditar">Editar</button>
                    <?php else: ?>
                            <button id="btnButton" type="submit">Enviar</button>
                    <?php endif; ?>
                </fieldset>
            </div>
        </div>
    </main>
    <footer>
        <span>&copy; Feito por Henrique</span>
    </footer>
</body>
</html>

