<?php
session_start(); // Iniciar a sessão

include_once('config.php');

$response = array(); // Array para armazenar a resposta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta no banco de dados para verificar se o usuário existe
    $query = "SELECT * FROM usuario WHERE email = '$email'";
    $result = mysqli_query($conexao, $query);

    if (mysqli_num_rows($result) == 1) {
        // Usuário encontrado
        $row = mysqli_fetch_assoc($result);

        // Verificar se o status do usuário é 'Ativo'
        if ($row['status'] == 'Ativo') {
            // Verificar se o nível de acesso é 'Funcionario'
            if ($row['nivel_acesso'] == 'Funcionário') {
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
                // Nível de acesso não é 'Funcionario'
                $response['status'] = 'error';
                $response['message'] = 'Nível de acesso inválido.';
            }
        } else {
            // Usuário não está ativo
            $response['status'] = 'error';
            $response['message'] = 'Usuário não está ativo. Entre em contato com o suporte.';
        }
    } else {
        // Usuário não encontrado
        $response['status'] = 'error';
        $response['message'] = 'Email não cadastrado.';
    }
} else {
    // Método de requisição inválido (não é POST)
    $response['status'] = 'error';
    $response['message'] = 'Método de requisição inválido.';
}

// Retorna a resposta em formato JSON
echo json_encode($response);

?>
