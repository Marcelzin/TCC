<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta no banco de dados para verificar se o usuário existe e tem o nível de acesso adequado
    $query = "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha' AND nivel_acesso = 'Proprietário'";
    $result = mysqli_query($conexao, $query);

    if (mysqli_num_rows($result) == 1) {
        // Usuário válido, redireciona para a página principal ou faz outras ações necessárias
        header("Location: /TCC/home.html");
        exit();
    } else {
        // Usuário inválido, exibe uma mensagem de erro ou redireciona para a página de login novamente
        echo "Email, senha ou nível de acesso inválidos";
    }
}
?>
