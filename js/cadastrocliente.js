jQuery(function($) {
    $('#Cep').mask('00000-000');
    $('#Telefone, #WhatsApp').mask('(00) 00000-0000');
    
    $('#CpfCnpj').mask('000.000.000-00', {
        onKeyPress : function(CpfCnpj, e, field, options) {
            const masks = ['000.000.000-000', '00.000.000/0000-00'];
            const mask = (CpfCnpj.length > 14) ? masks[1] : masks[0];
            $('#CpfCnpj').mask(mask, options);
        }
    });

    $('#CpfCnpj, #RazaoSocial').on('blur', function() {
        let campo = $(this).attr('id');
        let valor = $(this).val().trim();

        if (valor === '') {
            return;
        }

        $.post('php/funcoes.php', {
            funcao: 'ClienteRepetido',
            campo: campo,
            valor: valor
        }, function(response) {
            if (response === 'existe') {
                alert('CPF/CNPJ ou Razão Social já cadastrados.');
                $('#' + campo).val('');
            }
        });
    });

    $(document).on('click', '.tabelaCliente button[id^="buttonExcluir_"]', function() {
        let idCliente = $(this).attr('id').split('_')[1];
        if (confirm('Tem certeza que deseja excluir este cliente?')) {
            $.post('php/funcoes.php', {
                action: 'atualizar_tabela',
                funcao: 'DeletaCliente',
                idCliente: idCliente
            }, function(response) {
                $('.tabelaCliente tbody').html(response);
            }).fail(function(xhr, status, error) {
                console.error(error);
                alert('Erro ao excluir o cliente. Por favor, tente novamente.');
            });
        }
    });

    $('#clientesubmit, #clienteatualizar').on('click', function() {
        let isSubmit = $(this).attr('id') === 'clientesubmit';
        if (VerificaRequired(isSubmit)) {
            let idCliente = isSubmit ? '' : $('#IDCliente').val();
            let data = {
                action: 'atualizar_tabela',
                funcao: isSubmit ? 'Cliente' : 'AtualizarCliente',
                idCliente: idCliente,
                Endereco: $('#Endereco').val(),
                Bairro: $('#Bairro').val(),
                RazaoSocial: $('#RazaoSocial').val(),
                Cep: $('#Cep').val(),
                CpfCnpj: $('#CpfCnpj').val(),
                Telefone: $('#Telefone').val(),
                WhatsApp: $('#WhatsApp').val(),
                Contato: $('#Contato').val(),
                email: $('#Email').val()
            };

            let confirmMessage = isSubmit ? 'Deseja salvar esse Cliente?' : 'Deseja Atualizar esse Cliente?';
            if (confirm(confirmMessage)) {
                $.post('php/funcoes.php', data, function(response) {
                    $('.tabelaCliente tbody').html(response);
                    if (!isSubmit) {
                        $('#IDCliente').val('');
                        $('#clienteatualizar').hide();
                        $('#clientesubmit').show();    
                    }
                    $('input[type="text"]').val('');
                    $('#Email').val(''); 
                }).fail(function(xhr, status, error) {
                    console.error(error);
                    alert('Erro ao salvar no banco de dados.');
                });
            }
        } else {
           window.alert(VerificaRequired(isSubmit));
        }
    });

    $(document).on('click', '[id^="buttonCancelarEdicao"]', function() {
        $('input[type="text"]').val('');
        $('#Email').val(''); 
        $('#clienteatualizar').hide();
        $('#clientesubmit').show(); 
    });

    $(document).on('click', '[id^="buttonEditar_"]', function() {
        $('#clienteatualizar').show();
        $('#clientesubmit').hide();
    
        let tr = $(this).closest('tr');
        let idCliente = $(this).attr('id').split('_')[1];
        $('#IDCliente').val(idCliente);
        $('#RazaoSocial').val(tr.find('td:nth-child(1)').text());
        $('#Endereco').val(tr.find('td:nth-child(2)').text());
        $('#Bairro').val(tr.find('td:nth-child(3)').text());
        $('#Cep').val(tr.find('td:nth-child(4)').text());
        $('#CpfCnpj').val(tr.find('td:nth-child(5)').text());
        $('#Telefone').val(tr.find('td:nth-child(6)').text());
        $('#WhatsApp').val(tr.find('td:nth-child(7)').text());
        $('#Email').val(tr.find('td:nth-child(9)').text());
        $('#Contato').val(tr.find('td:nth-child(8)').text());
    });
});

function VerificaRequired(isSubmit) {
    let camposObrigatorios = [];
    $('input[required]').each(function() {
        if (!$(this).val().trim()) {
            camposObrigatorios.push($("label[for='" + this.id + "']").text().replace(" (*)", ""));
            $(this).css('border-color', 'red');
        }
    });

    return camposObrigatorios.length === 0 ? true : (isSubmit ? "Campos obrigatórios não preenchidos: " + camposObrigatorios.join(', ') : false);
}

document.addEventListener('DOMContentLoaded', function() {
    let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));

    popoverTriggerList.forEach(function (popoverTriggerEl) {
        let popover = new bootstrap.Popover(popoverTriggerEl);

        popoverTriggerEl.addEventListener('mouseenter', function () {
            popover.show();
        });

        popoverTriggerEl.addEventListener('mouseleave', function () {
            popover.hide();
        });
    });
});


