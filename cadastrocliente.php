<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="uploads/henrimack.png">
        <title>HenrimackSistema</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/cadastrocliente.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-masker/1.1.0/vanilla-masker.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" crossorigin="anonymous"></script>
        <script src="./js/main.js"></script>
        <script src="./js/cadastrocliente.js"></script>
    </head>
<body>
    <?php 
        require "php/funcoes.php";
        $arrayResultados = show();
        $clientes = buscarClientes();
    ?> 
           
    <header>
        <?php include 'php/header.php'; ?>
    </header>
    
    <main>
        <div class="orcamento"style="margin: 2px 0px 53px 0px;">
            <div class="card-head"style=" padding-right: 2000px;">
                <h3>Dados Cadastrais da Empresa</h3>
            </div>
            <div class="div-input" id="divCpfCnpj">
                <label for="CpfCnpj">CPF/CNPJ</label>
                <input type="text" value="" name="CpfCnpj" id="CpfCnpj" required="true" style="width: 200px; padding: 5px; box-sizing: border-box;">
            </div>            
            <div class="div-input" id="divNome">
                <label for="RazaoSocial">Razão Social</label>
                <input type="text" value="" name="RazaoSocial" id="RazaoSocial" required="true" style="width: 200px; padding: 5px; box-sizing: border-box;">
            </div>
            <div class="div-input" id="divEndereco">
                <label for="Endereco">Endereço</label>
                <input type="text" value="" name="Endereco" id="Endereco" style="width: 200px; padding: 5px; box-sizing: border-box;">
            </div>            
            <div class="div-input" id="divBairro">
                <label for="Bairro">Bairro</label>
                <input type="text" value="" name="Bairro" id="Bairro" style="width: 200px; padding: 5px; box-sizing: border-box;">
            </div>
            <div class="div-input" id="divCep">
                <label for="Cep">Cep</label>
                <input type="text" value="" name="Cep" id="Cep" placeholder="Digite o CEP" style="width: 200px; padding: 5px; box-sizing: border-box;">
            </div>
            <div class="div-input" id="divContato">
                <label for="Contato">Contato</label>
                <input type="text" value="" name="Contato" id="Contato" style="width: 200px; padding: 5px; box-sizing: border-box;">
            </div>
            <div class="div-input" id="divTelefone">
                <label for="Telefone">Telefone</label>
                <input type="text" value="" name="Telefone" id="Telefone" style="width: 200px; padding: 5px; box-sizing: border-box;">
            </div>
            <div class="div-input" id="divWhatsApp">
                <label for="WhatsApp">WhatsApp</label>
                <input type="text" value="" name="WhatsApp" id="WhatsApp" required="true" style="width: 200px; padding: 5px; box-sizing: border-box;">
            </div>
            <div class="div-input" id="divEmail">
                <label for="Email">Email</label>
                <input type="email" value="" name="Email" required="true" placeholder="Digite seu e-mail" id="Email" style="width: 200px; padding: 5px; box-sizing: border-box;">
            </div>           
            <div class="div-input" style="display: none;">
                <label for="IDCliente">ID do Cliente:</label>
                <input type="text" id="IDCliente">
            </div>
            </tbody>
                </tr>
                    <tr>
                        <td colspan="0">
                            <button type="button" id="clientesubmit">Salvar Cliente</button>
                            <button id="clienteatualizar">Editar</button>
                        </td>
                    </tr>
            </tfoot>
        </div>       
        <div class="show">
            <div class="card-head">
                <h3>Dados Clientes</h3>
                <li><input type="text" id="searchInputCliente" placeholder="Pesquisar Clientes..."></li>
            </div>
                <table class="tabelaCliente">
                <thead>
                    <tr>
                        <th>Razão Social</th>
                        <th>Endereço</th>
                        <th>Bairro</th>
                        <th>CEP</th>
                        <th>CPF/CNPJ</th>
                        <th>Telefone</th>
                        <th>WhatsApp</th>
                        <th>Contato</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo $cliente['RazaoSocial']; ?></td>
                            <td><?php echo $cliente['Endereco']; ?></td>
                            <td><?php echo $cliente['Bairro']; ?></td>
                            <td><?php echo $cliente['Cep']; ?></td>
                            <td><?php echo $cliente['CpfCnpj']; ?></td>
                            <td><?php echo $cliente['Telefone']; ?></td>
                            <td><?php echo $cliente['WhatsApp']; ?></td>
                            <td><?php echo $cliente['Contato']; ?></td>
                            <td><?php echo $cliente['Email']; ?></td>
                            <td style="text-align: center;">
                                <div class="button-group">
                                    <a class="actions">
                                        <button id="buttonEditar_<?php echo $cliente['IDCliente']; ?>" class="btn btn-primary" data-bs-toggle="popover" title="Editar" data-bs-content="Clique para editar o cliente">🔍</button>
                                    </a>
                                    <a class="actions">
                                        <button id="buttonCancelarEdicao" class="btn btn-secondary" data-bs-toggle="popover" title="Cancelar a edição" data-bs-content="Clique para cancelar a edição">X</button>
                                    </a>
                                    <a class="actions">
                                        <button id="buttonExcluir_<?php echo $cliente['IDCliente']; ?>" class="btn btn-danger" data-bs-toggle="popover" title="Excluir" data-bs-content="Clique para excluir o cliente">🗑</button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    
    <footer>
        <span>&copy; Feito por Henrique</span>           
    </footer>
</body>
</html>