<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="uploads/henrimack.png">
    <title>HenrimackSistema</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <link rel="stylesheet" type="text/css" href="css/listaorcamentos.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light-border.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/material.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/translucent.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css">
    <script src="./js/main.js"></script>
    <script src="./js/listaorcamentos.js"></script>
</head>
<body>
    <?php
        require "php/funcoes.php";
        require "php/listaorcamentosfunc.php";
    ?>
    <header>
        <?php include 'php/header.php'; ?>
    </header>
    <main>
        <div class="orcamentoslista">
            <div style="display: flex; justify-content: center !important;" class="card-head">
                <h3>Lista de Orçamentos Feito</h3>
            </div>
            <div style="align-self: end;" class="card-head">
                <li><input type="text" id="searchInputCliente" placeholder="Pesquisar Clientes..."></li>
            </div>
            <table>
                <thead>               
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Status</th>
                        <th>Razão Social</th>
                        <th>Montadora</th>
                        <th>Stand</th>
                        <th>CPF/CNPJ</th>
                        <th>Contato</th>
                        <th>Telefone</th>
                        <th>WhatsApp</th>
                        <th>Email</th>
                        <th>Evento</th>
                        <th>Local</th>
                        <th>Data de Entrega</th>
                        <th>Data de</th>
                        <th>Data até</th>
                        <th style="align-items: normal; display:flex;justify-content: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {

                                if ($row['STATUS'] === "Finalizado") {
                                    $stylecolor = "<tr style='background-color: #ff0a0a54; border: 5px solid #f70000b8'>";

                                } elseif ($row['STATUS'] === "Em Andamento") {
                                    $stylecolor = "<tr style='background-color: #15d70670; border: 5px solid #15d706b0'>";
                                    $buttonConcluirStatus = "<button class='ConcluirStatus' data-id='{$row['idorcamento']}'>✓</button>";

                                } elseif ($row['STATUS'] === "Ag/Confir") {
                                    $stylecolor = "<tr style='background-color: #d0dd0073; border: 5px solid #d0dd00c2'>";
                                    $buttonNextStatus = "<button class='NextStatus' data-id='{$row['idorcamento']}'>➦</button>";
                                    $buttonFinalizarStatus = "<button class='CancelStatus' data-id='{$row['idorcamento']}'>🗙</button>";

                                } else {
                                    $stylecolor = "<tr>";
                                }
                                echo "$stylecolor
                                    <td>{$row['idorcamento']}</td>
                                    <td>{$row['codigounico']}</td>
                                    <td class='status'>{$row['STATUS']}</td>
                                    <td>{$row['razaosocial']}</td>
                                    <td>{$row['Montadora']}</td>
                                    <td>{$row['Stand']}</td>
                                    <td>{$row['cpfcnpj']}</td>
                                    <td>{$row['contato']}</td>
                                    <td>{$row['telefone']}</td>
                                    <td>{$row['WhatsApp']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['nome_evento']}</td>
                                    <td>{$row['local']}</td>
                                    <td>{$row['data_entrega']}</td>
                                    <td>{$row['data_de']}</td>
                                    <td>{$row['data_ate']}</td>
                                    <td>                 
                                        <button class='openModalBtn' data-id='{$row['idorcamento']}'>≡</button>";
                                echo isset($buttonNextStatus) ? $buttonNextStatus : '';
                                echo isset($buttonConcluirStatus) ? $buttonConcluirStatus : '';
                                echo isset($buttonFinalizarStatus) ? $buttonFinalizarStatus : '';
                                echo "</td>
                                </tr>";
                                unset($buttonNextStatus);
                                unset($buttonConcluirStatus);
                                unset($buttonFinalizarStatus);
                            }
                        } else {
                            echo "<tr><td colspan='20'>No records found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="closeBtn">&times;</span>
            <main>
                <div class="show" style="width: 98%;"></div>
            </main>
        </div>
    </div>
    <footer>
        <span>&copy; Feito por Henrique</span>
    </footer>
</body>
</html>


