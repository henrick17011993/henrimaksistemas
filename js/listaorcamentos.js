
$(document).ready(function() {
    $('#searchInputCliente').on('keyup', function() {
        let searchText = $(this).val().toLowerCase();
        $('.orcamentoslista tbody tr').each(function() {
            let rowData = $(this).text().toLowerCase();
            if (rowData.indexOf(searchText) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
    $('#searchInput').on('keyup', function() {
        let searchText = $(this).val().toLowerCase();
        $('.tabela tbody tr').each(function() {
            let rowData = $(this).text().toLowerCase();
            if (rowData.indexOf(searchText) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
});

$(document).ready(function() {
    $(".openModalBtn").click(function() {
        var idorcamento = $(this).data('id');
        $.ajax({
            url: 'php/getprodutosmodalorca.php', 
            type: 'GET',
            data: { idorcamento: idorcamento },
            success: function(response) {
                $("#myModal .show").html(response);
                $("#myModal").show();
            },
            error: function() {
                alert('Erro ao carregar os produtos.');
            }
        });
    });

    $(".closeBtn").click(function() {
        $("#myModal").hide();
    });

    $(window).click(function(event) {
        if ($(event.target).is("#myModal")) {
            $("#myModal").hide();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
tippy('.openModalBtn', {
        content: 'Produtos listado no Orçamento', 
        placement: 'left', 
        theme: 'light',  
        maxWidth: '200px', 
    });
});

document.addEventListener('DOMContentLoaded', function() {
tippy('.CancelStatus', {
        content: 'Finalizar Orçamento', 
        placement: 'left', 
        theme: 'light',  
        maxWidth: '200px', 
    });
});

document.addEventListener('DOMContentLoaded', function() {
tippy('.NextStatus', {
        content: 'Colocar "Em Andamento"', 
        placement: 'left', 
        theme: 'light',  
        maxWidth: '200px', 
    });
});

document.addEventListener('DOMContentLoaded', function() {
tippy('.ConcluirStatus', {
        content: 'Colocar Status "Concluido"', 
        placement: 'left', 
        theme: 'light',  
        maxWidth: '200px', 
    });
});

$(document).ready(function() {
    $('.NextStatus').on('click', function() {
        var idorcamento = $(this).data('id');
        
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Você quer atualizar o status para 'Em Andamento'?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, atualizar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'php/update_status.php',
                    type: 'POST',
                    data: { idorcamento: idorcamento },
                    success: function(response) {
                        if(response.success) {
                            var row = $('button[data-id="' + idorcamento + '"]').closest('tr');
                            row.find('.status').text('Em Andamento');
                            row.css('background-color', '#15d70670');
                            row.css('border', '5px solid #15d706b0');
                            row.find('.NextStatus').remove();
                            row.find('.CancelStatus').remove();
                            var actionCell = row.find('td:last');
                            actionCell.append("<button class='ConcluirStatus' data-id='" + idorcamento + "'>✓</button>");
                            
                            Swal.fire('Sucesso!', 'Status atualizado para "Em Andamento".', 'success');
                        } else {
                            Swal.fire('Erro', 'Erro ao atualizar o status: ' + response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Erro', 'Erro ao atualizar o status: ' + error, 'error');
                    }
                });
            }
        });
    });
});


$(document).ready(function() {
    $(document).on('click', '.ConcluirStatus', function() {
        var idorcamento = $(this).data('id');
        
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Você quer atualizar o status para 'Finalizado'?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, finalizar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'php/subtraindo_estoque.php',
                    type: 'POST',
                    data: { idorcamento: idorcamento },
                    success: function(response) {
                        if(response.success) {
                            var row = $('button[data-id="' + idorcamento + '"]').closest('tr');
                            row.find('.status').text('Finalizado');
                            row.css('background-color', '#ff0a0a54');
                            row.css('border', '5px solid #f70000b8');
                            row.find('.ConcluirStatus').remove();
                            
                            Swal.fire('Sucesso!', 'Status atualizado para "Finalizado".', 'success');
                        } else {
                            Swal.fire('Erro', 'Erro ao atualizar o status: ' + response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Erro', 'Erro ao atualizar o status: ' + error, 'error');
                    }
                });
            }
        });
    });
});

$(document).ready(function() {
    $(document).on('click', '.CancelStatus', function() {
        var idorcamento = $(this).data('id');
        
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Você quer atualizar o status para 'Cancelado'?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, cancelar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'php/cancel_estoque.php',
                    type: 'POST',
                    data: { idorcamento: idorcamento },
                    success: function(response) {
                        if(response.success) {
                            var row = $('button[data-id="' + idorcamento + '"]').closest('tr');
                            row.find('.status').text('Cancelado');
                            row.css({
                                'background-color': '',
                                'border': ''
                            });
                            row.find('.NextStatus').remove();
                            row.find('.CancelStatus').remove();
                            row.find('.ConcluirStatus').remove();
                            
                            Swal.fire('Sucesso!', 'Status atualizado para "Cancelado".', 'success');
                        } else {
                            Swal.fire('Erro', 'Erro ao atualizar o status: ' + response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Erro', 'Erro ao atualizar o status: ' + error, 'error');
                    }
                });
            }
        });
    });
});




