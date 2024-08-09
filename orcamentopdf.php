<?php


require_once __DIR__ . '/vendor/autoload.php';
require_once "php/funcoes.php";


$mpdf = new mPDF();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "henrimack";


try {

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $consultaDetalhes = $conn->query("SELECT o.*, p.imagem, p.tipo, p.modelos, p.cor, p.valor, p.quantidade
                                    FROM orcamentohenr o
                                    LEFT JOIN produtosorcamentohenr p ON o.idorcamento = p.idorcamento
                                    WHERE o.idorcamento = (SELECT MAX(idorcamento) FROM orcamentohenr)
                                    ORDER BY o.idorcamento DESC");


    $detalhes = $consultaDetalhes->fetchAll(PDO::FETCH_ASSOC);

    $categorias = array();
    foreach ($detalhes as $item) {
        $categorias[$item['tipo']][] = $item;
    }


    $html = '
    <link rel="stylesheet" type="text/css" href="css/orcamentopdf.css" />
    ';

    $html .= '<div class="container">';
    $html .= '<p style="text-align: right;"><strong>Código:</strong> ' . strtoupper($detalhes[0]['codigounico'] ?? ' ') . '</p>';
    $html .= '<img src="uploads/henrimack.png" alt="Descrição da Imagem" width="200" height="200">';
    $html .= '<h3>Atendimento: <span style="color: #6a11cb; font-weight: bold;">Ewerton Gomes</span> | WhatsApp: <span style="color: #6a11cb; font-weight: bold;">(11) 94247-2250</span></h3>';

    $html .= '<div class="clearfix">';
    $html .= '<div class="detalhes-container1 detalhes-cliente moderno">';
    $html .= '<h2>Dados do Cliente</h2>';
    $html .= '<div class="detalhe"><span>Razão Social:</span>&nbsp;' . ($detalhes[0]['razaosocial'] ?? ' ') . '&nbsp;&nbsp;&nbsp;&nbsp;<span>CPF/CNPJ:</span>&nbsp;' . ($detalhes[0]['cpfcnpj'] ?? ' ') .  '</div>';
    $html .= '<div class="detalhe"><span>Endereço:</span>&nbsp;' . ($detalhes[0]['endereco'] ?? ' ') . ', ' . ($detalhes[0]['bairro'] ?? ' ') . '&nbsp;&nbsp;&nbsp;&nbsp;<span>WhatsApp:</span>&nbsp; ' . ($detalhes[0]['WhatsApp'] ?? ' ') . ', ' . ($detalhes[0]['estado'] ?? ' ') . ' &nbsp;&nbsp;&nbsp;&nbsp;<span>CEP:</span>&nbsp; ' . ($detalhes[0]['cep'] ?? ' ') . '</div>';
    $html .= '<div class="detalhe"><span>Contato:</span>&nbsp;' . ($detalhes[0]['contato'] ?? ' ') . '&nbsp;&nbsp;&nbsp;&nbsp;<span>Telefone:</span>&nbsp;' . ($detalhes[0]['telefone'] ?? ' ') . '&nbsp;&nbsp;&nbsp;&nbsp;<span>Email:</span>&nbsp;' . ($detalhes[0]['email'] ?? ' ') .  ' </div>';
    $html .= '</div>';

    $html .= '<div class="detalhes-container2 detalhes-evento moderno">';
    $html .= '<h2>Dados do Evento</h2>';
    $html .= '<div class="detalhe"><span>Nome do Evento:</span>&nbsp;' . ($detalhes[0]['nome_evento'] ?? ' ') . '&nbsp;&nbsp;&nbsp;&nbsp;<span>Local do Evento:</span>&nbsp;' . ($detalhes[0]['local'] ?? ' ') . '&nbsp;&nbsp;&nbsp;&nbsp;<span>Data Entrega:</span>&nbsp;' . ($detalhes[0]['data_entrega'] ?? ' ') . '</div>';
    $html .= '<div class="detalhe"><span>Data Inicial:</span>&nbsp;' . ($detalhes[0]['data_de'] ?? ' ') . '&nbsp;&nbsp;&nbsp;&nbsp;<span>Data Final:</span>&nbsp;' . ($detalhes[0]['data_ate'] ?? ' ') . '</div>';
    $html .= '</div>';
    $html .= '</div>';


    $htmlCategorias = '';

    foreach ($categorias as $tipo => $itens) {
        $htmlCategoria = '<div class="detalhes-container">';
        $htmlCategoria .= '<h2>' . $tipo . '</h2>';
        $htmlCategoria .= '<table>';
        $htmlCategoria .= '<tr><th>Imagem</th><th>Tipo</th><th>Modelo</th><th>Cor</th><th>Valor</th><th>Quantidade</th><th>Total</th></tr>';

        $totalTipo = 0;

        foreach ($itens as $item) {
            $valor = $item['valor'];
            $quantidade = $item['quantidade'];
            $totalItem = $valor * $quantidade;
            $totalTipo += $totalItem;

            $htmlCategoria .= '<tr>
                        <td>
                            <div class="image-container">
                                <img src="' . ($item["imagem"] ?? '') . '" alt="Imagem" style="width: 8%; height: 10%;">
                            </div>
                        </td>
                        <td>' . ($item['tipo'] ?? ' ') . '</td>
                        <td>' . ($item['modelos'] ?? ' ') . '</td>
                        <td>' . ($item['cor'] ?? ' ') . '</td>
                        <td>R$ ' . number_format($valor, 2, ',', '.') . '</td>
                        <td>' . $quantidade . '</td>
                        <td>R$ ' . number_format($totalItem, 2, ',', '.') . '</td>
                     </tr>';
        }

        $htmlCategoria .= '</table>';
        $htmlCategoria .= '<p style="text-align: right; margin-right: 10px;">Total ' . $tipo . ': R$ ' . number_format($totalTipo, 2, ',', '.') . '</p>';
        $htmlCategoria .= '</div>';


        $htmlCategorias .= $htmlCategoria;


        $totalGeral += $totalTipo;
    }
    if ($_GET['info'] !== $totalGeral && $_GET['check'] === "sim") {
        if ($_GET['info'] !== $totalGeral) {
            $html .= $htmlCategorias;
            $html .= '<div class="totals-container">';
            $html .= '<p>Valor Total: R$ ' . $_GET['info'] . '</p>';
            $html .= '</div>';
        } else {
            $html .= $htmlCategorias;
            $html .= '<div class="totals-container">';
            $html .= '<p>Valor Total: R$ ' . number_format($totalGeral, 2, ',', '.') . '</p>';
            $html .= '</div>';
        }
    }
    $mpdf->WriteHTML($html);
    $mpdf->Output("Orçamento_{$detalhes[0]['razaosocial']}.pdf", 'D');
} catch (PDOException $e) {
    die('Erro de conexão com o banco de dados: ' . $e->getMessage());
}
