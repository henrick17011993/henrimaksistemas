$(document).ready(function () {
    const imagemInput = $('#imagemInput');
    const imagemExibicao = $('#imagemExibicao');
    const previewImage = $('#previewImage');
    const tipoInput = $('#Tipo');
    const modelosInput = $('#Modelos');
    const corInput = $('#Cor');
    const valorInput = $('#Valor');
    var formData = new FormData();
  
   
    $("#btnButton").on("click", function() {
        if(VerificaRequired() == true){
            enviar();
        }else {
           window.alert(VerificaRequired())
        }
    });
   
    
   
    $("#orcamentoForm").on("click", function() {
        
        setTimeout(function() {
            const informacao = document.getElementById('total_valor').textContent;
            const informacaoCodificada = encodeURIComponent(informacao);
            var checkboxes = document.querySelectorAll('input[name="product_checkbox[]"]');
            var checkTrue = "nao"; 
    
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    checkTrue = "sim";
                    break; 
                }
            }
    
            if(VerificaRequired() == true){
                window.location.assign('orcamentopdf.php?info=' + informacaoCodificada + '&check=' + checkTrue);
                enviarDadosOrcamento();
            }else {
               window.alert(VerificaRequired())
            }        
        }, 1000);

    });
    

    $('[id^=btnPDF]').on('click', function () {
        const checkbox = document.getElementById("checkboxSemValores");
        const informacao = "NaoImprimirValor"; 
        const informacaoCodificada = encodeURIComponent(informacao);
    
        if (checkbox.checked) {
            window.location.href = 'imprimir.php?info=' + informacaoCodificada;
        } else {
            const informacao = "ImprimirValor";
            const informacaoCodificada = encodeURIComponent(informacao);

            window.location.href = 'imprimir.php?info=' + informacaoCodificada;
        }
    });   

    // $('#btnAtualizar').on('click', function () {
    //     const id = $(this).data('id');
    //     AtualizaDados(id);
    // });

    $('[id^=btnD_]').on('click', function () {
        if (confirm('tem certeza que deseja excluir esse produto? ')) {
            const id = $(this).data('id');
            deletaDados(id);
        }
    });

    $(document).on('change', '#imagemInput', exibirImagemInput);

    $('#Imagens').change(function () {
        const selectedFile = this.files[0];
        if (selectedFile) {
            const reader = new FileReader();
            reader.onload = function (event) {
                previewImage.prop('src', event.target.result).css('display', 'block');
            };
            reader.readAsDataURL(selectedFile);
        } else {
            previewImage.prop('src', '').css('display', 'none');
        }
    });

    $('#uploadButton').on('click', function () {
        $('#Imagens').click();
    });

    function VerificaRequired() {
        var obrigatorios = [];
        $('input[required]').each(function() {
            if ((this.value).trim() == '') {
                obrigatorios.push(this.id);
                $(this).css('border-color', 'red');
            }
        });
    
        var campos = "";
        if (obrigatorios.length > 0) {
            for (var i = 0; i < obrigatorios.length; i++) { 
                campos += ", " + $("label[for='"+obrigatorios[i]+"']").text().replace(" (*)", "");
            }
            return "Campos obrigatórios não preenchidos: " + campos.substring(2);
        } else {
            return true;
        }        
    }
    

    function exibirImagemInput() {
        const file = imagemInput.prop('files')[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagemExibicao.prop('src', e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            imagemExibicao.prop('src', '');
        }
    }

    function enviar() {
        const formData = new FormData();
        formData.append('funcao', 'Insere'); 
    
        formData.append('tipo', tipoInput.val());
        formData.append('modelos', modelosInput.val());
        formData.append('cor', corInput.val());
        formData.append('valor', valorInput.val());
        formData.append('imagem', imagemInput.prop('files')[0]);
    
        $.ajax({
            type: 'POST',
            url: 'php/funcoes.php?funcao=Insere',
            data: formData,
            contentType: false,
            processData: false,
            success: function (ret) {
                location.href = 'catalogo.php';
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Erro ao enviar a requisição. Consulte o console para mais detalhes.');
            }
        });
    }    

    function deletaDados(id) {
        $.ajax({
            type: 'POST',
            url: 'php/funcoes.php?funcao=Deleta',
            data: {
                'id':id,
                'funcao': 'Deleta'
            },
            success: function (ret) {
                if (!ret) {
                    $(`#btnD_${id}`).closest('tr').remove();
                } else {
                    alert(ret);
                }
            }
        });
    }

    // $(document).ready(function() {
    
    //     function calcularResultado(productId) {
    //         var valor = parseFloat($("#valor_" + productId).text().replace('R$ ', '').replace(',', '.')) || 0;
    //         var quantidade = parseInt($("#divQuantidade_" + productId).val()) || 0;
    //         var desconto = parseFloat($("#divdesconto_" + productId).val()) || 0;
    
    //         desconto = Math.max(0, desconto);
    
    //         var resultado = (valor * quantidade) - (desconto * quantidade);
    
    //         $("#result_" + productId).text("R$ " + resultado.toFixed(2));
    //     }
    
    //     function calcularTotal() {
    //         var total = 0;
    
    //         $(".div-input input[type='number']").each(function() {
    //             var productId = this.id.replace("divQuantidade_", "");
    //             var quantidade = parseInt($(this).val()) || 0;
    //             var valorUnitario = parseFloat($("#valor_" + productId).text().replace('R$ ', '').replace(',', '.')) || 0;
    //             var desconto = parseFloat($("#divdesconto_" + productId).val()) || 0;
    
    //             desconto = Math.max(0, desconto);
    
    //             if (!isNaN(quantidade)) {
    //                 total += (quantidade * valorUnitario) - (desconto * quantidade);
    //             }
    //         });
    
    //         var frete = parseFloat($("#frete").val()) || 0;
    //         total += frete;
    
    //         $("#total_valor").text("R$ " + total.toFixed(2));
    //     }
    
    //     $(".div-input input[type='number'], .div-input input[type='text'], #frete").on("input", function() {
    //         var productId = $(this).attr("id").replace("divQuantidade_", "").replace("divdesconto_", "");
    //         calcularResultado(productId);
    //         calcularTotal();
    //     });
        
    // });       
    
    function enviarDadosOrcamento() {
        var formData = new FormData();

        formData.append('razaoSocial', document.getElementById('razaosocial').value);
        formData.append('endereco', document.getElementById('Endereco').value);
        formData.append('bairro', document.getElementById('Bairro').value);
        formData.append('cep', document.getElementById('Cep').value);
        formData.append('WhatsApp', document.getElementById('WhatsApp').value);
        formData.append('estado', document.getElementById('Estado').value);
        formData.append('cpfcnpj', document.getElementById('cpfcnpj').value);
        formData.append('rg', document.getElementById('RG').value);
        formData.append('ccm', document.getElementById('CCM').value);
        formData.append('contato', document.getElementById('Contato').value);
        formData.append('telefone', document.getElementById('Telefone').value);
        formData.append('email', document.getElementById('Email').value);

        formData.append('nomeEvento', document.getElementById('Evento').value);
        formData.append('local', document.getElementById('Local').value);
        formData.append('montadora', document.getElementById('montadora').value);
        formData.append('stand', document.getElementById('stand').value);
        
        formData.append('dataEntrega', document.getElementById('Entrega').value);
        formData.append('dataDe', document.getElementById('Datade').value);
        formData.append('dataAte', document.getElementById('DataAte').value);

        var checkboxes = document.querySelectorAll('input[name="product_checkbox[]"]:checked');
        checkboxes.forEach(function(checkbox) {
            var id = checkbox.id.split('_')[2];
            var row = checkbox.closest('tr'); 

            if (row) {
                var valorComSimbolo = row.querySelector('td:nth-child(5)').innerText;
                var valorNumerico = valorComSimbolo.replace(/[^0-9,.]/g, '');

                formData.append('checkboxSelections[]', JSON.stringify({
                    id: id,
                    imagem: row.querySelector('.image-container img').src,
                    tipo: row.querySelector('td:nth-child(2)').innerText,
                    modelos: row.querySelector('td:nth-child(3)').innerText,
                    cor: row.querySelector('td:nth-child(4)').innerText,
                    valor: valorNumerico,
                    quantidade: row.querySelector('.div-input input').value,
                    total: row.querySelector('td:nth-child(7)').innerText
                }));
            }
        });


        formData.append('funcao', 'Orcamento');


        $.ajax({
            type: 'POST',
            url: 'php/funcoes.php?funcao=Orcamento',
            data: formData,
            contentType: false,
            processData: false,
            success: function (ret) {
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Erro ao enviar a requisição. Consulte o console para mais detalhes.');
            }
        });
    }

});

$(document).ready(function() {
    $('#searchInput').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        $('.tabela tbody tr').each(function() {
            var rowData = $(this).text().toLowerCase();
            if (rowData.indexOf(searchText) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
});

$(document).ready(function() {
    $('#searchInputCliente').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        $('.tabelaCliente tbody tr').each(function() {
            var rowData = $(this).text().toLowerCase();
            if (rowData.indexOf(searchText) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
    var botoesEditar = document.querySelectorAll("a[id^='btnU_']");

    botoesEditar.forEach(function(botao) {
        botao.addEventListener("click", function(event) {
            event.preventDefault();
            var idProduto = botao.getAttribute("href").split("=")[1];
            var exemploDadosProduto = {
                modelo: "Exemplo de Modelo",
                cor: "Exemplo de Cor",
            };
            document.getElementById("Modelos").value = exemploDadosProduto.modelo;
            document.getElementById("Cor").value = exemploDadosProduto.cor;
            document.getElementById("Valor").value = exemploDadosProduto.valor;
        });
    });
});




