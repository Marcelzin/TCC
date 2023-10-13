<?php
// Certifique-se de incluir o arquivo de configuração do banco de dados
include_once('config.php');

// Verifique se os dados foram enviados via POST e se o ID é um número válido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    // Obtenha o ID do funcionário a ser marcado como inativo a partir do corpo da solicitação POST
    $id = intval($_POST['id']);

    // Atualize o status do funcionário para 'Inativo'
    $sql_update_status = "UPDATE usuario SET status = 'Inativo' WHERE id = ?";
    $stmt_update_status = mysqli_prepare($conexao, $sql_update_status);

    if ($stmt_update_status) {
        mysqli_stmt_bind_param($stmt_update_status, "i", $id);

        if (mysqli_stmt_execute($stmt_update_status)) {
            // A atualização do status foi bem-sucedida
            echo json_encode(array('status' => 'success', 'message' => 'Status do funcionário alterado para Inativo.'));
        } else {
            // Trate erros na atualização do status
            echo json_encode(array('status' => 'error', 'message' => 'Erro ao atualizar o status do funcionário.'));
        }

        mysqli_stmt_close($stmt_update_status);
    } else {
        // Trate erros na preparação da atualização do status
        echo json_encode(array('status' => 'error', 'message' => 'Erro ao preparar a atualização do status do funcionário.'));
    }
} else {
    // Se os dados não foram enviados via POST ou o ID não é válido, retorne um JSON de erro
    echo json_encode(array('status' => 'error', 'message' => 'Requisição inválida.'));
}

// Feche a conexão com o banco de dados
mysqli_close($conexao);
?>