<?php
session_start();
include_once('config.php');

$totalPedido = 0; // Inicialize a variável para calcular o total do pedido
?>
<table id="cart-table" class="table table-bordered table-hover">
    <thead class="table-primary">
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Subtotal</th>
            <th>Excluir</th> <!-- Nova coluna para ação (remover) -->
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
                    <td>
                        <input type='number' class='form-control' value='$quantity' data-product-id='$productId' onchange='updateQuantity(this)'>
                    </td>
                    <td>R$ $productPrice</td>
                    <td>R$ $subtotal</td>
                    <td>
                        <button class='btn btn-danger' data-product-id='$productId' onclick='removeProduct(this)'>
                            <i class='fas fa-trash'></i> <!-- Ícone de lixeira do FontAwesome -->
                        </button>
                    </td>
                </tr>";
                }
            }
        }
        ?>
    </tbody>
    <tfoot class="table-primary">
        <tr>
            <td colspan="3" align="right"><strong>Total:</strong></td>
            <td colspan="2"><strong>R$ <?php echo $totalPedido; ?></strong></td>
        </tr>
    </tfoot>
</table>
