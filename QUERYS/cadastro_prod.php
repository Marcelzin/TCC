<?php
include_once('config.php');

// Iniciar a sessão (se ainda não estiver iniciada)
session_start();

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

    // Recuperar o comercio_id da sessão (substitua 'nome_da_variavel_de_sessao' pelo nome real)
    $comercio_id = $_SESSION['comercio_id'];

    // Upload da imagem
    $imagemPath = null;
    if (isset($_FILES['imagem'])) {
        $uploadDir = '/TCC/QUERYS/imagens/';
        $imagemName = $_FILES['imagem']['name'];
        $imagemPath = $uploadDir . $imagemName;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imagemPath)) {
            // Upload bem-sucedido
        } else {
            // Erro no upload
            $response = array(
                'status' => 'error',
                'message' => 'Erro ao fazer upload da imagem.'
            );
            echo json_encode($response);
            exit();
        }
    }

    // Modificar a consulta SQL para incluir o comercio_id e o caminho da imagem
    $query = "INSERT INTO produto (comercio_id, nome, valor_fabrica, valor_venda, descricao, imagem) 
              VALUES ('$comercio_id', '$nome', '$valorFabrica', '$valorVenda', '$descricao', '$imagemPath')";

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
