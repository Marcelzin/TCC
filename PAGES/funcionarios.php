<?php
session_start();

if (isset($_SESSION['comercio_id']) && isset($_SESSION['usuario_id'])) {
    include_once('config.php');

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
    <title>funcionarios</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar {
            width: 0px;
        }

        .dataTables_wrapper {
            margin-bottom: 3rem;
        }
    </style>
</head>

<body>
    <main>
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
                            <a class="nav-link" style="color: #fff !important;"
                                href="/TCC/PAGES/cadastro_prod.php">PRODUTOS</a>
                        </li>
                        <?php if ($nivel_acesso === 'Proprietário') { ?>
                            <li class="nav-item active">
                                <a class="nav-link" style="color: #fff !important; font-weight: 600;"
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

        <div class="container">

            <div class="container mt-5">
                <h5>Cadastrar usuário</h5>
                <form id="cadastroUsuarioForm">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="nome" class="form-label" style="font-weight: bold;">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required
                                style="height: 40px; border: solid 1.5px;">
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label" style="font-weight: bold;">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                style="height: 40px; border: solid 1.5px;">
                        </div>
                        <div class="col-md-3">
                            <label for="senha" class="form-label" style="font-weight: bold;">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" required
                                style="height: 40px; border: solid 1.5px;">
                        </div>
                        <div class="col-md-3">
                            <label for="nivelAcesso" class="form-label" style="font-weight: bold;">Nível de
                                Acesso</label>
                            <select class="form-select" id="nivelAcesso" name="nivel_acesso" required
                                style="height: 40px; border: solid 1.5px;">
                                <option value="">Selecione</option>
                                <option value="Funcionário">Funcionário</option>
                                <option value="Proprietário">Proprietário</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-submit"
                                style="width: 100%; height: 40px; border: solid 1.5px; background-color: #2e3559;">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>


            <div class="container mt-5" style="display: flex; flex-direction: column;">
                <h5>Filtrar dados</h5>
                <form id="FiltroUsuarioForm" style="display: flex; flex-direction: column">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="nome_filtra" class="form-label" style="font-weight: bold;">Nome</label>
                            <input type="text" class="form-control" id="nome_filtra" name="nome_filtra"
                                style="height: 40px; border: solid 1.5px;">
                        </div>
                        <div class="col-md-3">
                            <label for="email_filtra" class="form-label" style="font-weight: bold;">Email</label>
                            <input type="email" class="form-control" id="email_filtra" name="email_filtra"
                                style="height: 40px; border: solid 1.5px;">
                        </div>
                        <div class="col-md-3">
                            <label for="status_filtra" class="form-label" style="font-weight: bold;">Status</label>
                            <select class="form-select" id="status_filtra" name="status_filtra"
                                style="height: 40px; border: solid 1.5px;">
                                <option value="">Selecione</option>
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="nivelAcesso_filtra" class="form-label" style="font-weight: bold;">Nível de
                                Acesso</label>
                            <select class="form-select" id="nivelAcesso_filtra" name="nivelAcesso_filtra"
                                style="height: 40px; border: solid 1.5px;">
                                <option value="">Selecione</option>
                                <option value="Funcionário">Funcionário</option>
                                <option value="Proprietário">Proprietário</option>
                            </select>
                        </div>
                    </div>

                </form>
            </div>

            <h5 style="text-align: center">Tabela de Usuários</h5>
            <table class="table table-striped" id="tabela_users" style="text-align: center;">
                <thead>
                    <tr>
                        <th style="text-align: center;">Nome</th>
                        <th style="text-align: center;">Email</th>
                        <th style="text-align: center;">Nível de Acesso</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Inativar</th>
                        <th style="text-align: center;">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Verifique se a variável de sessão comercio_id está definida antes de usar
                    if (isset($_SESSION['comercio_id'])) {
                        $comercio_id = $_SESSION['comercio_id'];
                        $usuario_id = $_SESSION['usuario_id']; // Adicione esta linha para obter o ID do usuário da sessão
                    
                        // Consulta SQL para selecionar todos os dados da tabela "usuario" para o comercio_id atual
                        $sql = "SELECT * FROM usuario WHERE comercio_id = '$comercio_id' AND id <> '$usuario_id'"; // Use o ID do usuário da sessão
                        $result = mysqli_query($conexao, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td style='text-align: center;'>" . $row["nome"] . "</td>";
                                echo "<td style='text-align: center;'>" . $row["email"] . "</td>";
                                echo "<td style='text-align: center;'>" . $row["nivel_acesso"] . "</td>";
                                echo "<td style='text-align: center;'>" . $row["status"] . "</td>";
                                echo '<td style="text-align: center;"><ion-icon name="ban-outline" style="cursor: pointer;" onclick="exibirModalExclusao(' . $row["id"] . ')"></ion-icon></td>';
                                echo '<td style="text-align: center;"><ion-icon name="pencil-outline" style="cursor: pointer;" onclick="abrirModalEdicao(' . $row["id"] . ')"></ion-icon></td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center;'>Nenhum registro encontrado.</td></tr>";
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
                        <h5 class="modal-title" id="excluirModalLabel">Confirmar Inativação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Tem certeza de que deseja inativar este registro?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" onclick="excluirRegistro()">Inativar</button>
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
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status">
                                    <option value="Ativo">Ativo</option>
                                    <option value="Inativo">Inativo</option>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/jquery.inputmask.min.js"></script>
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
                            $("#edit_status").val(funcionario.status);
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
                var status = $("#edit_status").val();

                var dados = {
                    id: id,
                    nome: nome,
                    email: email,
                    nivel_acesso: nivelAcesso,
                    status: status
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
                // Função para validar e enviar o formulário de cadastro
                function validarEEnviarCadastro() {
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
                    } else if (senha2.length < 8) {
                        // Verificar se a senha tem menos de 8 caracteres
                        $('#senha').css('border', '1px solid red');
                        Swal.fire({
                            icon: 'error',
                            title: 'Senha curta!',
                            text: 'A senha deve conter pelo menos 8 caracteres.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Entendi'
                        });
                    } else {
                        // Chamar a função de cadastro
                        cadastraFuncionario();
                    }
                }

                // Lidar com o clique no botão de envio do formulário de cadastro
                $('#cadastroUsuarioForm .btn-submit').on('click', function (e) {
                    e.preventDefault();
                    validarEEnviarCadastro();
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
                                // Exibir uma mensagem de sucesso
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


        <script>
            $(document).ready(function () {
                // Adicione evento oninput para cada campo de entrada do formulário de filtragem
                $("#nome_filtra, #email_filtra, #status_filtra, #nivelAcesso_filtra").on('input', function () {
                    filtraUsuario(); // Chama a função de filtro quando o usuário insere qualquer valor
                });

                $("#FiltroUsuarioForm").submit(function (event) {
                    event.preventDefault(); // Impede o envio do formulário padrão
                    filtraUsuario(); // Chama a função de filtro quando o formulário é enviado
                });

                // Defina a função de filtro para ser chamada no carregamento da página
                filtraUsuario();
            });
        </script>

        <script>
            function filtraUsuario() {
                var nome_filtra = $("#nome_filtra").val();
                var email_filtra = $("#email_filtra").val();
                var status_filtra = $("#status_filtra").val();
                var nivelAcesso_filtra = $("#nivelAcesso_filtra").val();

                // Dentro da função que aciona o filtro no seu JavaScript
                $.ajax({
                    method: "POST",
                    url: '/TCC/QUERYS/filtraFunc.php',
                    data: {
                        nome_filtra: nome_filtra,
                        email_filtra: email_filtra,
                        status_filtra: status_filtra,
                        nivelAcesso_filtra: nivelAcesso_filtra
                    },
                    success: function (response) {
                        // Atualize a tabela com os resultados do filtro
                        $("#tabela_users tbody").html(response); // Aqui, atualizamos apenas o corpo da tabela
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            }
        </script>

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
            $(document).ready(function () {
                $('#nome, #nome_filtra').inputmask({ regex: "[A-Za-z]", placeholder: "" });
            });
        </script>
    </main>

</body>

</html>