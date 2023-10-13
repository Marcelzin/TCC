<?php
// Inclua o arquivo de configuração
include('config.php');

// Verifique se a variável POST 'productIds' foi enviada
if (isset($_POST['productIds'])) {
    // Recupere os IDs dos produtos da requisição POST
    $productIds = $_POST['productIds'];

    // Inicialize uma variável para armazenar o HTML dos detalhes dos produtos
    $productDetailsHTML = '';

    // Consulta SQL para buscar informações dos produtos com base nos IDs
    $query = "SELECT * FROM produto WHERE id IN (" . implode(",", $productIds) . ")";

    // Execute a consulta
    $result = mysqli_query($conexao, $query);

    if ($result) {
        // Recupere os detalhes dos produtos e construa o HTML
        while ($row = mysqli_fetch_assoc($result)) {
            $productId = $row['id'];
            $quantity = 1; // Defina a quantidade inicial como 1 (pode ser atualizada pelo JavaScript)
            $totalPrice = $row['valor_venda'];

            $productDetailsHTML .= '<div class="product-details" data-product-id="' . $productId . '">
                <div class="product-name">
                    <span class="name-product">' . $row['nome'] . '</span>
                </div>
                <div class="quantity-controls">
                    <button class="btn btn-secondary quantity-btn-minus">-</button>
                    <input type="text" class="quantity-input" style="width: 6vh; border: none; text-align: center" value="' . $quantity . '">
                    <button class="btn btn-secondary quantity-btn-plus">+</button>
                    <span class="final-price">R$ ' . $totalPrice . '</span>
                    <div style="float: right">
                        <button class="btn btn-danger remove-btn">
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>';
        }

        // Retorna os detalhes dos produtos como resposta
        echo $productDetailsHTML;
    } else {
        // Se houver um erro na consulta, você pode lidar com ele aqui
        echo "Erro na consulta: " . mysqli_error($conexao);
    }

    // Feche a conexão com o banco de dados
    mysqli_close($conexao);
} else {
    // Caso 'productIds' não seja recebido, retorne uma resposta de erro
    echo "Nenhum ID de produto fornecido na requisição.";
}
?>