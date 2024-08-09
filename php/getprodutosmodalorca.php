<?php
require_once 'funcoes.php';

function produto($idorcamento) {
    $conn = conect();

    $SQL = "SELECT * 
            FROM produtosorcamentohenr
            WHERE idorcamento = :idorcamento
            ORDER BY 
                CASE 
                    WHEN TIPO = 'Banquetas' THEN 1
                    WHEN TIPO = 'Sofá/Poltronas' THEN 2
                    WHEN TIPO = 'Bistrô' THEN 3
                    WHEN TIPO = 'Cadeiras' THEN 4
                    WHEN TIPO = 'Mesareunião' THEN 5
                    WHEN TIPO = 'Mesacanto' THEN 6
                    WHEN TIPO = 'Mesacentro' THEN 7
                    WHEN TIPO = 'Eletro/Acessórios' THEN 999
                    ELSE 8
                END, 
                TIPO DESC;";

    try {
        $stmt = $conn->prepare($SQL);
        $stmt->bindParam(':idorcamento', $idorcamento, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        return false;
    }
}

if (isset($_GET['idorcamento'])) {
    $idorcamento = $_GET['idorcamento'];
    $arrayResultados = produto($idorcamento);    

    $produtosAgrupados = [];

    if ($arrayResultados !== false) {
        foreach ($arrayResultados as $produto) {
            $tipo = !empty($produto["tipo"]) ? $produto["tipo"] : "Sem Tipo";

            if (!isset($produtosAgrupados[$tipo])) {
                $produtosAgrupados[$tipo] = [];
            }
            $produtosAgrupados[$tipo][] = $produto;
        }
    }

    if (!empty($produtosAgrupados)) {
        foreach ($produtosAgrupados as $tipo => $produtos) {
            echo '<div class="tipo-produto">';
            echo '<h2 style="text-align: center; background-color: white; width: 100%; color: #6a11cb; border-radius: 10px; padding: 5px;">' . htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8') . '</h2>';
            echo '<table class="tabela">';
            echo '<thead><tr>';
            echo '<th>Imagem</th><th>Tipo</th><th>Modelo</th><th>Cor</th><th>Valor</th><th>Quant</th>';
            echo '</tr></thead><tbody>';
            foreach ($produtos as $produto) {
                $nova_imagem = isset($produto["imagem"]) ? htmlspecialchars($produto["imagem"], ENT_QUOTES, 'UTF-8') : 'uploads/henrimack.png';
                $imagemurl = str_replace("http://localhost/henrimack sistsolut", "/henrimack sistsolut", $nova_imagem);
                $imagem = urldecode($imagemurl);
                $tipoProduto = isset($produto["tipo"]) ? htmlspecialchars($produto["tipo"], ENT_QUOTES, 'UTF-8') : 'N/A';
                $modelos = isset($produto["modelos"]) ? htmlspecialchars($produto["modelos"], ENT_QUOTES, 'UTF-8') : 'N/A';
                $cor = isset($produto["cor"]) ? htmlspecialchars($produto["cor"], ENT_QUOTES, 'UTF-8') : 'N/A';
                $valor = isset($produto["valor"]) ? htmlspecialchars($produto["valor"], ENT_QUOTES, 'UTF-8') : 'N/A';
                $quantidade = isset($produto["quantidade"]) ? htmlspecialchars($produto["quantidade"], ENT_QUOTES, 'UTF-8') : 'N/A';

                echo '<tr>';
                echo '<td><div class="image-container"><img src="' . $imagem . '" alt="Imagem" onerror="this.onerror=null; this.src=\'uploads/henrimack.png\';"></div></td>';
                echo '<td>' . $tipoProduto . '</td>';
                echo '<td>' . $modelos . '</td>';
                echo '<td>' . $cor . '</td>';
                echo '<td>' . $valor . '</td>';
                echo '<td style="display: flex; justify-content: space-around;margin-top: 34px;">' . $quantidade . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';
        }
    } else {
        echo '<p>Nenhum produto encontrado para este orçamento.</p>';
    }
}
?>
