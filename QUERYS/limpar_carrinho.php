<?php
session_start();

// Destrua a sessão carrinho
unset($_SESSION['carrinho']);

// Responda com uma confirmação
echo json_encode(array('message' => 'Carrinho limpo com sucesso.'));
?>
