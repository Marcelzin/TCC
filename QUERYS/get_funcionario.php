<?php
// Primeiro, verifique se o ID do funcionário foi fornecido na solicitação
if (isset($_GET['id'])) {
    $funcionario_id = $_GET['id'];

    // Faça a conexão com o banco de dados
    include_once('config.php'); // Certifique-se de incluir o arquivo de configuração do banco de dados

    // Consulta SQL para buscar os dados do funcionário pelo ID
    $sql = "SELECT * FROM usuario WHERE id = $funcionario_id";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        // Verifique se encontrou um registro
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Crie um array associativo com os dados do funcionário
            $funcionario = array(
                'id' => $row['id'],
                'nome' => $row['nome'],
                'email' => $row['email'],
                'nivel_acesso' => $row['nivel_acesso'],
                'status' => $row['status']
            );

            // Converta o array em formato JSON e retorne como resposta
            echo json_encode($funcionario);
        } else {
            // Caso nenhum registro seja encontrado, retorne um JSON vazio ou uma mensagem de erro, conforme necessário
            echo json_encode(array('status' => 'error', 'message' => 'Funcionário não encontrado.'));
        }
    } else {
        // Trate erros na execução da consulta SQL, se necessário
        echo json_encode(array('status' => 'error', 'message' => 'Erro na consulta SQL.'));
    }

    // Feche a conexão com o banco de dados
    mysqli_close($conexao);
} else {
    // Se o ID do funcionário não foi fornecido, retorne um JSON de erro
    echo json_encode(array('status' => 'error', 'message' => 'ID do funcionário não fornecido.'));
}
?>