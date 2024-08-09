<?php
require 'funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $field = $_POST['field'];

    if ($field === 'imagem') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'C:\\xampp\\htdocs\\henrimack sistsolut\\uploads\\';
            $uploadFile = $uploadDir . basename($_FILES['file']['name']);

            // Verifica se o diretório de upload existe, senão cria
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    error_log("Erro ao criar o diretório de upload.");
                    http_response_code(500);
                    echo json_encode(['error' => 'Falha ao criar o diretório de upload']);
                    exit;
                }
            }

            // Move o arquivo para o diretório de upload
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                error_log("Upload bem-sucedido: " . $uploadFile);
                $filePath = 'uploads/' . basename($_FILES['file']['name']); // Caminho relativo para salvar no banco de dados
                if (AtualizaCampo($id, 'IMAGEM', $filePath)) {
                    echo json_encode(['newImagePath' => $filePath]);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Falha ao atualizar o caminho da imagem no banco de dados']);
                }
            } else {
                error_log("Falha no upload: " . $_FILES['file']['error']);
                http_response_code(500);
                echo json_encode(['error' => 'Falha no upload da imagem']);
            }
        } else {
            error_log("Nenhum arquivo ou erro no upload: " . $_FILES['file']['error']);
            http_response_code(400);
            echo json_encode(['error' => 'Nenhum arquivo enviado ou erro no upload']);
        }
    } else {
        $value = $_POST['value'];
        if (AtualizaCampo($id, strtoupper($field), $value)) {
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Falha ao atualizar o campo no banco de dados']);
        }
    }
}
?>


