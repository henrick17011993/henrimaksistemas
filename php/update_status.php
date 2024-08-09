<?php
require_once 'funcoes.php';

$response = ['success' => false, 'message' => 'Unknown error occurred'];

try {
    $pdo = conect(); // Utilize sua função de conexão

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idorcamento = $_POST['idorcamento'];

        // Inicia uma transação
        $pdo->beginTransaction();

        // Consulta para obter o status da tabela orcamento e os dados da tabela produtos
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
                $status = $row['STATUS'];
                $idproduto = $row['idproduto'];
                $quantidade = $row['quantidade'];
                $nova_imagem = $row['imagem']; 
                $imagemurl = str_replace("http://localhost/henrimack sistsolut", "/henrimack sistsolut", $nova_imagem);
                $imagem = urldecode($imagemurl);
                // Atualiza o status da tabela orcamento
                $updateQuery = "UPDATE orcamentohenr SET STATUS = 'Em Andamento' WHERE idorcamento = :idorcamento";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':idorcamento', $idorcamento, PDO::PARAM_INT);
                $updateStmt->execute();

                // Verifica se o produto já está cadastrado na tabela estoque e se a imagem é a mesma
                $checkQuery = "SELECT idestoque, imagem, Nfora FROM estoquehenr WHERE imagem = :imagem";
                $checkStmt = $pdo->prepare($checkQuery);
                $checkStmt->bindParam(':imagem', $imagem, PDO::PARAM_STR);
                $checkStmt->execute();
                $existingProduct = $checkStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($existingProduct) {
                    // Soma a quantidade existente com a nova quantidade
                    $novaQuantidade = $existingProduct['Nfora'] + $quantidade;
                
                    // Atualiza a tabela estoque com a nova quantidade
                    $updateEstoqueQuery = "UPDATE estoquehenr SET Nfora = :novaQuantidade WHERE imagem = :imagem";
                    $updateEstoqueStmt = $pdo->prepare($updateEstoqueQuery);
                    $updateEstoqueStmt->bindParam(':novaQuantidade', $novaQuantidade, PDO::PARAM_INT);
                    $updateEstoqueStmt->bindParam(':imagem', $imagem, PDO::PARAM_STR);
                    $updateEstoqueStmt->execute();
                } else {
                    // Insere um novo registro na tabela estoque
                    $insertEstoqueQuery = "INSERT INTO estoquehenr (idproduto, Nfora, imagem) VALUES (:idproduto, :quantidade, :imagem)";
                    $insertEstoqueStmt = $pdo->prepare($insertEstoqueQuery);
                    $insertEstoqueStmt->bindParam(':idproduto', $idproduto, PDO::PARAM_INT);
                    $insertEstoqueStmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
                    $insertEstoqueStmt->bindParam(':imagem', $imagem, PDO::PARAM_STR);
                    $insertEstoqueStmt->execute();
                }
                
                if (!$existingProduct && $imagem) {
                    // Exibe mensagem informando que existe um produto sem estoque
                    $response = ['success' => false, 'message' => 'Existe um produto sem estoque.'];
                }
            }

            // Comita a transação
            $pdo->commit();
            $response = ['success' => true, 'message' => 'Status atualizado com sucesso!'];
        } else {
            $response = ['success' => false, 'message' => 'Nenhum Produto foi cadastrado para esse Orçamento!'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Método de requisição inválido.'];
    }
} catch (Exception $e) {
    // Rollback a transação em caso de erro
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response = ['success' => false, 'message' => $e->getMessage()];
}

header('Content-Type: application/json');
echo json_encode($response);

?>

