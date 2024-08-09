<?php
require_once 'funcoes.php';

$response = ['success' => false, 'message' => 'Unknown error occurred'];

try {
    $pdo = conect(); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idorcamento = $_POST['idorcamento'];
        $pdo->beginTransaction();       
        $query = "SELECT o.STATUS, p.idproduto, p.quantidade, p.imagem
                    FROM orcamentohenr o
                    JOIN produtosorcamentohenr p ON o.idorcamento = p.idorcamento
                    WHERE o.idorcamento = :idorcamento";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idorcamento', $idorcamento, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach ($result as $row) {
                $idproduto = $row['idproduto'];
                $quantidade = $row['quantidade'];
                $nova_imagem = $row['imagem']; 
                $imagemurl = str_replace("http://localhost/henrimack sistsolut", "/henrimack sistsolut", $nova_imagem);
                $imagem = urldecode($imagemurl);

                $quantidadeAtualQuery = "SELECT Nfora FROM estoquehenr WHERE imagem = :imagem";
                $quantidadeAtualStmt = $pdo->prepare($quantidadeAtualQuery);
                $quantidadeAtualStmt->bindParam(':imagem', $imagem, PDO::PARAM_STR); 
                $quantidadeAtualStmt->execute();
                $quantidadeAtualResult = $quantidadeAtualStmt->fetch(PDO::FETCH_ASSOC);
                                
                if ($quantidadeAtualResult) {
                    $quantidadeAtual = $quantidadeAtualResult['Nfora'];
                    $novaQuantidadeint = intval($quantidadeAtual) - intval($quantidade);
                    $novaQuantidade = strval($novaQuantidadeint);
                } else {
                    $novaQuantidade = $quantidadeAtual; 
                }
                $updateEstoqueQuery = "UPDATE estoquehenr SET Nfora = :novaQuantidade WHERE imagem = :imagem";
                $updateEstoqueStmt = $pdo->prepare($updateEstoqueQuery);
                $updateEstoqueStmt->bindParam(':novaQuantidade', $novaQuantidade, PDO::PARAM_STR);
                $updateEstoqueStmt->bindParam(':imagem', $imagem, PDO::PARAM_STR);
                $updateEstoqueStmt->execute();

                $updateQuery = "UPDATE orcamentohenr SET STATUS = 'Finalizado' WHERE idorcamento = :idorcamento";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':idorcamento', $idorcamento, PDO::PARAM_INT);
                $updateStmt->execute();
            }
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Nenhum Produto foi cadastrado para esse Orçamento!'];
        }
        $pdo->commit();
    } else {
        $response = ['success' => false, 'message' => 'Método de requisição inválido.'];
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response = ['success' => false, 'message' => $e->getMessage()];
}
header('Content-Type: application/json');
echo json_encode($response);
?>


