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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">    <link href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
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
                <a href="/TCC/PAGES/vendas.html">
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
                <a href="/TCC/PAGES/funcionarios.html">
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

    <div class="card-cadastro">
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

<table id="tb_produtos" name="tb_produtos">
    <thead style="background-color: #0a2654; color: #FFF">
        <tr>
            <th>ID</th>
            <th>Descrição do Produto</th>
            <th>Nome do Produto</th>
            <th>Valor de Produção</th>
            <th>Preço</th>
            <th>Excluir</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include_once('config.php');

        // Consulta SQL para selecionar todos os dados da tabela "produto"
        $sql = "SELECT * FROM produto";
        $result = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["descricao"] . "</td>";
                echo "<td>" . $row["nome"] . "</td>";
                echo "<td>R$" . $row["valor_fabrica"] . "</td>";
                echo "<td>R$" . $row["valor_venda"] . "</td>";
                echo '<td><ion-icon name="trash-outline" style="cursor: pointer;" onclick="exibirModalExclusao(' . $row["id"] . ')"></ion-icon></td>';
                echo '<td><ion-icon name="pencil-outline" style="cursor: pointer;" onclick="abrirModalEdicao(' . $row["id"] . ')"></ion-icon></td>';                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Nenhum registro encontrado.</td></tr>";
        }

        mysqli_close($conexao);
        ?>
    </tbody>
</table>
<div class="modal" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
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
<div class="modal" id="excluirModal" tabindex="-1" role="dialog" aria-labelledby="excluirModalLabel" aria-hidden="true">
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
        reader.onload = function(e) {
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
    
    <!-- Modifique a função exibirModalExclusao para definir o atributo data-id -->
<script>
    // Função para abrir a modal de exclusão
    function exibirModalExclusao(id) {
        // Defina o atributo data-id no elemento da modal
        $("#excluirModal").attr("data-id", id);
        $("#excluirModal").modal("show");
    }

    // Função para excluir o registro após a confirmação
    function excluirRegistro() {
        // Obtenha o id do atributo data-id do elemento da modal
        var id = $("#excluirModal").data("id");

        // Realize uma chamada AJAX para excluir o registro
        $.ajax({
            method: "POST",
            url: '/TCC/QUERYS/delete_produto.php',
            data: { id: id },
            success: function (retorno) {
                try {
                    var response = JSON.parse(retorno);
                    if (response.status === 'success') {
                        // Produto excluído com sucesso, faça algo aqui (por exemplo, recarregar a tabela)
                        $("#excluirModal").modal("hide"); // Fechar a modal de confirmação
                        // Atualizar a página após a exclusão bem-sucedida
                        location.reload();
                    } else {
                        // Trate o erro de exclusão aqui (por exemplo, exibir uma mensagem de erro)
                        console.error(response.message);
                    }
                } catch (e) {
                    console.error('Erro ao analisar resposta JSON:', e);
                    // Tratar o erro de análise JSON aqui (por exemplo, exibir uma mensagem de erro)
                }
            },
            error: function (error) {
                console.error(error);
                // Tratar o erro aqui (por exemplo, mostrar uma mensagem de erro)
            }
        });
    }
</script>

<script>
    $(document).ready(function () {
        $('#tb_produtos').DataTable({
            paging: true, // Habilita a paginação
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json' // Configuração do idioma em Português (Brasil)
            }
        });
    });
</script>
    
