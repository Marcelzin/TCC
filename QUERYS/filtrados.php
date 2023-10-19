<?php
// Conecte-se ao banco de dados e configure a consulta SQL
session_start(); // Inicie a sessão
include_once('config.php');
$comercio_id = $_SESSION['comercio_id'];

if (isset($_GET['filtrag'])) {
    $filtro = mysqli_real_escape_string($conexao, $_GET['filtrag']);
    $sqlProdutos = "SELECT * FROM produto WHERE comercio_id = '$comercio_id' AND status = 'Ativo' AND (nome LIKE '%$filtro%' OR descricao LIKE '%$filtro%' OR valor_venda LIKE '%$filtro%')";
} else {
    $sqlProdutos = "SELECT * FROM produto WHERE comercio_id = '$comercio_id' AND status = 'Ativo'";
}

$resultProdutos = mysqli_query($conexao, $sqlProdutos);

// Gerar o HTML para os produtos filtrados
while ($rowProduto = mysqli_fetch_assoc($resultProdutos)) {
    echo '<div class="col-md-4">
        <div class="card mb-4 h-100">
            <img src="' . $rowProduto['imagem'] . '" class="card-img-top" style="min-height: 200px; max-height: 200px; object-fit: contain;" alt="Imagem do Produto">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">' . $rowProduto['nome'] . '</h5>
                <p class="card-text flex-grow-1">' . $rowProduto['descricao'] . '</p>
                <p class="card-text">R$ ' . $rowProduto['valor_venda'] . '</p>
                <a data-product-id="' . $rowProduto['id'] . '" class="btn btn-primary mt-auto add-to-cart">Adicionar ao carrinho</a>
            </div>
        </div>
    </div>';
}
?>