<?php
$categorias = array(
    "Banquetas",
    "Sofá/Poltronas",
    "Bistrô",
    "Cadeiras",
    "Eletro/Acessórios",
    "Puff's",
    "Mesa/centro",
    "Mesa/canto",
    "Mesa/reunião"
);

foreach ($categorias as $categoria) {
    if (!empty($dadosTabela["TIPO"]) && $dadosTabela["TIPO"] === $categoria) {
        continue; 
    }
    echo '<option value="' . htmlspecialchars($categoria) . '">' . htmlspecialchars($categoria) . '</option>';
}
?>