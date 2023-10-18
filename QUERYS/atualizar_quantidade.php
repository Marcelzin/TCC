<?php
session_start();
include_once('config.php');

if (isset($_POST['productId']) && isset($_POST['quantity'])) {
    $productId = $_POST['productId'];
    $newQuantity = intval($_POST['quantity']); // Converta a nova quantidade em um número inteiro

    // Verifique se o produto existe no carrinho
    if (isset($_SESSION['carrinho'][$productId])) {
        // Atualize a quantidade do produto no carrinho
        $_SESSION['carrinho'][$productId] = $newQuantity;

        // Responda com uma confirmação de atualização bem-sucedida
        echo json_encode(array('message' => 'Quantidade atualizada com sucesso.'));
    } else {
        // Responda com um erro se o produto não foi encontrado no carrinho
        echo json_encode(array('error' => 'Produto não encontrado no carrinho.'));
    }
} else {
    // Responda com um erro se os parâmetros não foram fornecidos corretamente
    echo json_encode(array('error' => 'Parâmetros inválidos.'));
}
?>
