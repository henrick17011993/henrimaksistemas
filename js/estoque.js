jQuery(function() {
    $('input[name="QuantidadeEstoque"]').blur(function(){
        let id = $(this).attr('id').split('_')[1]; 
        let quantidade = $(this).val();
        let imagemNome = $(this).closest('tr').find('img').attr('src');

        if(confirm("Deseja salvar a quantidade de estoque?")){
            $.ajax({
                type: "POST",
                url: "php/funcoes.php",
                data: { funcao: 'Estoque', id: id, quantidade: quantidade, imagem: imagemNome },
                success: function(response){
                    alert(response);
                },
                error: function(xhr, status, error){
                    alert("Erro ao salvar no banco de dados.");
                }
            });
        }
    });
});



