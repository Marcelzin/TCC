<?php
include_once('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_filtra = $_POST['nome_filtra'];
    $email_filtra = $_POST['email_filtra'];
    $status_filtra = $_POST['status_filtra'];
    $nivelAcesso_filtra = $_POST['nivelAcesso_filtra'];

    $comercio_id = $_SESSION['comercio_id'];
    $usuario_id = $_SESSION['usuario_id'];

    $query = "SELECT * FROM usuario WHERE comercio_id = ? AND id <> '$usuario_id'";

    $types = "i";
    $params = array(&$comercio_id);

    if (!empty($nome_filtra)) {
        $query .= " AND nome LIKE ?";
        $types .= "s";
        $params[] = "%$nome_filtra%";
    }

    if (!empty($email_filtra)) {
        $query .= " AND email LIKE ?";
        $types .= "s";
        $params[] = "%$email_filtra%";
    }

    if (!empty($status_filtra)) {
        $query .= " AND status = ?";
        $types .= "s";
        $params[] = $status_filtra;
    }

    if (!empty($nivelAcesso_filtra)) {
        $query .= " AND nivel_acesso = ?";
        $types .= "s";
        $params[] = $nivelAcesso_filtra;
    }

    $stmt = mysqli_prepare($conexao, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table" id="tabela_users">
                    
                    <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['nome'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['nivel_acesso'] . '</td>';
                echo '<td>' . $row['status'] . '</td>';
                echo '<td><ion-icon name="ban-outline" style="cursor: pointer;" onclick="exibirModalExclusao(' . $row["id"] . ')"></ion-icon></td>';
                echo '<td><ion-icon name="pencil-outline" style="cursor: pointer;" onclick="abrirModalEdicao(' . $row["id"] . ')"></ion-icon></td>';
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