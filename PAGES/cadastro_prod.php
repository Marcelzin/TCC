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
    <title>cadastro de item</title>
    <link rel="stylesheet" href="/TCC/CSS/cadastro_prod.css">
    <link rel="stylesheet" href="/TCC/CSS/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <style>
        .dataTables_length {
            width: auto;
            position: absolute;
        }

        #tb_produtos_filter {
            float: right;
        }
    </style>
</head>

<body style="flex-direction: column">

    <div class="navigation">
        <ul>
            <li class="list">
                <a href="/TCC/PAGES/home.html">
                    <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                    <span class="title">Início</span>
                </a>
            </li>
            <li class="list">
                <a href="/TCC/PAGES/vendas.php">
                    <span class="icon"><ion-icon name="grid-outline"></ion-icon></span>
                    <span class="title">Menu</span>
                </a>
            </li>
            <li class="list active">
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

    <div class="card-cadastro" style="margin-bottom: 50px">
        <div class="left">
            <form id="form_cadastro_prod" name="form_cadastro_prod" method="POST">
                <div>
                    <label for="descricion">Descrição do produto</label>
                    <input type="text" placeholder="Digite a descrição do produto aqui..." id="descricion">
                </div>
                <div>
                    <label for="name">Nome do produto</label>
                    <input type="text" placeholder="Digite o nome do produto aqui..." id="name">
                </div>

                <div class="valor-type">

                    <div class="valor">

                        <label for="valorfab">Valor de produção</label>
                        <input type="text" placeholder="Valor de fabrica" id="valorfab">

                    </div>
                    <div class="valor">

                        <label for="price">Preço</label>
                        <input type="text" placeholder="Digite o preço aqui..." id="price" class="price">
                    </div>

                </div>
                <div class="buttons-submit">
                    <button type="reset" class="reset"><ion-icon name="trash-outline" class="icon"></ion-icon>Limpar
                    </button>

                    <button class="btn-submit" onclick="cadastra_prod()">Pronto</button>
                </div>


        </div>

        <div class="right">

            <h1>Adicionar produtos</h1>
            <div class="custom-file-upload">
                <input type="file" id="image" name="image" accept="image/*" onchange="exibirPreviewImagem(this);">
                <label for="image" id="image-label">
                    <img id="imagem-preview" src="#" alt="Pré-visualização da imagem">
                    <span>Selecione uma imagem</span>
                </label>


            </div>

            <section class="right-input">
            </section>

        </div>


        </form>
    </div>

    </div>

    <!--Botão de filtragem-->

    <table id="tb_produtos" name="tb_produtos" class="table table-striped table-bordered" style="margin-left: 80px; width: 93vw">
        <thead class="bg-primary text-white">
            <tr>
                <th>Imagem do Produto</th>
                <th>Nome do Produto</th>
                <th>Descrição do Produto</th>
                <th>Valor de Produção</th>
                <th>Preço</th>
                <th>Excluir</th>
                <th>Editar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verifique se a variável de sessão comercio_id está definida antes de usar
            if (isset($_SESSION['comercio_id'])) {
                $comercio_id = $_SESSION['comercio_id'];

                // Consulta SQL para selecionar todos os dados da tabela "produto" para o comercio_id atual
                $sql = "SELECT * FROM produto WHERE comercio_id = '$comercio_id'";
                $result = mysqli_query($conexao, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        /* echo "<td>" . $row["id"] . "</td>"; */
                        echo '<td><img src="' . $row["imagem"] . '" alt="Imagem do Produto" width="5%"></td>';
                        echo "<td>" . $row["nome"] . "</td>";
                        echo "<td>" . $row["descricao"] . "</td>";
                        echo "<td>R$" . $row["valor_fabrica"] . "</td>";
                        echo "<td>R$" . $row["valor_venda"] . "</td>";
                        echo '<td><ion-icon name="trash-outline" style="cursor: pointer;" onclick="exibirModalExclusao(' . $row["id"] . ')"></ion-icon></td>';
                        echo '<td><ion-icon name="pencil-outline" style="cursor: pointer;" onclick="abrirModalEdicao(' . $row["id"] . ')"></ion-icon></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Nenhum registro encontrado.</td></tr>";
                }

                mysqli_close($conexao);
            } else {
                echo "A variável de sessão comercio_id não está definida.";
            }
            ?>
        </tbody>
    </table>

    <div class="modal" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form id="form_edicao_prod">
                        <input type="hidden" id="produto_id">
                        <div class="mb-3">
                            <label for="edit_descricao" class="form-label">Descrição do produto</label>
                            <input type="text" class="form-control" id="edit_descricao">
                        </div>
                        <div class="mb-3">
                            <label for="edit_nome" class="form-label">Nome do produto</label>
                            <input type="text" class="form-control" id="edit_nome">
                        </div>
                        <div class="mb-3">
                            <label for="edit_valor_fabrica" class="form-label">Valor de produção</label>
                            <input type="text" class="form-control" id="edit_valor_fabrica">
                        </div>
                        <div class="mb-3">
                            <label for="edit_valor_venda" class="form-label">Preço</label>
                            <input type="text" class="form-control" id="edit_valor_venda">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="salvarEdicao()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Crie a modal de confirmação -->
    <div class="modal" id="excluirModal" tabindex="-1" role="dialog" aria-labelledby="excluirModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="excluirModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Tem certeza de que deseja excluir este registro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="excluirRegistro()">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        function exibirPreviewImagem(input) {
            var imagemPreview = document.getElementById('imagem-preview');
            var label = document.getElementById('image-label');
            var textoSelecionar = label.querySelector('span');

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    imagemPreview.src = e.target.result;
                    imagemPreview.style.display = 'block';
                    textoSelecionar.style.display = 'none'; // Oculta o texto
                    label.style.border = 'none';
                    label.style.background = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                imagemPreview.src = '';
                imagemPreview.style.display = 'none';
                textoSelecionar.style.display = 'block'; // Exibe o texto
                label.style.border = '2px dashed #ccc';
                label.style.background = 'white';
            }
        }
    </script>

    <script>
        function exibirModalExclusao(id) {
            $("#excluirModal").attr("data-id", id);
            $("#excluirModal").modal("show");
        }

        function excluirRegistro() {
            var id = $("#excluirModal").data("id");

            $.ajax({
                method: "POST",
                url: '/TCC/QUERYS/delete_produto.php',
                data: { id: id },
                success: function (retorno) {
                    try {
                        var response = JSON.parse(retorno);
                        if (response.status === 'success') {
                            $("#excluirModal").modal("hide");
                            location.reload();
                        } else {
                            console.error(response.message);
                        }
                    } catch (e) {
                        console.error('Erro ao analisar resposta JSON:', e);
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            $('#tb_produtos').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese.json"
                }
            });
        });
    </script>

    <script>
        function abrirModalEdicao(id) {
            $.ajax({
                method: "GET",
                url: '/TCC/QUERYS/get_produto.php?id=' + id,
                success: function (retorno) {
                    var produto = JSON.parse(retorno);
                    if (produto) {
                        $("#produto_id").val(produto.id);
                        $("#edit_descricao").val(produto.descricao);
                        $("#edit_nome").val(produto.nome);
                        $("#edit_valor_fabrica").val(produto.valor_fabrica);
                        $("#edit_valor_venda").val(produto.valor_venda);
                        $("#editarModal").modal("show");
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }

        function salvarEdicao() {
            var id = $("#produto_id").val();
            var descricao = $("#edit_descricao").val();
            var nome = $("#edit_nome").val();
            var valorFabrica = $("#edit_valor_fabrica").val();
            var valorVenda = $("#edit_valor_venda").val();

            var dados = {
                id: id,
                descricao: descricao,
                nome: nome,
                valor_fabrica: valorFabrica,
                valor_venda: valorVenda
            };

            $.ajax({
                method: "POST",
                url: '/TCC/QUERYS/update_produto.php',
                data: dados,
                success: function (retorno) {
                    var response = JSON.parse(retorno);
                    if (response.status === 'success') {
                        $("#editarModal").modal("hide");
                        location.reload();
                    } else {
                        console.error(response.message);
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            $('#valorfab').mask('999999999.99', { reverse: true });

            $('#price').mask('999999999.99', { reverse: true });

            function validarEEnviar() {
                var nomeProduto = $('#name').val();
                var valorFabricacao = $('#valorfab').val();
                var preco = $('#price').val();

                if (nomeProduto === '' || valorFabricacao === '' || preco === '') {
                    if (nomeProduto === '') {
                        $('#name').css('border', '1px solid red');
                    } else {
                        $('#name').css('border', '1px solid #ccc');
                    }

                    if (valorFabricacao === '') {
                        $('#valorfab').css('border', '1px solid red');
                    } else {
                        $('#valorfab').css('border', '1px solid #ccc');
                    }

                    if (preco === '') {
                        $('#price').css('border', '1px solid red');
                    } else {
                        $('#price').css('border', '1px solid #ccc');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Campos em vermelho!',
                        text: 'Preencha os campos em vermelho.',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Entendi'
                    });
                } else {
                    cadastra_prod();
                }
            }

            $('.btn-submit').on('click', function (e) {
                e.preventDefault();
                validarEEnviar();
            });

            function cadastra_prod() {
                var nome = $("#name").val();
                var valorFabrica = $("#valorfab").val();
                var valorVenda = $("#price").val();
                var descricao = $("#descricion").val();
                var imagem = document.getElementById("image").files[0]; // Obtenha o arquivo de imagem

                // Valide se uma imagem foi selecionada
                if (!imagem) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao cadastrar o produto!',
                        text: 'Selecione uma imagem para o produto.'
                    });
                    return;
                }

                var dados = new FormData();
                dados.append("nome", nome);
                dados.append("valor_fabrica", valorFabrica);
                dados.append("valor_venda", valorVenda);
                dados.append("descricao", descricao);
                dados.append("imagem", imagem);

                $.ajax({
                    method: "POST",
                    url: '/TCC/QUERYS/cadastro_prod.php',
                    data: dados,
                    contentType: false, // Não defina o tipo de conteúdo, deixe o jQuery cuidar disso
                    processData: false, // Não processe os dados, deixe o jQuery cuidar disso
                    success: function (retorno) {
                        var response = JSON.parse(retorno);
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Produto cadastrado com sucesso!',
                                text: response.message,
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer);
                                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                                }
                            }).then(function () {
                                location.reload();
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro ao cadastrar o produto!',
                                text: response.message
                            });
                        }
                    },
                    error: function (error) {
                        console.error(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro na requisição AJAX',
                            text: 'Ocorreu um erro ao enviar a solicitação. Verifique a conexão ou tente novamente mais tarde.'
                        });
                    }
                });
            }

        });
    </script>

</body>

</html>