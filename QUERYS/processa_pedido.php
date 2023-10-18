<?php
session_start();
include_once('config.php');

// Verifique se a sessão está configurada e se há itens no carrinho
if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
    // Obtenha os valores necessários para inserir na tabela de pedido
    $data_pedido = date('Y-m-d H:i:s'); // Data e hora atual
    $totalPedido = 0; // O valor total deve ser calculado
    $responsavel_id = $_SESSION['usuario_id']; // ID do usuário na sessão
    $comercio_id = $_SESSION['comercio_id']; // ID do comércio na sessão

    $totalLucro = 0; // Inicialize a variável para calcular o lucro do pedido

    // Verifique o valor selecionado no select de forma de pagamento
    if (isset($_POST['pagamento_id'])) {
        $pagamento_id = $_POST['pagamento_id'];
    } else {
        $pagamento_id = 1; // Valor padrão (no exemplo, é 1)
    }

    // Calcule o valor total e o lucro com base nos itens do carrinho
    foreach ($_SESSION['carrinho'] as $productId => $quantity) {
        // Consulte o banco de dados para obter detalhes do produto com base no $productId
        $sql = "SELECT valor_venda, valor_fabrica FROM produto WHERE id = $productId";
        $result = mysqli_query($conexao, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $productPrice = $row['valor_venda'];
            $productCost = $row['valor_fabrica'];
            $subtotal = $productPrice * $quantity;
            $totalPedido += $subtotal;

            // Calcule o lucro para esta linha
            $lucro = ($productPrice - $productCost) * $quantity;
            $totalLucro += $lucro; // Adicione o lucro desta linha ao lucro total
        }
    }

    // Insira os dados na tabela de pedido, incluindo o lucro e a forma de pagamento
    $sqlInsertPedido = "INSERT INTO pedido (data_pedido, valor_total, lucro_obtido, responsavel_id, pagamento_id, comercio_id)
    VALUES (NOW(), $totalPedido, $totalLucro, $responsavel_id, $pagamento_id, $comercio_id)";
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