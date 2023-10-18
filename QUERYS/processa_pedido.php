<?php
session_start();
include_once('config.php');

// Verifique se a sessão está configurada e se há itens no carrinho
if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
    // Obtenha os valores necessários para inserir na tabela de pedido
    $data_pedido = date('Y-m-d H:i:s'); // Data e hora atual
    $totalPedido = 0; // O valor total deve ser calculado
    $responsavel_id = $_SESSION['usuario_id']; // ID do usuário na sessão
    $pagamento_id = 1; // Defina o ID do método de pagamento (no exemplo, é 1)
    $comercio_id = $_SESSION['comercio_id']; // ID do comércio na sessão

    // Calcule o valor total com base nos itens do carrinho
    foreach ($_SESSION['carrinho'] as $productId => $quantity) {
        // Consulte o banco de dados para obter detalhes do produto com base no $productId
        $sql = "SELECT valor_venda FROM produto WHERE id = $productId";
        $result = mysqli_query($conexao, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $productPrice = $row['valor_venda'];
            $subtotal = $productPrice * $quantity;
            $totalPedido += $subtotal;
        }
    }

    // Insira os dados na tabela de pedido
$sqlInsertPedido = "INSERT INTO pedido (data_pedido, valor_total, lucro_obtido, responsavel_id, pagamento_id, comercio_id)
                  VALUES (NOW(), $totalPedido, NULL, $responsavel_id, $pagamento_id, $comercio_id)";

    if (mysqli_query($conexao, $sqlInsertPedido)) {
        // Pedido inserido com sucesso

        // Limpe o carrinho (destrua a sessão)
        $_SESSION['carrinho'] = array();

        echo "Pedido inserido com sucesso!";
    } else {
        // Erro ao inserir o pedido
        echo "Erro ao processar o pedido: " . mysqli_error($conexao);
    }
} else {
    echo "Carrinho vazio. Adicione produtos ao carrinho antes de finalizar o pedido.";
}

// Feche a conexão com o banco de dados
mysqli_close($conexao);
?>