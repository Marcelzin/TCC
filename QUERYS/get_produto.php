<?php
// Primeiro, verifique se o ID do produto foi fornecido na solicitação
if (isset($_GET['id'])) {
    $produto_id = $_GET['id'];

    // Faça a conexão com o banco de dados
    include_once('config.php'); // Certifique-se de incluir o arquivo de configuração do banco de dados

    // Consulta SQL para buscar os dados do produto pelo ID
    $sql = "SELECT * FROM produto WHERE id = $produto_id";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        // Verifique se encontrou um registro
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Crie um array associativo com os dados do produto
            $produto = array(
                'id' => $row['id'],
                'descricao' => $row['descricao'],
                'nome' => $row['nome'],
                'valor_fabrica' => $row['valor_fabrica'],
                'valor_venda' => $row['valor_venda']
            );

            // Converta o array em formato JSON e retorne como resposta
            echo json_encode($produto);
        } else {
            // Caso nenhum registro seja encontrado, retorne um JSON vazio ou uma mensagem de erro, conforme necessário
            echo json_encode(array('status' => 'error', 'message' => 'Produto não encontrado.'));
        }
    } else {
        // Trate erros na execução da consulta SQL, se necessário
        echo json_encode(array('status' => 'error', 'message' => 'Erro na consulta SQL.'));
    }

    // Feche a conexão com o banco de dados
    mysqli_close($conexao);
} else {
    // Se o ID do produto não foi fornecido, retorne um JSON de erro
    echo json_encode(array('status' => 'error', 'message' => 'ID do produto não fornecido.'));
}
?>