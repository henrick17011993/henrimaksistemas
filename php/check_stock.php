<?php
require_once "funcoes.php"; // Certifique-se de que este arquivo contém a função de conexão com o banco de dados

header('Content-Type: application/json');

$conn = conect(); // Presume-se que a função conect() esteja definida em funcoes.php e retorne uma conexão válida com o banco de dados

$idProduto = $_POST['idproduto'];
$quantidade = $_POST['quantidade'];

// Sanitiza o valor do ID do produto para garantir que seja um número inteiro
$idProduto = (int) $idProduto;

try {
    // Prepara a consulta para verificar o estoque
    $sql = "SELECT Nestoque - Nfora AS estoque FROM estoquehenr WHERE idproduto = :idproduto";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idproduto', $idProduto, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $estoque = $row['estoque'];
        echo json_encode(['success' => true, 'stock' => $estoque]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Produto não encontrado.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a consulta: ' . $e->getMessage()]);
}

// Fecha a conexão com o banco de dados
$conn = null;
?>
