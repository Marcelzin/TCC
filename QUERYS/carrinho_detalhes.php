<?php
session_start();
include_once('config.php');

$totalPedido = 0; // Inicialize a variável para calcular o total do pedido

?>
<table id="cart-table">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $productId => $quantity) {
                // Consulte o banco de dados para obter detalhes do produto com base no $productId
                $sql = "SELECT nome, valor_venda FROM produto WHERE id = $productId";
                $result = mysqli_query($conexao, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $productName = $row['nome'];
                    $productPrice = $row['valor_venda'];
                    $subtotal = $productPrice * $quantity;
                    $totalPedido += $subtotal; // Adicione o subtotal ao total do pedido
                    echo "<tr>
                        <td>$productName</td>
                        <td>$quantity</td>
                        <td>R$ $productPrice</td>
                        <td>R$ $subtotal</td>
                    </tr>";
                }
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" align="right"><strong>Total:</strong></td>
            <td><strong>R$ <?php echo $totalPedido; ?></strong></td>
        </tr>
    </tfoot>
</table>
