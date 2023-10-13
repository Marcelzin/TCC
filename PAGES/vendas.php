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
                        <a class="nav-link" style="color: #fff !important;" href="/TCC/PAGES/vendas.php">VENDAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: #fff !important; font-weight: 600;"
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

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="row" style="padding-top: 5%">
                    <?php
                    if (isset($_SESSION['comercio_id'])) {
                        $comercio_id = $_SESSION['comercio_id'];

                        $sql = "SELECT * FROM produto WHERE comercio_id = '$comercio_id' AND status = 'Ativo'";
                        $result = mysqli_query($conexao, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <div class="col-md-4">
                                    <div class="card mb-4 h-100">
                                        <img src="<?php echo $row['imagem']; ?>" class="card-img-top"
                                            style="min-height: 200px;max-height: 200px; object-fit: contain;"
                                            alt="Imagem do Produto">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">
                                                <?php echo $row['nome']; ?>
                                            </h5>
                                            <p class="card-text flex-grow-1">
                                                <?php echo $row['descricao']; ?>
                                            </p>
                                            <p class="card-text">R$
                                                <?php echo $row['valor_venda']; ?>
                                            </p>
                                            <a href="#" data-product-id="<?php echo $row['id']; ?>"
                                                class="btn btn-primary mt-auto add-to-cart">Adicionar ao pedido</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div class="col-md-12">Nenhum produto encontrado para este comércio.</div>';
                        }
                        mysqli_close($conexao);
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-3" style="border-color: #999999; border-radius: 20px; border: solid 1px; margin-top: 3.5%">
                <div class="sticky-top">
                    <div class="item-list">
                        <div class="header">
                            <h4 class="text-center">Resumo do Pedido</h4>
                        </div>
                        <ul class="product-list">
                            <li class="item-list-card">
                                <div class="item-list-final-description">
                                    <div class="product-details" id="product-details-container">
                                        <!-- Conteúdo dos produtos será adicionado aqui dinamicamente -->
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="subtotal">
                            <h4 class="text-end">Subtotal: R$120,00</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <!-- Divs da forma de pagamento -->
                        <button id="pay-form" class="btn btn-secondary">
                            <ion-icon name="card-outline"></ion-icon>
                            Débito
                        </button>
                        <button id="pay-form" class="btn btn-secondary">
                            <ion-icon name="cash-outline"></ion-icon>
                            Dinheiro
                        </button>

                        <button id="pay-form" class="btn btn-secondary">
                            <ion-icon name="card-outline"></ion-icon>
                            Crédito
                        </button>
                    </div>
                    <div class="d-flex justify-content-center mb-2" style="justify-content: space-between !important;">
                        <!-- Botões de Finalizar e Limpar alinhados ao centro -->
                        <button id="clean" class="btn btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-trash-2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path
                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                </path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>Limpar
                        </button>
                        <button id="finalize" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-check">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>Finalizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
    .product-list {
        list-style-type: none;
        /* Remove as bolinhas da lista */
        padding: 0;
        /* Remove o espaçamento padrão da lista */
    }
</style>

<script>
    // Função para realizar o logoff
    function logoff() {
        $.ajax({
            method: "POST",
            url: '/TCC/QUERYS/logout.php',
            success: function (response) {
                // Exibir um alerta SweetAlert2 quando o logoff for bem-sucedido
                Swal.fire({
                    icon: 'success', // Ícone de sucesso
                    title: 'Usuário deslogado com sucesso',
                    showConfirmButton: false, // Oculta o botão de confirmação
                    timer: 1500 // Fecha automaticamente após 1,5 segundos
                });

                // Redirecionar o usuário para a página de login ou fazer outras ações após o logoff
                setTimeout(function () {
                    window.location.href = '/TCC/index.html'; // Altere para a URL correta da página de login
                }, 1500);
            },
            error: function (error) {
                console.error(error);
                // Lidar com erros, se necessário
            }
        });
    }
</script>

<script>
    // Array para armazenar os IDs dos produtos
    var productIds = [];

    // Função para lidar com o clique no botão "Adicionar ao pedido"
    function addToCartClicked(event) {
        event.preventDefault(); // Impede que o link redirecione para outra página

        // Obtém o ID do produto a partir do atributo "data-product-id"
        var productId = event.target.getAttribute('data-product-id');

        // Armazena o ID na array
        productIds.push(productId);

        // Exibe os IDs armazenados na array no console
        console.log('IDs dos produtos no carrinho:', productIds);

        // Faz uma requisição AJAX para processar o pedido
        $.ajax({
            type: "POST",
            url: "/TCC/QUERYS/processa_pedido.php",
            data: { productIds: productIds },
            success: function (response) {
                console.log("Resposta do servidor:", response);
                // Você pode lidar com a resposta do servidor aqui

                // Atualize a div 'product-details-container' com a resposta do servidor
                $("#product-details-container").html(response);
            },
            error: function (xhr, status, error) {
                console.error("Erro na requisição AJAX:", error);
            }
        });
    }

    // Selecione todos os botões "Adicionar ao pedido" e adicione um evento de clique a cada um deles
    var addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(function (button) {
        button.addEventListener('click', addToCartClicked);
    });
</script>



</body>

</html>