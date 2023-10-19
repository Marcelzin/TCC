<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once('config.php');
    
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $nivelAcesso = $_POST['nivel_acesso'];
    $status = $_POST['status'];
    
    // Verifique se o novo email já existe no banco de dados
    $checkEmailQuery = "SELECT id FROM usuario WHERE email = '$email' AND id != $id";
    $result = mysqli_query($conexao, $checkEmailQuery);
    
    if (mysqli_num_rows($result) > 0) {
        echo json_encode(array('status' => 'error', 'message' => 'O email já está em uso.'));
    } else {
        $sql = "UPDATE usuario SET nome = '$nome', email = '$email', nivel_acesso = '$nivelAcesso', status = '$status' WHERE id = $id";
    
        if (mysqli_query($conexao, $sql)) {
            echo json_encode(array('status' => 'success', 'message' => 'Funcionário atualizado com sucesso.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Erro na atualização do funcionário.'));
        }
    }
    
    mysqli_close($conexao);
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Requisição inválida.'));
}
?>
