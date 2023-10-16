<?php
session_start(); // Inicia a sessão

include_once('config.php'); // Certifique-se de que este arquivo inclua a configuração do banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comercio_nome = $_POST['nome']; // Altere para o nome correto do campo no seu formulário
    $comercio_cpf_cnpj = $_POST['cpf_cnpj']; // Altere para o nome correto do campo no seu formulário

    // Verifique se já existe um comércio com o mesmo CPF/CNPJ
    $query_check_duplicate = "SELECT id FROM comercio WHERE cpf_cnpj = ?";
    $stmt = mysqli_prepare($conexao, $query_check_duplicate);
    mysqli_stmt_bind_param($stmt, 's', $comercio_cpf_cnpj);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Já existe um comércio com o mesmo CPF/CNPJ, retorne um erro
        $response = array(
            'status' => 'error',
            'message' => 'Já existe um comércio com o mesmo CPF/CNPJ.'
        );
        echo json_encode($response);
    } else {
        // Inserir dados na tabela comercio
        $query_comercio = "INSERT INTO comercio (nome, cpf_cnpj) VALUES (?, ?)";
        $stmt = mysqli_prepare($conexao, $query_comercio);
        mysqli_stmt_bind_param($stmt, 'ss', $comercio_nome, $comercio_cpf_cnpj);
        $result_comercio = mysqli_stmt_execute($stmt);

        if ($result_comercio) {
            // Recuperar o ID gerado após o insert na tabela comercio
            $comercio_id = mysqli_insert_id($conexao);

            // Armazenar o ID do comércio e o horário atual na sessão
            $_SESSION['comercio_id'] = $comercio_id;
            $_SESSION['comercio_cadastro_time'] = time(); // Armazena o timestamp atual

            // Em seguida, você pode retornar uma resposta JSON de sucesso
            $response = array(
                'status' => 'success',
                'message' => 'Cadastro do comércio realizado com sucesso!'
            );
            echo json_encode($response);
        } else {
            // Se houver um erro na inserção na tabela comercio, você pode retornar uma resposta JSON de erro
            $response = array(
                'status' => 'error',
                'message' => 'Erro ao cadastrar o comércio. Por favor, tente novamente.'
            );
            echo json_encode($response);
        }
    }
} else {
    // Se a requisição não for do tipo POST, você pode retornar uma resposta JSON de erro
    $response = array(
        'status' => 'error',
        'message' => 'Método de requisição inválido.'
    );
    echo json_encode($response);
}

// Verificar se passaram 10 minutos desde o cadastro do comércio
if (isset($_SESSION['comercio_id']) && isset($_SESSION['comercio_cadastro_time'])) {
    $comercio_id = $_SESSION['comercio_id'];
    $cadastro_time = $_SESSION['comercio_cadastro_time'];

    // Verifique se passaram 10 minutos desde o cadastro do comércio
    if (time() - $cadastro_time > 600) { // 10 minutos = 600 segundos
        // Excluir o registro de comércio
        $query_delete_comercio = "DELETE FROM comercio WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $query_delete_comercio);
        mysqli_stmt_bind_param($stmt, 'i', $comercio_id);
        $result_delete_comercio = mysqli_stmt_execute($stmt);

        // Verifique se a exclusão foi bem-sucedida
        if ($result_delete_comercio) {
            // Limpe as variáveis de sessão
            unset($_SESSION['comercio_id']);
            unset($_SESSION['comercio_cadastro_time']);
        } else {
            // Trate o erro de exclusão do comércio, se necessário
            echo "Erro ao excluir o comércio.";
        }
    }
}
?>
