document.addEventListener('DOMContentLoaded', function() {
    const imagemInput = document.getElementById('imagemInput');
    const tipoInput = document.getElementById('Tipo');
    const modelosInput = document.getElementById('Modelos');
    const corInput = document.getElementById('Cor');
    const valorInput = document.getElementById('Valor');
    const cadastroEditarButton = document.getElementById('CadastroEditar');
    const uploadButton = document.getElementById('uploadButton');
    const imagemExibicao = document.getElementById('imagemExibicao');

    // Função para enviar dados via AJAX
    function updateField(fieldName, fieldValue) {
        $.ajax({
            url: 'php/produto.php',
            type: 'POST',
            data: {
                field: fieldName,
                value: fieldValue,
                id: $('#IDCliente').val()
            },
            success: function(response) {
                console.log(response); // Exibe a resposta do servidor no console
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown); // Exibe o erro no console
            }
        });
    }

    // Adiciona eventos somente se os elementos existirem
    if (cadastroEditarButton) {
        cadastroEditarButton.addEventListener('click', function() {
            document.querySelectorAll('input[readonly], select[disabled]').forEach(function(element) {
                element.removeAttribute('readonly');
                element.removeAttribute('disabled');
                element.classList.remove('disabled-select');
            });
            cadastroEditarButton.style.display = 'none';
        });
    }

    if (modelosInput) {
        $('#Modelos').on('change', function() {
            var fieldName = $(this).attr('name');
            var fieldValue = $(this).val();
            updateField(fieldName, fieldValue);
        });
    }

    if (corInput) {
        $('#Cor').on('change', function() {
            var fieldName = $(this).attr('name');
            var fieldValue = $(this).val();
            updateField(fieldName, fieldValue);
        });
    }

    if (valorInput) {
        $('#Valor').on('change', function() {
            var fieldName = $(this).attr('name');
            var fieldValue = $(this).val();
            updateField(fieldName, fieldValue);
        });
    }

    if (tipoInput) {
        $('#Tipo').on('change', function() {
            var fieldName = $(this).attr('name');
            var fieldValue = $(this).val();
            updateField(fieldName, fieldValue);
        });
    }

    if (imagemInput) {
        imagemInput.addEventListener('change', function() {
            const file = imagemInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (imagemExibicao) {
                        imagemExibicao.src = e.target.result;
                        uploadButton.textContent = 'Imagem Selecionada';
                    }
                }
                reader.readAsDataURL(file);
            } else {
                if (uploadButton) {
                    uploadButton.textContent = 'Upload Imagem';
                }
            }
        });
    }

    if (uploadButton && imagemInput) {
        uploadButton.addEventListener('click', function() {
            imagemInput.click();
        });
    }

    if (cadastroEditarButton && uploadButton && imagemInput) {
        cadastroEditarButton.addEventListener('click', function() {
            imagemInput.removeAttribute('readonly');
            imagemInput.removeAttribute('disabled');
            uploadButton.removeAttribute('disabled');
        });
    }

    // Verificação adicional para garantir que todos os elementos necessários estão presentes
    if (!imagemInput || !tipoInput || !modelosInput || !corInput || !valorInput || !cadastroEditarButton || !uploadButton || !imagemExibicao) {
        console.error("Um dos elementos necessários não foi encontrado no DOM.");
    }
    $('#imagemInput').on('change', function() {
        var formData = new FormData();
        formData.append('field', 'imagem');
        formData.append('id', $('#IDCliente').val());
        formData.append('file', $(this)[0].files[0]);

        $.ajax({
            url: 'php/produto.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                // Atualiza a imagem de exibição com a nova imagem enviada
                $('#imagemExibicao').attr('src', response.newImagePath);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
            }
        });
    });
});



