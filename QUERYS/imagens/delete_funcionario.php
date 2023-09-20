<?php
// Certifique-se de incluir o arquivo de configuração do banco de dados
include_once('config.php');

// Verifique se os dados foram enviados via POST e se o ID é um número válido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    // Obtenha o ID do funcionário a ser excluído a partir do corpo da solicitação POST
    $id = intval($_POST['id']);

    // Consulta SQL para excluir o funcionário
    $sql_delete_funcionario = "DELETE FROM usuario WHERE id = ?";
    $stmt_delete_funcionario = mysqli_prepare($conexao, $sql_delete_funcionario);

    if ($stmt_delete_funcionario) {
        mysqli_stmt_bind_param($stmt_delete_funcionario, "i", $id);

        if (mysqli_stmt_execute($stmt_delete_funcionario)) {
            // A exclusão do funcionário foi bem-sucedida
            echo json_encode(array('status' => 'success', 'message' => 'Funcionário excluído com sucesso.'));
        } else {
            // Trate erros na exclusão do funcionário
            echo json_encode(array('status' => 'error', 'message' => 'Erro ao excluir o funcionário.'));
        }

        mysqli_stmt_close($stmt_delete_funcionario);
    } else {
        // Trate erros na preparação da exclusão do funcionário
        echo json_encode(array('status' => 'error', 'message' => 'Erro ao preparar a exclusão do funcionário.'));
    }
} else {
    // Se os dados não foram enviados via POST ou o ID não é válido, retorne um JSON de erro
    echo json_encode(array('status' => 'error', 'message' => 'Requisição inválida.'));
}

// Feche a conexão com o banco de dados
mysqli_close($conexao);
?>
