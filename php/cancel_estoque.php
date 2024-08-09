<?php
require_once 'funcoes.php';

$response = ['success' => false, 'message' => 'Unknown error occurred'];

try {
    $pdo = conect();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['idorcamento']) && !empty($_POST['idorcamento'])) {
            $idorcamento = $_POST['idorcamento'];
            $pdo->beginTransaction();

            $updateQuery = "UPDATE orcamentohenr SET STATUS = 'Cancelado' WHERE idorcamento = :idorcamento";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':idorcamento', $idorcamento, PDO::PARAM_INT);
            $updateStmt->execute();

            if ($updateStmt->rowCount() > 0) {
                $pdo->commit();
                $response = ['success' => true, 'message' => 'Orçamento cancelado com sucesso.'];
            } else {
                $pdo->rollBack();
                $response = ['success' => false, 'message' => 'Nenhum orçamento encontrado com esse ID.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'ID do orçamento não fornecido ou inválido.'];
        }
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
