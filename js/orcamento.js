jQuery(function($) {
    $('#Cep').mask('00000-000');
    $('#Telefone, #WhatsApp').mask('(00) 00000-0000');
    
    $('#cpfcnpj').mask('000.000.000-00', {
        onKeyPress : function(cpfcnpj, e, field, options) {
            const masks = ['000.000.000-000', '00.000.000/0000-00'];
            const mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
            $('#cpfcnpj').mask(mask, options);
        }
    });

    function limparCampos() {
        $('#razaosocial, #Endereco, #Bairro, #Cep, #WhatsApp, #Contato, #Telefone, #Email, #Estado, #RG, #CCM').val('');
    }
    
    $('#cpfcnpj').on('blur', function() {
        let cpfcnpj = $(this).val().trim();
    
        if (cpfcnpj !== '') {
            $.post('php/funcoes.php', { 
                funcao: 'buscarClientes',
                filtro: "cpfcnpj = '" + cpfcnpj + "'"
            }, function(response) {
                if (response) {
                    let data = JSON.parse(response);
                    
                    if (data.length > 0) {
                        if (confirm('Já existe um cliente "' + data[0].RazaoSocial + '" cadastrado com esses dados. Deseja preencher os campos com os dados desse cliente?')) {
                            $('#razaosocial').val(data[0].RazaoSocial);
                            $('#Endereco').val(data[0].Endereco);
                            $('#Bairro').val(data[0].Bairro);
                            $('#Cep').val(data[0].Cep);
                            $('#Contato').val(data[0].Contato);
                            $('#Email').val(data[0].Email);
                            $('#Telefone').val(data[0].Telefone);
                            $('#WhatsApp').val(data[0].WhatsApp);                            
                        } else {
                            limparCampos();
                        }
                    } else {
                        limparCampos();
                    }
                }
            }).fail(function(xhr, status, error) {
                console.error('Erro ao buscar dados: ' + status);
            });
        } else {
            limparCampos();
        }
    });   

    function tornarReadOnly(elementId, readOnly) {
        let element = document.getElementById(elementId);
        if (element) {
            element.readOnly = readOnly;
        }
    }
    
    tornarReadOnly('meuInput', true); 
    tornarReadOnly('meuInput', false);
});


document.addEventListener('DOMContentLoaded', function() {
    let cpfcnpjInput = document.querySelector('#cpfcnpj');
    let inputs = document.querySelectorAll('input:not(#cpfcnpj)');

    cpfcnpjInput.addEventListener('blur', function() {
        if (this.value.length >= 14) {
            inputs.forEach(function(input) {
                if (input.classList.contains('readonly')) {
                    input.removeAttribute('readOnly');
                }
            });
        } else {
            inputs.forEach(function(input) {
                if (input.classList.contains('readonly')) {
                    input.setAttribute('readOnly', 'true');
                }
            });
        }
    });  

});

function calcularResultado(productId) {
    let valor = parseFloat($("#valor_" + productId).text().replace('R$ ', '').replace(',', '.')) || 0;
    let quantidade = parseInt($("#divQuantidade_" + productId).val()) || 0;
    let desconto = parseFloat($("#divdesconto_" + productId).val()) || 0;

    desconto = Math.max(0, desconto);

    let resultado = (valor * quantidade) - (desconto * quantidade);

    $("#result_" + productId).text("R$ " + resultado.toFixed(2));
}

function calcularTotal() {
    let total = 0;

    $(".div-input input[type='number']").each(function() {
        let productId = this.id.replace("divQuantidade_", "");
        let quantidade = parseInt($(this).val()) || 0;
        let valorUnitario = parseFloat($("#valor_" + productId).text().replace('R$ ', '').replace(',', '.')) || 0;
        let desconto = parseFloat($("#divdesconto_" + productId).val()) || 0;

        desconto = Math.max(0, desconto);

        if (!isNaN(quantidade)) {
            total += (quantidade * valorUnitario) - (desconto * quantidade);
        }
    });

    let frete = parseFloat($("#frete").val()) || 0;
    total += frete;

    $("#total_valor").text("R$ " + total.toFixed(2));
}

function checkStock(productId) {
    let quantityInput = document.getElementById('divQuantidade_' + productId);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/check_stock.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                let quantity = parseFloat(quantityInput.value);
                if (quantity > response.stock) {
                    if (response.stock === null || response.stock === "" || response.stock === undefined) {
                        response.stock = 0;
                        text = `<p>Conferir na aba Estoque!</p>
                                <p>Quantidade disponível: ${response.stock} ou sem valor na aba "Estoque"</p>`;
                    } else {
                        text = `<p>Quantidade em estoque insuficiente!</p>
                                <p>Quantidade disponível: ${response.stock}</p>`;
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Estoque Insuficiente',
                        html: text,
                        confirmButtonText: 'OK'
                    });
                    quantityInput.value = parseInt(response.stock); 
                }                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: response.message,
                    confirmButtonText: 'OK'
                });
            }
            calcularResultado(productId);
            calcularTotal();
        }
    };
    xhr.send('idproduto=' + productId + '&quantidade=' + quantityInput.value);
}

$(document).ready(function() {
    $(".div-input input[type='number'], .div-input input[type='text'], #frete").on("input", function() {
        let productId = $(this).attr("id").replace("divQuantidade_", "").replace("divdesconto_", "");
        calcularResultado(productId);
        calcularTotal();
    });
});