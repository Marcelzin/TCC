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
    <title>tela de vendas</title>
    <link rel="stylesheet" href="/TCC/CSS/vendas.css">
    <link rel="stylesheet" href="/TCC/CSS/menu.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap');
    </style>
</head>

<body>
    <div class="main">
        <div class="navigation">
            <ul>
                <li class="list">
                    <a href="/TCC/PAGES/home.html">
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="title">Início</span>
                    </a>
                </li>
                <li class="list active">
                    <a href="/TCC/PAGES/vendas.html">
                        <span class="icon"><ion-icon name="grid-outline"></ion-icon></span>
                        <span class="title">Menu</span>
                    </a>
                </li>
                <li class="list">
                    <a href="/TCC/PAGES/cadastro_prod.php">
                        <span class="icon"><ion-icon name="pricetag-outline"></ion-icon></span>
                        <span class="title">Cadastro</span>
                    </a>
                </li>
                <li class="list">
                    <a href="/TCC/PAGES/funcionarios.php">
                        <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                        <span class="title">funcionário</span>
                    </a>
                </li>
                <div class="out">
                    <li class="list-out">
                        <a href="/TCC/index.html">
                            <span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
                            <span class="title">sair</span>
                        </a>
                    </li>
                </div>
            </ul>
        </div>

        <div class="item-menu">
            <section class="search-bar">
                <input style="margin-left:20px" type="search" placeholder="Pesquisar..." id="search">
                <svg id="filter-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-filter">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
            </section>
            <ul class="card-itens">
                <ul class="card-itens">
                    <?php
                    if (isset($_SESSION['comercio_id'])) {
                        $comercio_id = $_SESSION['comercio_id'];

                        $sql = "SELECT * FROM produto WHERE comercio_id = '$comercio_id'";
                        $result = mysqli_query($conexao, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<li class="item">';
                                echo '<section id="left-side">';
                                echo '<div class="top">';
                                echo '<h1>' . $row['nome'] . '</h1>';
                                echo '<h2>' . $row['descricao'] . '</h2>';
                                echo '</div>';
                                echo '<div class="product-price-bottom">';
                                echo '<span class="product-price">R$' . $row['valor_venda'] . '</span>';
                                echo '</div>';
                                echo '</section>';
                                echo '<section id="right-side">';
                                echo '<div class="right-side-top">';
                                echo '<div class="product-image" id="product-image" style="width: 90%; height: 80px; background-color: rgb(190, 190, 190); border-radius: 5px; justify-content: center; display: flex;">';
                                echo '<img src="' . $row["imagem"] . '" alt="Imagem do Produto" style="height: 80px; width: auto">';
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="button-qnt">';
                                echo '<span class="minus">-</span>';
                                echo '<span class="num" contentEditable="true">0</span>';
                                echo '<span class="plus">+</span>';
                                echo '<input type="hidden" class="product-id" value="' . $row['id'] . '">';
                                echo '</div>';
                                echo '</section>';
                                echo '</li>';
                            }
                        } else {
                            echo 'Nenhum produto encontrado para este comércio.';
                        }
                        mysqli_close($conexao);
                    }
                    ?>
                </ul>
        </div>

        <div class="item-list">
            <span class="lista">Lista</span>
            <ul class="product-list">
                <div class="item-list-card">
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
                </div>
                <div class="sale-summary">
                    <div id="total">
                        <h5>Total</h5>
                        <h5>R$00,00</h5>
                    </div>
            </ul>
            <div class="button-pay-form">
                <button id="pay-form"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="#2E3559" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-dollar-sign">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    Dinheiro
                </button>
                <button id="pay-form"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="#2E3559" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-credit-card">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Debito
                </button>
                <button id="pay-form"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="#2E3559" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-credit-card">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Credito
                </button>
                <button id="pay-form">
                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <defs />
                        <g fill="#2E3559" fill-rule="evenodd">
                            <path
                                d="M112.57 391.19c20.056 0 38.928-7.808 53.12-22l76.693-76.692c5.385-5.404 14.765-5.384 20.15 0l76.989 76.989c14.191 14.172 33.045 21.98 53.12 21.98h15.098l-97.138 97.139c-30.326 30.344-79.505 30.344-109.85 0l-97.415-97.416h9.232zm280.068-271.294c-20.056 0-38.929 7.809-53.12 22l-76.97 76.99c-5.551 5.53-14.6 5.568-20.15-.02l-76.711-76.693c-14.192-14.191-33.046-21.999-53.12-21.999h-9.234l97.416-97.416c30.344-30.344 79.523-30.344 109.867 0l97.138 97.138h-15.116z" />
                            <path
                                d="M22.758 200.753l58.024-58.024h31.787c13.84 0 27.384 5.605 37.172 15.394l76.694 76.693c7.178 7.179 16.596 10.768 26.033 10.768 9.417 0 18.854-3.59 26.014-10.75l76.989-76.99c9.787-9.787 23.331-15.393 37.171-15.393h37.654l58.3 58.302c30.343 30.344 30.343 79.523 0 109.867l-58.3 58.303H392.64c-13.84 0-27.384-5.605-37.171-15.394l-76.97-76.99c-13.914-13.894-38.172-13.894-52.066.02l-76.694 76.674c-9.788 9.788-23.332 15.413-37.172 15.413H80.782L22.758 310.62c-30.344-30.345-30.344-79.524 0-109.868" />
                        </g>
                    </svg>
                    Pix
                </button>
            </div>

            <div class="button-close-sale">
                <button id="clean"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-trash-2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                        </path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>Limpar</button>
                <button id="finalize"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-check">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>Finalizar</button>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const plusButtons = document.querySelectorAll('.plus');
        const minusButtons = document.querySelectorAll('.minus');
        const numElements = document.querySelectorAll('.num');
        const productSummary = document.querySelector('.product-list');
        const addToCartButtons = document.querySelectorAll('.add-to-list');
        const totalElement = document.querySelector('#total h5:last-child');

        const resumoProdutos = {};

        plusButtons.forEach((plusButton, index) => {
            plusButton.addEventListener('click', () => {
                let quantidade = parseInt(numElements[index].textContent) || 0;
                quantidade++;

                if (quantidade > 0) {
                    const nomeProduto = document.querySelectorAll('.top h1')[index].textContent;
                    const precoProduto = parseFloat(document.querySelectorAll('.product-price')[index].textContent.replace('R$', '').trim());
                    const imagemProduto = document.querySelectorAll('.product-image img')[index].getAttribute('src');

                    if (resumoProdutos[nomeProduto]) {
                        resumoProdutos[nomeProduto].quantidade = quantidade;
                        resumoProdutos[nomeProduto].precoTotal = quantidade * precoProduto;
                    } else {
                        resumoProdutos[nomeProduto] = {
                            quantidade: quantidade,
                            precoTotal: quantidade * precoProduto,
                            imagem: imagemProduto,
                        };
                    }

                    numElements[index].textContent = quantidade;
                    atualizarResumo();
                }
            });
        });

        minusButtons.forEach((minusButton, index) => {
            minusButton.addEventListener('click', () => {
                let quantidade = parseInt(numElements[index].textContent) || 0;
                quantidade--;

                if (quantidade >= 0) {
                    const nomeProduto = document.querySelectorAll('.top h1')[index].textContent;
                    const precoProduto = parseFloat(document.querySelectorAll('.product-price')[index].textContent.replace('R$', '').trim());
                    const imagemProduto = document.querySelectorAll('.product-image img')[index].getAttribute('src');

                    if (quantidade > 0) {
                        resumoProdutos[nomeProduto].quantidade = quantidade;
                        resumoProdutos[nomeProduto].precoTotal = quantidade * precoProduto;
                    } else {
                        delete resumoProdutos[nomeProduto];
                    }

                    numElements[index].textContent = quantidade;
                    atualizarResumo();
                }
            });
        });

        addToCartButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                const quantidade = parseInt(document.querySelectorAll('.num')[index].textContent);
                if (quantidade > 0) {
                    const nomeProduto = document.querySelectorAll('.top h1')[index].textContent;
                    const precoProduto = parseFloat(document.querySelectorAll('.product-price')[index].textContent.replace('R$', '').trim());
                    const imagemProduto = document.querySelectorAll('.product-image img')[index].getAttribute('src');

                    if (resumoProdutos[nomeProduto]) {
                        resumoProdutos[nomeProduto].quantidade += quantidade;
                        resumoProdutos[nomeProduto].precoTotal += quantidade * precoProduto;
                    } else {
                        resumoProdutos[nomeProduto] = {
                            quantidade: quantidade,
                            precoTotal: quantidade * precoProduto,
                            imagem: imagemProduto,
                        };
                    }

                    atualizarResumo();
                }
            });
        });

        function atualizarResumo() {
            productSummary.innerHTML = '';
            let total = 0;

            for (const nomeProduto in resumoProdutos) {
                const produto = resumoProdutos[nomeProduto];
                const listItem = document.createElement('div');
                listItem.className = 'item-list-card';

                listItem.innerHTML = `
                    <div class="item-list-final-image" style="width: 80px; height: 70px;">
                        <img src="${produto.imagem}" alt="Imagem do Produto" style="width: 80px; height: 70px; background-color: #2e3559; border-radius: 15px;">
                    </div>
                    <div class="item-list-final-description">
                        <section id="name-product">
                            <span class="name-product">${nomeProduto}</span>
                        </section>
                        <section id="item-qtd">
                            <span class="item-qnt">x${produto.quantidade}</span>
                        </section>
                        <span class="final-price">R$${produto.precoTotal.toFixed(2)}</span>
                    </div>
                `;

                productSummary.appendChild(listItem);
                total += produto.precoTotal;
            }

            totalElement.textContent = `R$${total.toFixed(2)}`;
        }
    });
</script>

</body>

</html>