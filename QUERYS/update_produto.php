<?php
// Verifique se os dados foram enviados através do método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Certifique-se de incluir o arquivo de configuração do banco de dados
    include_once('config.php');

    // Obtenha os dados do produto a ser atualizado a partir do corpo da solicitação POST
    $id = $_POST['id'];
    $descricao = $_POST['descricao'];
    $nome = $_POST['nome'];
    $valorFabrica = $_POST['valor_fabrica'];
    $valorVenda = $_POST['valor_venda'];

    // Consulta SQL para atualizar os dados do produto
    $sql = "UPDATE produto SET descricao = '$descricao', nome = '$nome', valor_fabrica = '$valorFabrica', valor_venda = '$valorVenda' WHERE id = $id";

    if (mysqli_query($conexao, $sql)) {
        // A atualização foi bem-sucedida
        echo json_encode(array('status' => 'success', 'message' => 'Produto atualizado com sucesso.'));
    } else {
        // Trate erros na execução da consulta SQL, se necessário
        echo json_encode(array('status' => 'error', 'message' => 'Erro na atualização do produto.'));
    }

    // Feche a conexão com o banco de dados
    mysqli_close($conexao);
} else {
    // Se os dados não foram enviados via POST, retorne um JSON de erro
    echo json_encode(array('status' => 'error', 'message' => 'Requisição inválida.'));
}
?>
