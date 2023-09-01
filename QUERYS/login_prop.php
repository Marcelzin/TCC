<?php
    session_start(); // Iniciar a sessão

    include_once('config.php');

    $response = array(); // Array para armazenar a resposta

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Consulta no banco de dados para verificar se o usuário existe e tem o nível de acesso adequado e a senha está correta
        $query = "SELECT * FROM usuario WHERE email = '$email' AND nivel_acesso = 'Proprietário' AND senha = '$senha'";
        $result = mysqli_query($conexao, $query);

        if (mysqli_num_rows($result) == 1) {
            // Usuário encontrado, senha correta
            $row = mysqli_fetch_assoc($result);

            // Armazenar os dados do usuário na sessão
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['usuario_email'] = $row['email'];
            $_SESSION['usuario_nivel_acesso'] = $row['nivel_acesso'];

            $response['status'] = 'success';
            $response['message'] = 'Login bem-sucedido! Redirecionando...';
        } else {
            // Usuário não encontrado ou senha incorreta
            $response['status'] = 'error';
            $response['message'] = 'Email não cadastrado como proprietário ou senha incorreta.';
        }
    } else {
        // Método de requisição inválido (não é POST)
        $response['status'] = 'error';
        $response['message'] = 'Método de requisição inválido.';
    }

    // Retorna a resposta em formato JSON
    echo json_encode($response);
?>
