<?php
include_once('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_filtra = $_POST['nome_filtra'];
    $descricao_filtra = $_POST['descricao_filtra'];
    $valor_prod_filtra = $_POST['valor_prod_filtra'];
    $preco_filtra = $_POST['preco_filtra'];

    $comercio_id = $_SESSION['comercio_id'];

    $query = "SELECT * FROM produto WHERE comercio_id = ? AND status = 'Ativo'";

    $types = "i";
    $params = array(&$comercio_id);

    if (!empty($nome_filtra)) {
        $query .= " AND nome LIKE ?";
        $types .= "s";
        $params[] = "%$nome_filtra%";
    }

    if (!empty($descricao_filtra)) {
        $query .= " AND descricao LIKE ?";
        $types .= "s";
        $params[] = "%$descricao_filtra%";
    }

    if (!empty($valor_prod_filtra)) {
        $query .= " AND valor_fabrica <= ?";
        $types .= "d";
        $params[] = floatval($valor_prod_filtra);
    }

    if (!empty($preco_filtra)) {
        $query .= " AND valor_venda <= ?";
        $types .= "d";
        $params[] = floatval($preco_filtra);
    }

    $query .= " ORDER BY status ASC"; // Adiciona a cláusula ORDER BY

    $stmt = mysqli_prepare($conexao, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Inicialize um contêiner para os cards de produtos
            echo '<div class="row" style="padding-top: 5%">';

            while ($row = mysqli_fetch_assoc($result)) {
                // Construa um card para cada produto
                echo '<div class="col-md-4">
                    <div class="card mb-4 h-100">
                        <img src="' . $row['imagem'] . '" class="card-img-top"
                            style="min-height: 200px;max-height: 200px; object-fit: contain;"
                            alt="Imagem do Produto">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">' . $row['nome'] . '</h5>
                            <p class="card-text">' . $row['descricao'] . '</p>
                            <p class="card-text">R$' . number_format($row['valor_venda'], 2, ',', '.') . '</p>';
                echo '<a href="#" data-product-id="' . $row['id'] . '" class="btn btn-primary mt-auto add-to-cart">Adicionar ao pedido</a>
                        </div>
                    </div>
                </div>';
            }

            // Feche o contêiner de cards
            echo '</div>';
        } else {
            echo 'Nenhum registro encontrado.';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo 'Erro na preparação da declaração: ' . mysqli_error($conexao);
    }
}
?>