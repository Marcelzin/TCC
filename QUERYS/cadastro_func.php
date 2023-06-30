<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nivel_acesso = 'Funcionario';

    $query = "INSERT INTO usuario (nome, email, senha, nivel_acesso) VALUES ('$nome', '$email', '$senha', '$nivel_acesso')";
    $result = mysqli_query($conexao, $query);

    if ($result) {
        // Delay de 3 segundos antes do redirecionamento
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
                <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: "success",
                        title: "Cadastro realizado",
                        text: "Usuário cadastrado com sucesso!",
                        timer: 3000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = "/TCC/PAGES/login_func.html";
                    });
                }, 3000);
            </script>';
        exit();
    } else {
        // Código de erro
    }
}
?>