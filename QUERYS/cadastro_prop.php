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