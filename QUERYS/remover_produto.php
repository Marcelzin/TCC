<?php
session_start();
include_once('config.php');

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Verifique se o produto está no carrinho
    if (isset($_SESSION['carrinho'][$productId])) {
        // Remova o produto do carrinho
        unset($_SESSION['carrinho'][$productId]);
    }

    // Você pode retornar uma resposta JSON para indicar o sucesso da remoção, se necessário
    echo json_encode(array('message' => 'Produto removido com sucesso do carrinho.'));
} else {
    // Se o ID do produto não for fornecido na solicitação, retorne um erro
    echo json_encode(array('error' => 'ID do produto não fornecido.'));
}
?>