<?php
// Certifique-se de incluir o arquivo de configuração do banco de dados
include_once('config.php');

// Verifique se os dados foram enviados via POST e se o ID é um número válido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    // Obtenha o ID do produto a ser atualizado a partir do corpo da solicitação POST
    $id = intval($_POST['id']);

    // Defina o novo valor para o campo 'status' como 'Inativo'
    $novo_status = 'Inativo';

    // Prepare a declaração SQL de atualização
    $sql_update_produto = "UPDATE produto SET status = ? WHERE id = ?";
    $stmt_update_produto = mysqli_prepare($conexao, $sql_update_produto);

    if ($stmt_update_produto) {
        mysqli_stmt_bind_param($stmt_update_produto, "si", $novo_status, $id);

        if (mysqli_stmt_execute($stmt_update_produto)) {
            // A atualização do status do produto foi bem-sucedida
            echo json_encode(array('status' => 'success', 'message' => 'Status do produto atualizado para "Inativo" com sucesso.'));
        } else {
            // Trate erros na atualização do status do produto
            echo json_encode(array('status' => 'error', 'message' => 'Erro ao atualizar o status do produto.'));
        }

        mysqli_stmt_close($stmt_update_produto);
    } else {
        // Trate erros na preparação da atualização do status do produto
        echo json_encode(array('status' => 'error', 'message' => 'Erro ao preparar a atualização do status do produto.'));
    }
} else {
    // Se os dados não foram enviados via POST ou o ID não é válido, retorne um JSON de erro
    echo json_encode(array('status' => 'error', 'message' => 'Requisição inválida.'));
}

// Feche a conexão com o banco de dados
mysqli_close($conexao);
?>