<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $valorFabrica = $_POST['valor_fabrica'];
    $valorVenda = $_POST['valor_venda'];
    $descricao = $_POST['descricao'];

    // Validar e escapar os dados para prevenir SQL injection
    $nome = mysqli_real_escape_string($conexao, $nome);
    $valorFabrica = mysqli_real_escape_string($conexao, $valorFabrica);
    $valorVenda = mysqli_real_escape_string($conexao, $valorVenda);
    $descricao = mysqli_real_escape_string($conexao, $descricao);

    $query = "INSERT INTO produto (nome, valor_fabrica, valor_venda, descricao) VALUES ('$nome', '$valorFabrica', '$valorVenda', '$descricao')";
    
    if (mysqli_query($conexao, $query)) {
        // Cadastro bem-sucedido
        $response = array(
            'status' => 'success',
            'message' => 'Produto cadastrado com sucesso!'
        );
        echo json_encode($response);
        exit();
    } else {
        // Erro na inserção
        $response = array(
            'status' => 'error',
            'message' => 'Erro ao cadastrar o produto: ' . mysqli_error($conexao)
        );
        echo json_encode($response);
        exit();
    }
} else {
    // Método de requisição incorreto
    $response = array(
        'status' => 'error',
        'message' => 'Método de requisição incorreto.'
    );
    echo json_encode($response);
    exit();
}
?>
