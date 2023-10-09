<?php
// Verifique se os dados foram enviados através do método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Certifique-se de incluir o arquivo de configuração do banco de dados
    include_once('config.php');

    // Obtenha os dados do funcionário a ser atualizado a partir do corpo da solicitação POST
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $nivelAcesso = $_POST['nivel_acesso'];
    $status = $_POST['status'];

    // Consulta SQL para atualizar os dados do funcionário
    $sql = "UPDATE usuario SET nome = '$nome', email = '$email', nivel_acesso = '$nivelAcesso', status = '$status' WHERE id = $id";

    if (mysqli_query($conexao, $sql)) {
        // A atualização foi bem-sucedida
        echo json_encode(array('status' => 'success', 'message' => 'Funcionário atualizado com sucesso.'));
    } else {
        // Trate erros na execução da consulta SQL, se necessário
        echo json_encode(array('status' => 'error', 'message' => 'Erro na atualização do funcionário.'));
    }

    // Feche a conexão com o banco de dados
    mysqli_close($conexao);
} else {
    // Se os dados não foram enviados via POST, retorne um JSON de erro
    echo json_encode(array('status' => 'error', 'message' => 'Requisição inválida.'));
}
?>