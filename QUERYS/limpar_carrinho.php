<?php
session_start();

// Limpe a sessão carrinho
unset($_SESSION['carrinho']);

// Limpe o pagamento_id da sessão (se existir)
unset($_SESSION['pagamento_id']);

// Responda com uma confirmação
echo json_encode(array('message' => 'Carrinho e pagamento_id limpos com sucesso.'));
?>