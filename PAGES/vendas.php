<?php
session_start();

// Resto do seu código PHP
include_once('config.php');
// ...

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/TCC/CSS/vendas.css">
    <link rel="stylesheet" href="/TCC/CSS/menu.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap');
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #2e3559 !important;">
        <div class="container-fluid">
            <a class="navbar-brand" href="/TCC/PAGES/home.html">
                <img src="/TCC/STATIC/PDV-HERMES.png" alt="Logo" width="60" height="auto">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" style="color: #fff !important;" href="/TCC/PAGES/vendas.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: #fff !important;"
                            href="/TCC/PAGES/cadastro_prod.php">Cadastro</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" style="color: #fff !important;"
                            href="/TCC/PAGES/funcionarios.php">Funcionário</a>
                    </li>
                </ul>
            </div>
            <div>
                <a class="nav-link" style="color: #fff !important;" href="/TCC/index.html">Sair</a>
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

                        $sql = "SELECT * FROM produto WHERE comercio_id = '$comercio_id'";
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
            <div class="col-md-3">
                <div class="sticky-top">
                    <div class="item-list">
                        <div
                            style="width: 100%; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; padding: 5%; font-weight: bold">
                            <span class="lista">Resumo do pedido</span>
                        </div>
                        <ul class="product-list">
                            <!-- <li class="item-list-card">
                                <div class="item-list-final-image"></div>
                                <div class="item-list-final-description">
                                    <section id="name-product">
                                        <span class="name-product">Nome do produto</span>
                                    </section>
                                    <section id="item-qtd">
                                        <span class="item-qnt">x4</span>
                                    </section>
                                    <span class="final-price">R$30,00</span>
                                </div>
                            </li> -->
                        </ul>
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

<style>
    .product-list {
        list-style-type: none; /* Remove as bolinhas da lista */
        padding: 0; /* Remove o espaçamento padrão da lista */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        const productList = document.querySelector('.product-list');
        const cartItems = [];

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const productId = button.getAttribute('data-product-id');
                const productName = button.closest('.card').querySelector('.card-title').textContent;
                const productPrice = parseFloat(button.closest('.card').querySelector('.card-text').textContent.replace('R$', '').trim());

                // Check if the product is already in the cart
                const existingCartItem = cartItems.find(item => item.productId === productId);

                if (existingCartItem) {
                    // If it's already in the cart, increment the quantity
                    existingCartItem.quantity++;
                    // Update the quantity in the UI
                    const quantitySpan = document.querySelector(`.item-qnt[data-product-id="${productId}"]`);
                    quantitySpan.textContent = `x${existingCartItem.quantity}`;
                } else {
                    // If it's not in the cart, create a new cart item
                    const cartItem = {
                        productId,
                        productName,
                        quantity: 1,
                        productPrice
                    };

                    // Add the new cart item to the cartItems array
                    cartItems.push(cartItem);

                    // Create a new list item for the cart
                    const listItem = document.createElement('li');
                    listItem.classList.add('item-list-card');

                    listItem.innerHTML = `
                        <div class="item-list-final-description">
                            <section id="name-product">
                                <span class="name-product">${productName}</span>
                            </section>
                            <section id="item-qtd" class="item-qnt" data-product-id="${productId}">
                                <input type="number" min="1" value="1" class="item-quantity">
                            </section>
                            <span class="final-price">R$${(cartItem.quantity * cartItem.productPrice).toFixed(2)}</span>
                        </div>
                    `;

                    // Add event listener to update price when quantity changes
                    const quantityInput = listItem.querySelector('.item-quantity');
                    quantityInput.addEventListener('input', function () {
                        const newQuantity = parseInt(quantityInput.value);
                        cartItem.quantity = newQuantity;
                        const priceSpan = listItem.querySelector('.final-price');
                        priceSpan.textContent = `R$${(cartItem.quantity * cartItem.productPrice).toFixed(2)}`;
                    });

                    productList.appendChild(listItem);
                }
            });
        });
    });
</script>


</body>

</html>