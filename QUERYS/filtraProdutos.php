<?php
include_once('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_filtra = $_POST['nome_filtra'];
    $descricao_filtra = $_POST['descricao_filtra'];
    $valor_prod_filtra = $_POST['valor_prod_filtra'];
    $preco_filtra = $_POST['preco_filtra'];

    $comercio_id = $_SESSION['comercio_id'];

    $query = "SELECT * FROM produto WHERE comercio_id = ? ORDER BY status ASC";

    $types = "i";
    $params = array(&$comercio_id);

    if (!empty($nome_filtra)) {
        $query .= " AND nome LIKE ?";
        $types .= "s";
        $params[] = "%$nome_filtra%";
    }

    if (!empty($descricao_filtra)) {
        $query .= " AND descricao LIKE ?";
        $types .= "s";
        $params[] = "%$descricao_filtra%";
    }

    if (!empty($valor_prod_filtra)) {
        $query .= " AND valor_fabrica <= ?";
        $types .= "d";
        $params[] = floatval($valor_prod_filtra);
    }

    if (!empty($preco_filtra)) {
        $query .= " AND valor_venda <= ?";
        $types .= "d";
        $params[] = floatval($preco_filtra);
    }

    $stmt = mysqli_prepare($conexao, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table" id="tb_produtos">
                    
                    <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td style="text-align: center;">' . $row['nome'] . '</td>';
                echo '<td style="text-align: center;">' . $row['descricao'] . '</td>';
                echo '<td style="text-align: center;">R$' . number_format($row['valor_fabrica'], 2, ',', '.') . '</td>';
                echo '<td style="text-align: center;">R$' . number_format($row['valor_venda'], 2, ',', '.') . '</td>';
                echo '<td style="text-align: center;">' . $row['status'] . '</td>';
                echo '<td style="text-align: center;"><ion-icon name="ban-outline" style="cursor: pointer;" onclick="exibirModalExclusao(' . $row["id"] . ')"></ion-icon></td>';
                echo '<td style="text-align: center;"><ion-icon name="pencil-outline" style="cursor: pointer;" onclick="abrirModalEdicao(' . $row["id"] . ')"></ion-icon></td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo 'Nenhum registro encontrado.';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo 'Erro na preparação da declaração: ' . mysqli_error($conexao);
    }
}
?>
