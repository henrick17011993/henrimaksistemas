<?php
require_once __DIR__ . '/vendor/autoload.php';
require "php/funcoes.php";

$mpdf = new mPDF();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lendo e decodificando a entrada JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao decodificar JSON: ' . json_last_error_msg()
        ]);
        exit;
    }

    $evento = $data['evento'] ?? '';
    $montadora = $data['montadora'] ?? '';
    $contato = $data['contato'] ?? '';
    $stand = $data['stand'] ?? '';
    $local = $data['local'] ?? '';
    $entrega = $data['entrega'] ?? '';
    $retirada = $data['retirada'] ?? '';
    $produtosSelecionados = $data['produtos'] ?? [];

    try {
        $db = new PDO("mysql:host=localhost;dbname=henrimack", "root");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro de conexão com o banco de dados: ' . $e->getMessage()
        ]);
        exit;
    }

    $html = '
    <link rel="stylesheet" type="text/css" href="css/entregapdf.css" />
    <div style="background-color:  #6a11cb; padding: 5px; margin-top: 40px;">
        <img src="uploads/henrimack.png" alt="Logo HenrimackSistema" width="200" height="200">
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="50%" class="borda-arredondada">
        <tr>
            <th colspan="2" style="text-align: right; width: 20%;">&nbsp;</th>
            <th colspan="10" style="text-align: left; width: 80%;"><b>Stand:</b> ' . $stand . '</th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: left; width: 20%;"><b>Evento:</b> ' . $evento . '</th>
            <th colspan="10" style="text-align: left; width: 80%;"><b>Local:</b> ' . $local . '</th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: left; width: 20%;"><b>Montadora:</b> ' . $montadora . '</th>
            <th colspan="10" style="text-align: left; width: 80%;"><b>Entrega:</b> ' . $entrega . '</th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: left ; width: 20%;"><b>Contato:</b> ' . $contato . '</th>
            <th colspan="10" style="text-align: left; width: 80%;"><b>Retirada:</b> ' . $retirada . '</th>
        </tr>
        <tr>
            <th colspan="1" style="text-align: left; background-color: #9a6ccb; width: 25%;"><b>Modelo</b></th>
            <th colspan="1" style="text-align: left; background-color: #9a6ccb; width: 25%;"><b>Observação</b></th>
            <th colspan="1" style="text-align: center; background-color: #9a6ccb; width: 25%;"><b>Imagem</b></th>
            <th colspan="1" style="text-align: center; background-color: #9a6ccb; width: 25%;"><b>Quantidade</b></th>
        </tr>
        <tbody>';

    foreach ($produtosSelecionados as $index => $produto) {
        if ($index % 10 === 0 && $index !== 0) {
            $mpdf->AddPage();
        }

        $id = $produto['id'];
        $quantidade = $produto['quantidade'];
        $observacao = urldecode($produto['observacao']);
        $obse_queb = wordwrap($observacao, 10, "\n", true);

        try {
            $query = $db->query("SELECT IMAGEM, MODELOS FROM produtoshenr WHERE ID = $id");
            $data = $query->fetchAll(PDO::FETCH_ASSOC);

            if ($query->rowCount() > 0) {
                foreach ($data as $item) {
                    $imagem = $item['IMAGEM'];
                    $modelo = $item['MODELOS'];

                    $html .= '
                    <tr>
                        <td>' . $modelo . '</td>
                        <td>' . $obse_queb . '</td>
                        <td class="image-cell"><img src="' . $imagem . '" style="max-width: 80px;"></td>
                        <td class="center">' . $produto['quantidade'] . '</td>
                    </tr>';
                }
            } else {
                $html .= '<tr><td colspan="3">Produto não encontrado para o ID: ' . $id . '</td></tr>';
            }
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro na consulta SQL: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    $html .= '
        </tbody>
        <tfoot>
            <tr style="background-color: #fff; color: #fff; border-radius: 50px;">
                <td colspan="4" style="color: #000;">Assinatura: __________________________________________________________________________________</td>
            </tr>
        </tfoot>
    </table>';

    $mpdf->WriteHTML($html);
    $data_atual = date('d/m/Y');
    $mpdf->SetFooter("{$montadora} - {$data_atual} - pág.{PAGENO}");
    $pdf_filename = "TabelaEntrega_{$montadora}.pdf";
    $mpdf->Output($pdf_filename, 'F'); // Salva o arquivo no servidor

    // Retorna um JSON indicando o sucesso e o nome do arquivo PDF
    echo json_encode([
        'success' => true,
        'pdf_filename' => $pdf_filename
    ]);
}
?>


