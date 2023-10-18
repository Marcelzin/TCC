<?php
session_start();

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Certifique-se de que a variável de sessão do carrinho exista ou inicialize-a, se necessário
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = array();
    }

    // Verifique se o produto já está no carrinho
    if (isset($_SESSION['carrinho'][$productId])) {
        // Se o produto já está no carrinho, aumente a quantidade
        $_SESSION['carrinho'][$productId]++;
    } else {
        // Caso contrário, adicione o produto ao carrinho com uma quantidade de 1
        $_SESSION['carrinho'][$productId] = 1;
    }

    // Responda com uma confirmação de que o produto foi adicionado ao carrinho
    echo json_encode(array('message' => 'Produto adicionado ao carrinho com sucesso.'));
} else {
    // Responda com um erro se o ID do produto não for fornecido
    echo json_encode(array('error' => 'ID do produto não fornecido.'));
}
?>