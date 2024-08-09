<?php
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new mPDF();

try {
    $db = new PDO("mysql:host=localhost;dbname=henrimack", "root");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erro de conexão com o banco de dados: ' . $e->getMessage());
}

try {
    $query = $db->query("SELECT * 
                        FROM produtoshenr
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
                    TIPO DESC;");
    $data = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erro na consulta SQL: ' . $e->getMessage());
}

$categorias = [];
foreach ($data as $row) {
    $tipo = $row['TIPO'];
    if (!isset($categorias[$tipo])) {
        $categorias[$tipo] = [];
    }
    $categorias[$tipo][] = $row;
}

$html = '
<style>
    body {
        font-family: "Arial", sans-serif;
    }

    .container {
        margin: 20px auto;
        max-width: 800px;
    }

    h1, h3, h4 {
        text-align: center;
        color: #6a11cb;
    }
    
    h2 {
        text-align: center;
    }

    .table-container {
        border: 1px solid #6a11cb;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 20px;
    }

    .category-header {
        background-color: #6a11cb;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        text-align: left;
        padding: 12px;
        border-bottom: 1px solid #fff;
    }

    th {
        background-color: #6a11cb;
        color: #fff;
    }

    .image-container {
        width: 70px;
        height: 100px;
        overflow: hidden;
    }

    .image-container img {
        width: 100%;
        height: auto;
    }
</style>
';

$html .= '<div class="container">';
$html .= '<img src="uploads/henrimack.png" alt="Descrição da Imagem" width="200" height="200">';
$html .= '<h3>Atendimento: <span style="color: #6a11cb; font-weight: bold;">Ewerton Gomes</span> | WhatsApp: <span style="color: #6a11cb; font-weight: bold;">(11) 94247-2250</span></h3>';


foreach ($categorias as $tipo => $itens) {
    $html .= '<div class="table-container">';
    $html .= '<h2 style="background-color: #fff; color: #6a11cb; border-radius: 5px;">' . $tipo . '</h2>';
    $html .= '<table>';
    $html .= '<tr><th>Imagem</th><th>Modelo</th><th>Cor</th>';

    if (isset($_GET['info']) && $_GET['info'] === "ImprimirValor") {
        $html .= '<th>Valores</th>';
    }

    $html .= '</tr>';

    foreach ($itens as $item) {
        $html .= '
            <tr>
                <td>
                    <div class="image-container">
                        <img src="' . $item["IMAGEM"] . '" alt="Imagem" style="width: 8%; height: 10%;">
                    </div>
                </td>
                <td>' . $item['MODELOS'] . '</td>
                <td>' . $item['COR'] . '</td>';

        if (isset($_GET['info']) && $_GET['info'] === "ImprimirValor") {
            $html .= '<td>' . $item['VALOR'] . '</td>';
        }

        $html .= '</tr>';
    }

    $html .= '</table>';
    $html .= '</div>';
}


$html .= '</div>';
$mpdf->WriteHTML($html);
$mpdf->Output("Catalogo_HenrimackSistema.pdf","D");
?>
