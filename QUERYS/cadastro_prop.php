<?php
include_once('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $senha = $_POST['senha'];
    $nivel_acesso = 'Proprietário';

    // Certifique-se de que a variável de sessão 'comercio_id' está definida
    if (!isset($_SESSION['comercio_id'])) {
        // A variável de sessão 'comercio_id' não está definida, trate esse caso como necessário
        echo json_encode(array('status' => 'error', 'message' => 'Erro: comercio_id não definido na sessão.'));
        exit();
    }

    $comercio_id = $_SESSION['comercio_id'];

    // Verifique se o email já existe no banco de dados
    $checkEmailQuery = "SELECT id FROM usuario WHERE email = ? LIMIT 1";
    $stmtCheckEmail = mysqli_prepare($conexao, $checkEmailQuery);

    if ($stmtCheckEmail) {
        mysqli_stmt_bind_param($stmtCheckEmail, "s", $email);
        mysqli_stmt_execute($stmtCheckEmail);
        $result = mysqli_stmt_get_result($stmtCheckEmail);

        if (mysqli_num_rows($result) > 0) {
            $response = [
                'status' => 'error',
                'message' => 'Este email já está em uso. Por favor, escolha outro.'
            ];
            echo json_encode($response);
            exit;
        }

        mysqli_stmt_close($stmtCheckEmail);
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Erro na preparação da declaração para verificar o email: ' . mysqli_error($conexao)
        ];
        echo json_encode($response);
        exit;
    }

    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Validações
    if (empty($nome) || empty($email) || empty($senha)) {
        echo json_encode(array('status' => 'error', 'message' => 'Erro: Preencha todos os campos.'));
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array('status' => 'error', 'message' => 'Erro: Insira um endereço de e-mail válido.'));
        exit();
    }

    // Inserção segura usando declaração preparada
    $stmt = mysqli_prepare($conexao, "INSERT INTO usuario (nome, email, senha, nivel_acesso, comercio_id, status) VALUES (?, ?, ?, ?, ?, 'Ativo')");
    mysqli_stmt_bind_param($stmt, "ssssi", $nome, $email, $senha_hash, $nivel_acesso, $comercio_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(array('status' => 'success', 'message' => 'Usuário cadastrado com sucesso.'));
        exit();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Erro ao cadastrar o usuário.'));
    }

    mysqli_stmt_close($stmt);
}
?>