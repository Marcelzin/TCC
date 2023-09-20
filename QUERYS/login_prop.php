<?php
session_start(); // Iniciar a sessão

include_once('config.php');

$response = array(); // Array para armazenar a resposta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta no banco de dados para verificar se o usuário existe e tem o nível de acesso adequado
    $query = "SELECT * FROM usuario WHERE email = '$email' AND nivel_acesso = 'Proprietário'";
    $result = mysqli_query($conexao, $query);

    if (mysqli_num_rows($result) == 1) {
        // Usuário encontrado com o nível de acesso adequado
        $row = mysqli_fetch_assoc($result);

        // Verificar se a senha fornecida corresponde à senha armazenada no banco de dados
        if (password_verify($senha, $row['senha'])) {
            // Senha correta
            // Armazenar os dados do usuário na sessão
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['usuario_email'] = $row['email'];
            $_SESSION['usuario_nivel_acesso'] = $row['nivel_acesso'];

            // Armazenar o comercio_id na sessão
            $_SESSION['comercio_id'] = $row['comercio_id'];

            $response['status'] = 'success';
            $response['message'] = 'Login bem-sucedido! Redirecionando...';
        } else {
            // Senha incorreta
            $response['status'] = 'error';
            $response['message'] = 'Senha incorreta.';
        }
    } else {
        // Usuário não encontrado com o nível de acesso adequado
        $response['status'] = 'error';
        $response['message'] = 'Email não cadastrado como proprietário.';
    }
} else {
    // Método de requisição inválido (não é POST)
    $response['status'] = 'error';
    $response['message'] = 'Método de requisição inválido.';
}

// Retorna a resposta em formato JSON
echo json_encode($response);
?>
