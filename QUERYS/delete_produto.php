<?php
// Certifique-se de incluir o arquivo de configuração do banco de dados
include_once('config.php');

// Verifique se os dados foram enviados via POST e se o ID é um número válido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    // Obtenha o ID do produto a ser excluído a partir do corpo da solicitação POST
    $id = intval($_POST['id']);

    // Primeiro, exclua os registros relacionados na tabela itens_pedido
    $sql_delete_itens_pedido = "DELETE FROM itens_pedido WHERE produto_id = ?";
    $stmt_delete_itens_pedido = mysqli_prepare($conexao, $sql_delete_itens_pedido);

    if ($stmt_delete_itens_pedido) {
        mysqli_stmt_bind_param($stmt_delete_itens_pedido, "i", $id);

        if (mysqli_stmt_execute($stmt_delete_itens_pedido)) {
            // Agora que os registros relacionados foram excluídos, exclua o produto
            $sql_delete_produto = "DELETE FROM produto WHERE id = ?";
            $stmt_delete_produto = mysqli_prepare($conexao, $sql_delete_produto);

            if ($stmt_delete_produto) {
                mysqli_stmt_bind_param($stmt_delete_produto, "i", $id);

                if (mysqli_stmt_execute($stmt_delete_produto)) {
                    // A exclusão do produto foi bem-sucedida
                    echo json_encode(array('status' => 'success', 'message' => 'Produto e registros relacionados excluídos com sucesso.'));
                } else {
                    // Trate erros na exclusão do produto
                    echo json_encode(array('status' => 'error', 'message' => 'Erro ao excluir o produto.'));
                }

                mysqli_stmt_close($stmt_delete_produto);
            } else {
                // Trate erros na preparação da exclusão do produto
                echo json_encode(array('status' => 'error', 'message' => 'Erro ao preparar a exclusão do produto.'));
            }
        } else {
            // Trate erros na exclusão dos registros relacionados
            echo json_encode(array('status' => 'error', 'message' => 'Erro ao excluir registros relacionados.'));
        }

        mysqli_stmt_close($stmt_delete_itens_pedido);
    } else {
        // Trate erros na preparação da exclusão dos registros relacionados
        echo json_encode(array('status' => 'error', 'message' => 'Erro ao preparar a exclusão de registros relacionados.'));
    }
} else {
    // Se os dados não foram enviados via POST ou o ID não é válido, retorne um JSON de erro
    echo json_encode(array('status' => 'error', 'message' => 'Requisição inválida.'));
}

// Feche a conexão com o banco de dados
mysqli_close($conexao);
?>