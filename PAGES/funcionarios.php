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
    <title>funcionarios</title>
    <link rel="stylesheet" href="/TCC/CSS/funcionarios.css">
    <link rel="stylesheet" href="/TCC/CSS/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

</head>

<body>
    <main>
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
                <li class="list">
                    <a href="/TCC/PAGES/cadastro_prod.php">
                        <span class="icon"><ion-icon name="pricetag-outline"></ion-icon></span>
                        <span class="title">Cadastro</span>
                    </a>
                </li>
                <li class="list active">
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



        <div class="container">

            <div class="container mt-5">
                <h2>Cadastro de Usuário</h2>
                <form id="cadastroUsuarioForm">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                        <div class="col-md-3">
                            <label for="nivelAcesso" class="form-label">Nível de Acesso</label>
                            <select class="form-select" id="nivelAcesso" name="nivel_acesso" required>
                                <option value="Funcionário">Funcionário</option>
                                <option value="Proprietário">Proprietário</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-submit">Cadastrar</button>
                </form>

            </div>
            <h2 style="margin-top: 50px">Tabela de Usuários</h2>
            <table class="table" id="tabela_users">
                <thead>
                    <tr>
                        <!-- <th>ID</th> -->
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Nível de Acesso</th>
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
                        $sql = "SELECT * FROM usuario WHERE comercio_id = '$comercio_id'";
                        $result = mysqli_query($conexao, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                /* echo "<td>" . $row["id"] . "</td>"; */
                                echo "<td>" . $row["nome"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["nivel_acesso"] . "</td>";
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
        </div>

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

        <div class="modal" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarModalLabel">Editar Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form_edicao_usuario">
                            <input type="hidden" id="usuario_id">
                            <div class="mb-3">
                                <label for="edit_nome" class="form-label">Nome do usuário</label>
                                <input type="text" class="form-control" id="edit_nome">
                            </div>
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email do usuário</label>
                                <input type="email" class="form-control" id="edit_email">
                            </div>
                            <div class="mb-3">
                                <label for="edit_nivel_acesso" class="form-label">Nível de Acesso</label>
                                <select class="form-select" id="edit_nivel_acesso">
                                    <option value="Funcionário">Funcionário</option>
                                    <option value="Proprietário">Proprietário</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="salvarEdicaoUsuario()">Salvar</button>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
        <script>
            function exibirModalExclusao(id) {
                $("#excluirModal").attr("data-id", id);
                $("#excluirModal").modal("show");
            }

            function excluirRegistro() {
                var id = $("#excluirModal").data("id");

                $.ajax({
                    method: "POST",
                    url: '/TCC/QUERYS/delete_funcionario.php',
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
            function abrirModalEdicao(id) {
                $.ajax({
                    method: "GET",
                    url: '/TCC/QUERYS/get_funcionario.php?id=' + id,
                    success: function (retorno) {
                        var funcionario = JSON.parse(retorno);
                        if (funcionario) {
                            $("#usuario_id").val(funcionario.id);
                            $("#edit_nome").val(funcionario.nome);
                            $("#edit_email").val(funcionario.email);
                            $("#edit_nivel_acesso").val(funcionario.nivel_acesso);
                            $("#editarModal").modal("show");
                        }
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            }

            function salvarEdicaoUsuario() {
                var id = $("#usuario_id").val();
                var nome = $("#edit_nome").val();
                var email = $("#edit_email").val();
                var nivelAcesso = $("#edit_nivel_acesso").val();

                var dados = {
                    id: id,
                    nome: nome,
                    email: email,
                    nivel_acesso: nivelAcesso
                };

                $.ajax({
                    method: "POST",
                    url: '/TCC/QUERYS/update_funcionario.php', // Defina o caminho correto para o script de atualização
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
                $('#tabela_users').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese.json"
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function () {
                // Função para validar e enviar o formulário
                function validarEEnviar() {
                    var nome2 = $('#nome').val();
                    var email2 = $('#email').val();
                    var senha2 = $('#senha').val();
                    var nivelAcesso2 = $('#nivelAcesso').val();

                    // Resetar estilos de borda para campos válidos
                    $('#nome, #email, #senha').css('border', '1px solid #ccc');

                    if (nome2 === '' || email2 === '' || senha2 === '') {
                        // Realçar os campos em vermelho
                        if (nome2 === '') {
                            $('#nome').css('border', '1px solid red');
                        }

                        if (email2 === '') {
                            $('#email').css('border', '1px solid red');
                        }

                        if (senha2 === '') {
                            $('#senha').css('border', '1px solid red');
                        }

                        // Exibir uma mensagem de erro
                        Swal.fire({
                            icon: 'error',
                            title: 'Campos em vermelho!',
                            text: 'Preencha os campos em vermelho.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Entendi'
                        });
                    } else {
                        // Chamar a função de cadastro
                        cadastraFuncionario();
                    }
                }

                // Lidar com o clique no botão de envio
                $('.btn-submit').on('click', function (e) {
                    e.preventDefault();
                    validarEEnviar();
                });

                // Função para cadastrar o funcionário via AJAX
                function cadastraFuncionario() {
                    var nome2 = $("#nome").val();
                    var email2 = $("#email").val();
                    var senha2 = $("#senha").val();
                    var nivelAcesso2 = $("#nivelAcesso").val();

                    $.ajax({
                        method: "POST",
                        url: '/TCC/QUERYS/cadastro_funcionario.php', // Certifique-se de fornecer o caminho correto para o seu arquivo PHP
                        data: {
                            nome2: nome2,
                            email2: email2,
                            senha2: senha2,
                            nivel_acesso2: nivelAcesso2
                        },

                        success: function (response) {
                            // Manipular a resposta do servidor aqui
                            console.log(response);

                            if (response.status === 'success') {
                                // Redirecionar ou exibir uma mensagem de sucesso
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: 'Funcionário cadastrado com sucesso.',
                                    confirmButtonColor: '#4caf50',
                                    confirmButtonText: 'OK'
                                }).then(function () {
                                    // Recarregar a página após a confirmação
                                    location.reload();
                                });
                            } else {
                                // Exibir uma mensagem de erro ao usuário
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: response.message,
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'Entendi'
                                });
                            }
                        },
                        error: function (error) {
                            console.error(error);
                            // Exibir uma mensagem de erro ao usuário, se necessário
                        }
                    });
                }
            });

        </script>

    </main>

</body>

</html>