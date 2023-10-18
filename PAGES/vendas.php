<?php
session_start();

if (isset($_SESSION['comercio_id']) && isset($_SESSION['usuario_id'])) {
    include_once('config.php');

    // Inicialize a variável $nivel_acesso com um valor padrão
    $nivel_acesso = '';

    // Consulta SQL para buscar o nível de acesso do usuário com base no 'usuario_id'
    $usuario_id = $_SESSION['usuario_id'];
    $sql = "SELECT nivel_acesso FROM usuario WHERE ID = '$usuario_id'";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $nivel_acesso = $row['nivel_acesso'];
    } else {
        // Se houver um erro na consulta, você pode lidar com ele aqui
        echo "Erro na consulta: " . mysqli_error($conexao);
        exit();
    }
} else {
    echo '<script type="text/javascript">
            alert("Acesso Negado. Você precisa efetuar login novamente.");
            window.location.href = "../index.html"; // Redirecione imediatamente
          </script>';
    exit();
}

// Consulta SQL para buscar os produtos na tabela 'produto' com base no 'comercio_id' e status 'Ativo'
$comercio_id = $_SESSION['comercio_id'];
$sqlProdutos = "SELECT * FROM produto WHERE comercio_id = '$comercio_id' AND status = 'Ativo'";
$resultProdutos = mysqli_query($conexao, $sqlProdutos);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Vendas</title>
    <link rel="stylesheet" href="/TCC/CSS/cadastro_prod.css">
    <link rel="stylesheet" href="/TCC/CSS/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap');
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #2e3559 !important;">
        <div class="container-fluid">
            <a class="navbar-brand" href="/TCC/PAGES/home.php">
                <img src="/TCC/STATIC/PDV-HERMES4.png" alt="Logo" width="60" height="auto">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" style="color: #fff !important;" href="/TCC/PAGES/home.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: #fff !important; font-weight: 600;"
                            href="/TCC/PAGES/vendas.php">VENDAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: #fff !important;"
                            href="/TCC/PAGES/cadastro_prod.php">PRODUTOS</a>
                    </li>
                    <?php if ($nivel_acesso === 'Proprietário') { ?>
                        <li class="nav-item active">
                            <a class="nav-link" style="color: #fff !important;"
                                href="/TCC/PAGES/funcionarios.php">USUÁRIOS</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div>
                <a class="nav-link" style="color: #fff !important; cursor: pointer" onclick="logoff()"><ion-icon
                        name="exit-outline"></ion-icon></a>
            </div>
        </div>
    </nav>

    <div class="container"
        style="margin-right: 0px !important; margin-left: 0px !important; padding-left: 0px !important; padding-right: 0px !important; width: 70% !important; float: left">
        <div class="row">
            <?php
            while ($rowProduto = mysqli_fetch_assoc($resultProdutos)) {
                // Exiba cada produto usando cartões do Bootstrap
                echo '<div class="col-md-4">
                    <div class="card mb-4 h-100">
                        <img src="' . $rowProduto['imagem'] . '" class="card-img-top" style="min-height: 200px;max-height: 200px; object-fit: contain;" alt="Imagem do Produto">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">' . $rowProduto['nome'] . '</h5>
                            <p class="card-text flex-grow-1">' . $rowProduto['descricao'] . '</p>
                            <p class="card-text">R$ ' . $rowProduto['valor_venda'] . '</p>
                            <a data-product-id="' . $rowProduto['id'] . '" class="btn btn-primary mt-auto add-to-cart">Adicionar ao carrinho</a>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>

    <div style="width: 30%; float: right">
        <h3 class="mt-4" style="text-align: center">Resumo do pedido</h3>
        <table id="cart-table" class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalPedido = 0; // Inicialize a variável para calcular o total do pedido
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
            <tfoot>
                <tr class="table-primary">
                    <td colspan="3" align="right"><strong>Total:</strong></td>
                    <td><strong>R$
                            <?php echo $totalPedido; ?>
                        </strong></td>
                </tr>
            </tfoot>
        </table>
        <div style="width: 100%; justify-content: space-evenly; display: flex">
            <a class="btn btn-danger" onclick="limparPedido()">Limpar</a>
            <a class="btn btn-success" onclick="FinalizarPedido()">Finalizar</a>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <!-- Seu código JavaScript para adicionar produtos ao carrinho aqui -->
    <script>
        // Função para atualizar a tabela do carrinho
        function updateCartTable() {
            // Faça uma requisição AJAX para obter os detalhes do carrinho a partir do servidor
            $.ajax({
                method: "GET",
                url: '/TCC/QUERYS/carrinho_detalhes.php', // Substitua pelo URL correto para obter os detalhes do carrinho
                success: function (data) {
                    // Atualize a tabela do carrinho com os dados recebidos
                    $("#cart-table").html(data);
                },
                error: function (error) {
                    console.error(error);
                    // Lidar com erros, se necessário
                }
            });
        }

        // Lidar com o clique no botão "Adicionar ao Carrinho"
        $(".add-to-cart").click(function (event) {
            event.preventDefault(); // Evita o comportamento padrão do link

            var productId = $(this).data("product-id");

            // Envie o ID do produto ao servidor para adicionar ao carrinho
            $.ajax({
                method: "POST",
                url: '/TCC/QUERYS/adicionar_ao_carrinho.php', // Substitua pelo URL correto do seu arquivo PHP para adicionar ao carrinho
                data: {
                    productId: productId
                },
                success: function (response) {
                    // Lide com a resposta do servidor, que pode incluir a confirmação de que o produto foi adicionado ao carrinho
                    Swal.fire({
                        icon: 'success',
                        title: 'Produto adicionado ao carrinho!',
                        position: 'top-end', // Posição no topo direito
                        toast: true, // Notificação estilo "toast"
                        showConfirmButton: false,
                        timer: 1500
                    });


                    // Atualize a tabela do carrinho após a adição
                    updateCartTable();
                },
                error: function (error) {
                    console.error(error);
                    // Lidar com erros, se necessário
                }
            });
        });

        // Chame a função para atualizar a tabela do carrinho ao carregar a página
        updateCartTable();
    </script>

    <script>
        function limparPedido() {
            $.ajax({
                method: "POST",
                url: '/TCC/QUERYS/limpar_carrinho.php', // Substitua pelo URL correto do seu arquivo PHP para limpar o carrinho
                success: function (response) {
                    // Limpe a tabela do carrinho
                    $("#cart-table tbody").html('');
                    // Atualize o total do pedido para 0
                    $("#cart-table tfoot td:last-child").text('R$ 0');
                },
                error: function (error) {
                    console.error(error);
                    // Lidar com erros, se necessário
                }
            });
        }
    </script>

    <script>
        function updateQuantity(input) {
            var productId = $(input).data("product-id");
            var newQuantity = $(input).val();

            // Envie uma solicitação AJAX para atualizar a quantidade no servidor
            $.ajax({
                method: "POST",
                url: '/TCC/QUERYS/atualizar_quantidade.php', // Substitua pelo URL correto do arquivo PHP para atualizar a quantidade
                data: {
                    productId: productId,
                    quantity: newQuantity
                },
                success: function (response) {
                    // Atualize a tabela do carrinho após a atualização da quantidade
                    updateCartTable();
                },
                error: function (error) {
                    console.error(error);
                    // Lidar com erros, se necessário
                }
            });
        }

    </script>

    <script>
        function removeProduct(button) {
            var productId = $(button).data("product-id");

            // Faça uma requisição AJAX para remover o produto do carrinho
            $.ajax({
                method: "POST",
                url: '/TCC/QUERYS/remover_produto.php', // Substitua pelo URL correto do seu arquivo PHP para remover o produto
                data: {
                    productId: productId
                },
                success: function (response) {
                    // Atualize a tabela do carrinho após a remoção
                    updateCartTable();
                },
                error: function (error) {
                    console.error(error);
                    // Lidar com erros, se necessário
                }
            });
        }

    </script>

    <script>
        function FinalizarPedido() {
            $.ajax({
                method: "POST",
                url: "/TCC/QUERYS/processa_pedido.php",
                success: function (response) {
                    // Lide com a resposta do servidor (mensagem de sucesso ou erro)
                    if (response.indexOf("Pedido inserido com sucesso") !== -1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido finalizado com sucesso!',
                            text: 'Seu pedido foi registrado com sucesso.',
                            onClose: function () {
                                // Chame a função limparPedido após fechar o alerta de sucesso
                                limparPedido();
                            }
                        });

                        // Redirecione para a página desejada após o sucesso, se necessário
                        // window.location.href = "pagina_de_sucesso.php";
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao finalizar o pedido',
                            text: 'Houve um problema ao processar seu pedido. Por favor, tente novamente.',
                        });
                    }
                },
                error: function (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao finalizar o pedido',
                        text: 'Houve um problema ao processar seu pedido. Por favor, tente novamente.',
                    });
                }
            });
        }
    </script>

</body>

</html>