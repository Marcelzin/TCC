<?php
session_start();
include_once('config.php');

$totalPedido = 0; // Inicialize a variável para calcular o total do pedido
$totalLucro = 0; // Inicialize a variável para calcular o lucro total do pedido

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
                $sql = "SELECT nome, valor_fabrica, valor_venda FROM produto WHERE id = $productId";
                $result = mysqli_query($conexao, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $productName = $row['nome'];
                    $productCost = $row['valor_fabrica'];
                    $productPrice = $row['valor_venda'];
                    $subtotal = $productPrice * $quantity;
                    $totalPedido += $subtotal; // Adicione o subtotal ao total do pedido
        
                    // Calcule o lucro para esta linha
                    $lucro = ($productPrice - $productCost) * $quantity;
                    $totalLucro += $lucro; // Adicione o lucro desta linha ao lucro total
        
                    echo "<tr>
                    <td>$productName</td>
                    <td>
                    <input type='number' class='form-control' value='$quantity' data-product-id='$productId' onchange='updateQuantity(this)' min='1'>
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
            <td colspan="2"><strong>R$
                    <?php echo $totalPedido; ?>
                </strong></td>
        </tr>
        <tr>
            <td colspan="3" align="right"><strong>Lucro Total:</strong></td>
            <td colspan="2"><strong>R$
                    <?php echo $totalLucro; ?>
                </strong></td>
        </tr>
    </tfoot>
</table>