<script>
    // Função para abrir a modal de edição e preencher os campos
    function abrirModalEdicao(id) {
        // Fazer uma chamada AJAX para buscar os dados do produto pelo ID
        $.ajax({
            method: "GET",
            url: '/TCC/QUERYS/get_produto.php?id=' + id,
            success: function (retorno) {
                var produto = JSON.parse(retorno);
                if (produto) {
                    // Preencher os campos da modal com os valores do produto
                    $("#produto_id").val(produto.id);
                    $("#edit_descricao").val(produto.descricao);
                    $("#edit_nome").val(produto.nome);
                    $("#edit_valor_fabrica").val(produto.valor_fabrica);
                    $("#edit_valor_venda").val(produto.valor_venda);
                    // Abrir a modal de edição
                    $("#editarModal").modal("show");
                }
            },
            error: function (error) {
                console.error(error);
                // Tratar o erro aqui (por exemplo, mostrar uma mensagem de erro)
            }
        });
    }

    // Função para salvar as alterações na modal de edição
    function salvarEdicao() {
        // Obter os valores dos campos da modal de edição
        var id = $("#produto_id").val();
        var descricao = $("#edit_descricao").val();
        var nome = $("#edit_nome").val();
        var valorFabrica = $("#edit_valor_fabrica").val();
        var valorVenda = $("#edit_valor_venda").val();

        // Criar um objeto com os dados a serem enviados
        var dados = {
            id: id,
            descricao: descricao,
            nome: nome,
            valor_fabrica: valorFabrica,
            valor_venda: valorVenda
        };

        // Realizar uma chamada AJAX para atualizar os dados do produto
        $.ajax({
            method: "POST",
            url: '/TCC/QUERYS/update_produto.php',
            data: dados,
            success: function (retorno) {
                var response = JSON.parse(retorno);
                if (response.status === 'success') {
                    // Produto atualizado com sucesso, faça algo aqui (por exemplo, recarregar a tabela)
                    $("#editarModal").modal("hide"); // Fechar a modal
                    // Atualizar a página após a atualização bem-sucedida
                    location.reload();
                } else {
                    // Tratar o erro de atualização aqui (por exemplo, exibir uma mensagem de erro)
                    console.error(response.message);
                }
            },
            error: function (error) {
                console.error(error);
                // Tratar o erro aqui (por exemplo, mostrar uma mensagem de erro)
            }
        });
    }
</script>

<script>
    $(document).ready(function () {
        // Máscara para o campo "Valor de produção"
        $('#valorfab').mask('999999999.99', { reverse: true });

        // Máscara para o campo "Preço"
        $('#price').mask('999999999.99', { reverse: true });

        // Função para validar e enviar o formulário
        function validarEEnviar() {
            var nomeProduto = $('#name').val();
            var valorFabricacao = $('#valorfab').val();
            var preco = $('#price').val();

            // Validar se os campos obrigatórios estão preenchidos
            if (nomeProduto === '' || valorFabricacao === '' || preco === '') {
                // Bordas vermelhas nos campos vazios
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

                // Alerta usando SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Campos em vermelho!',
                    text: 'Preencha os campos em vermelho.',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Entendi'
                });
            } else {
                // Se os campos estão preenchidos, envie o formulário
                cadastra_prod();
            }
        }

        // Associar a função de validação e envio ao clique do botão "Pronto"
        $('.btn-submit').on('click', function (e) {
            e.preventDefault(); // Impede o envio padrão do formulário
            validarEEnviar();
        });

        // Função para cadastrar o produto
        function cadastra_prod() {
            // Obtenha os valores dos campos do formulário de produto
            var nome = $("#name").val();
            var valorFabrica = $("#valorfab").val();
            var valorVenda = $("#price").val();
            var descricao = $("#descricion").val();
            
            // Crie um objeto com os dados a serem enviados
            var dados = {
                nome: nome,
                valor_fabrica: valorFabrica,
                valor_venda: valorVenda,
                descricao: descricao
            };
    
            // Realize uma chamada AJAX para o arquivo cadastro_prod.php
            $.ajax({
                method: "POST",
                url: '/TCC/QUERYS/cadastro_prod.php',
                data: dados,
                success: function (retorno) {
                    var response = JSON.parse(retorno);
                    if (response.status === 'success') {
                        // Produto cadastrado com sucesso, faça algo aqui (por exemplo, redirecionar ou exibir uma mensagem de sucesso)
                        Swal.fire({
                            icon: 'success',
                            title: 'Produto cadastrado com sucesso!',
                            text: response.message,
                            timer: 2000, // Redirecionar após 2 segundos
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer);
                                toast.addEventListener('mouseleave', Swal.resumeTimer);
                            }
                        }).then(function() {
                            // Atualizar a página após o Swal ser fechado
                            location.reload();
                        });

                    } else {
                        // Trate o erro de cadastro aqui (por exemplo, exibir uma mensagem de erro)
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao cadastrar o produto!',
                            text: response.message
                        });
                    }
                },
                error: function (error) {
                    // Ação a ser tomada em caso de erro na requisição AJAX
                    console.error(error); // Exiba o erro no console para fins de depuração
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