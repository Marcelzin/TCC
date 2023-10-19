<?php
// Inclua o arquivo de configuração e inicie a sessão, se necessário
require_once "config.php";
session_start();

if (isset($_GET['id'])) {
    $pedido_id = $_GET['id'];

    // Recupere os detalhes do pedido a ser editado do banco de dados (substitua com sua consulta SQL)
    $sql = "SELECT * FROM pedido WHERE id = $pedido_id";
    $result = mysqli_query($conexao, $sql);

    if ($result) {
        $pedido = mysqli_fetch_assoc($result);

        // Aqui você pode exibir um formulário com os detalhes do pedido e permitir a edição

        // Exemplo de formulário para edição (substitua com os campos reais do seu pedido)
        echo "<form method='post' action='salvar_edicao.php'>";
        echo "Data do Pedido: <input type='text' name='data_pedido' value='" . $pedido['data_pedido'] . "'><br>";
        echo "Valor Total: <input type='text' name='valor_total' value='" . $pedido['valor_total'] . "'><br>";
        // Adicione mais campos aqui
        echo "<input type='hidden' name='pedido_id' value='" . $pedido_id . "'>";
        echo "<input type='submit' value='Salvar Edição'>";
        echo "</form>";
    } else {
        echo "Erro ao recuperar dados do pedido.";
    }
} else {
    echo "ID do pedido não especificado.";
}
?>