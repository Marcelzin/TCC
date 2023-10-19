<?php
include_once('config.php');
session_start(); // Certifique-se de iniciar a sessão

header('Content-Type: application/json'); // Definir o cabeçalho para resposta JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome2'];
    $email = $_POST['email2'];
    $senha = $_POST['senha2'];
    $nivel_acesso = $_POST['nivel_acesso2'];

    // Recupere o valor da coluna "comercio_id" da sessão
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

    // Criptografe a senha usando password_hash()
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Usando declarações preparadas para evitar injeção de SQL
    $query = "INSERT INTO usuario (nome, email, senha, nivel_acesso, comercio_id, status) VALUES (?, ?, ?, ?, ?, 'Ativo')";
    $stmt = mysqli_prepare($conexao, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssi", $nome, $email, $senha_hash, $nivel_acesso, $comercio_id);

        if (mysqli_stmt_execute($stmt)) {
            $response = [
                'status' => 'success',
                'message' => 'Funcionário cadastrado com sucesso!'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao cadastrar o funcionário: ' . mysqli_error($conexao)
            ];
        }

        mysqli_stmt_close($stmt);
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Erro na preparação da declaração: ' . mysqli_error($conexao)
        ];
    }

    echo json_encode($response);
}
?>