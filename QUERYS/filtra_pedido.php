<?php
// Inclua o arquivo de configuração
require_once "config.php";

// Inicie a sessão
session_start();

// Verifique se a variável de sessão comercio_id está definida
if (isset($_SESSION['comercio_id'])) {
  $comercio_id = $_SESSION['comercio_id'];

  // Recolha os parâmetros da requisição AJAX
  $data_pedido = $_POST['data_pedido'];
  $valor_total = $_POST['valor_total'];
  $lucro_obtido = $_POST['lucro_obtido'];
  $responsavel = $_POST['responsavel'];
  $forma_pagamento = $_POST['forma_pagamento'];
  $comercio = $_POST['comercio'];

  // Construa a consulta SQL com base nos parâmetros
  $sql = "SELECT
            usuario.nome AS nome_usuario,
            forma_pagamento.tipo AS nome_forma_pagamento,
            comercio.nome AS nome_comercio,
            pedido.*
          FROM
            pdvher45_PDV.pedido
          INNER JOIN usuario ON pedido.responsavel_id = usuario.id
          INNER JOIN forma_pagamento ON pedido.pagamento_id = forma_pagamento.id
          INNER JOIN comercio ON pedido.comercio_id = comercio.id
          WHERE pedido.comercio_id = '$comercio_id'";

  // Adicione condições à consulta com base nos parâmetros fornecidos
  if (!empty($data_pedido)) {
    $sql .= " AND pedido.data_pedido LIKE '%$data_pedido%'";
  }

  if (!empty($valor_total)) {
    $sql .= " AND pedido.valor_total LIKE '%$valor_total%'";
  }

  if (!empty($lucro_obtido)) {
    $sql .= " AND pedido.lucro_obtido LIKE '%$lucro_obtido%'";
  }

  if (!empty($responsavel)) {
    $sql .= " AND usuario.nome LIKE '%$responsavel%'";
  }

  if (!empty($forma_pagamento)) {
    $sql .= " AND forma_pagamento.tipo LIKE '%$forma_pagamento%'";
  }

  if (!empty($comercio)) {
    $sql .= " AND comercio.nome LIKE '%$comercio%'";
  }

  $result = mysqli_query($conexao, $sql);

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      // Construa a saída com os dados filtrados
      echo "<tr>";
      echo "<td style='text-align: center'>" . $row["data_pedido"] . "</td>";
      echo "<td style='text-align: center'>" . $row["valor_total"] . "</td>";
      echo "<td style='text-align: center'>" . $row["lucro_obtido"] . "</td>";
      echo "<td style='text-align: center'>" . $row["nome_usuario"] . "</td>";
      echo "<td style='text-align: center'>" . $row["nome_forma_pagamento"] . "</td>";
      echo "<td style='text-align: center'>" . $row["nome_comercio"] . "</td>";
      echo "</tr>";
    }
  } else {
    echo "<tr><td colspan='6' style='text-align: center;'>Nenhum registro encontrado.</td></tr>";
  }
} else {
  echo "A variável de sessão comercio_id não está definida.";
}
