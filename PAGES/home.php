<?php
session_start();

if (isset($_SESSION['comercio_id']) && isset($_SESSION['usuario_id'])) {
  include_once('config.php');

  // Inicialize a variável $nivel_acesso com um valor padrão
  $nivel_acesso = '';

  // Obtenha o valor de $comercio_id da sessão
  $comercio_id = $_SESSION['comercio_id'];

  // Consulta SQL para buscar o nível de acesso do usuário com base no 'usuario_id'
  $usuario_id = $_SESSION['usuario_id'];
  $sql = "SELECT nivel_acesso FROM usuario WHERE ID = '$usuario_id'";
  $result = mysqli_query($conexao, $sql);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $nivel_acesso = $row['nivel_acesso'];
  } else {
    // Se houver um erro na consulta, você pode lidar com ele aqui
    echo "Erro na consulta: " . mysqli_error($conexao);
    exit();
  }
} else {
  echo '<script type="text/javascript">
            alert("Acesso Negado. Você precisa efetuar login novamente.");
            window.location.href = "../index.html"; // Redirecione imediatamente
          </script>';
  exit();
}

$sqlHoje = "SELECT COUNT(*) AS vendas_realizadas, SUM(valor_total) AS faturamento, SUM(lucro_obtido) AS lucro 
            FROM pedido 
            WHERE DATE(data_pedido) = CURDATE() AND comercio_id = '$comercio_id';";

$resultHoje = mysqli_query($conexao, $sqlHoje);

if ($resultHoje) {
  $rowHoje = mysqli_fetch_assoc($resultHoje);
  $vendasHoje = $rowHoje['vendas_realizadas'];
  $faturamentoHoje = $rowHoje['faturamento'];
  $lucroHoje = $rowHoje['lucro'];
} else {
  // Trate erros aqui, se necessário
}

// Consulta para o card "Semana"
$sqlSemana = "SELECT COUNT(*) AS vendas_realizadas, SUM(valor_total) AS faturamento, SUM(lucro_obtido) AS lucro 
              FROM pedido 
              WHERE YEARWEEK(data_pedido, 1) = YEARWEEK(NOW(), 1) AND comercio_id = '$comercio_id';";

$resultSemana = mysqli_query($conexao, $sqlSemana);

if ($resultSemana) {
  $rowSemana = mysqli_fetch_assoc($resultSemana);
  $vendasSemana = $rowSemana['vendas_realizadas'];
  $faturamentoSemana = $rowHoje['faturamento'];
  $lucroSemana = $rowSemana['lucro'];
} else {
  // Trate erros aqui, se necessário
}

// Consulta para o card "Mês"
$sqlMes = "SELECT COUNT(*) AS vendas_realizadas, SUM(valor_total) AS faturamento, SUM(lucro_obtido) AS lucro 
           FROM pedido 
           WHERE YEAR(data_pedido) = YEAR(NOW()) AND MONTH(data_pedido) = MONTH(NOW()) AND comercio_id = '$comercio_id';";

$resultMes = mysqli_query($conexao, $sqlMes);

if ($resultMes) {
  $rowMes = mysqli_fetch_assoc($resultMes);
  $vendasMes = $rowMes['vendas_realizadas'];
  $faturamentoMes = $rowMes['faturamento'];
  $lucroMes = $rowMes['lucro'];
} else {
  // Trate erros aqui, se necessário
}

// ...
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/TCC/CSS/cadastro_prod.css">
  <link rel="stylesheet" href="/TCC/CSS/menu.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <title>tela home</title>
  <style>
    .dataTables_wrapper {
      margin-left: 0px;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #2e3559 !important;">
    <div class="container-fluid">
      <a class="navbar-brand" href="/TCC/PAGES/home.php">
        <img src="/TCC/STATIC/PDV-HERMES4.png" alt="Logo" width="60" height="auto">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" style="color: #fff !important; font-weight: 600;" href="/TCC/PAGES/home.php">HOME</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="color: #fff !important;" href="/TCC/PAGES/vendas.php">VENDAS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="color: #fff !important;" href="/TCC/PAGES/cadastro_prod.php">PRODUTOS</a>
          </li>
          <?php if ($nivel_acesso === 'Proprietário') { ?>
            <li class="nav-item active">
              <a class="nav-link" style="color: #fff !important;" href="/TCC/PAGES/funcionarios.php">USUÁRIOS</a>
            </li>
          <?php } ?>
        </ul>
      </div>
      <div>
        <a class="nav-link" style="color: #fff !important; cursor: pointer" onclick="logoff()"><ion-icon
            name="exit-outline"></ion-icon></a>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-around">
      <div class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title">Hoje</h1>
            <h3>Vendas realizadas:
              <?php echo $vendasHoje; ?>
            </h3>
            <h3>Faturamento: R$
              <?php echo number_format($faturamentoHoje, 2, ',', '.'); ?>
            </h3>
            <h2>Lucro Total: R$
              <?php echo number_format($lucroHoje, 2, ',', '.'); ?>
            </h2>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title">Semana</h1>
            <h3>Vendas realizadas:
              <?php echo $vendasSemana; ?>
            </h3>
            <h3>Faturamento: R$
              <?php echo number_format($faturamentoSemana, 2, ',', '.'); ?>
            </h3>
            <h2>Lucro Total: R$
              <?php echo number_format($lucroSemana, 2, ',', '.'); ?>
            </h2>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title">Mês</h1>
            <h3>Vendas realizadas:
              <?php echo $vendasMes; ?>
            </h3>
            <h3>Faturamento: R$
              <?php echo number_format($faturamentoMes, 2, ',', '.'); ?>
            </h3>
            <h2>Lucro Total: R$
              <?php echo number_format($lucroMes, 2, ',', '.'); ?>
            </h2>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <h5 style="text-align: center">Tabela de pedidos</h5>
    <table class="table table-striped" id="tabela_relatorio" style="text-align: center;">
      <thead>
        <tr>
          <th style="text-align: center;">Data do Pedido</th>
          <th style="text-align: center;">Valor Total</th>
          <th style="text-align: center;">Lucro Obtido</th>
          <th style="text-align: center;">Responsável</th>
          <th style="text-align: center;">Pagamento</th>
          <th style="text-align: center;">Comércio</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Conecte-se ao banco de dados aqui
        
        if (isset($_SESSION['comercio_id'])) {
          $comercio_id = $_SESSION['comercio_id'];

          // Consulta SQL para selecionar os dados da tabela "pedido" com base no comercio_id
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
    WHERE pedido.comercio_id = '$comercio_id';
    ";
          $result = mysqli_query($conexao, $sql);

          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td style='text-align: center;'>" . $row["data_pedido"] . "</td>";
              echo "<td style='text-align: center;'>" . $row["valor_total"] . "</td>";
              echo "<td style='text-align: center;'>" . $row["lucro_obtido"] . "</td>";
              echo "<td style='text-align: center;'>" . $row["nome_usuario"] . "</td>"; // Nome do usuário
              echo "<td style='text-align: center;'>" . $row["nome_forma_pagamento"] . "</td>"; // Nome da forma de pagamento
              echo "<td style='text-align: center;'>" . $row["nome_comercio"] . "</td>"; // Nome do comércio
              echo "</tr>";

            }
          } else {
            echo "<tr><td colspan='7' style='text-align: center;'>Nenhum registro encontrado.</td></tr>";
          }
        } else {
          echo "A variável de sessão comercio_id não está definida.";
        }

        // Feche a conexão com o banco de dados
        
        ?>
      </tbody>
    </table>
  </div>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
  <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

  <?php
  include_once('config.php');

  $query = "SELECT
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

  $result = $conexao->query($query);

  if ($result->num_rows > 0) {
    ?>
    <script>
      $(document).ready(function () {
        $('#tabela_relatorio').DataTable({
          "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese.json"
          }
        });
      });
    </script>
    <?php
  }

  $conexao->close();
  ?>

  <script>
    // Função para realizar o logoff
    function logoff() {
      $.ajax({
        method: "POST",
        url: '/TCC/QUERYS/logout.php',
        success: function (response) {
          // Exibir um alerta SweetAlert2 quando o logoff for bem-sucedido
          Swal.fire({
            icon: 'success', // Ícone de sucesso
            title: 'Usuário deslogado com sucesso',
            showConfirmButton: false, // Oculta o botão de confirmação
            timer: 1500 // Fecha automaticamente após 1,5 segundos
          });

          // Redirecionar o usuário para a página de login ou fazer outras ações após o logoff
          setTimeout(function () {
            window.location.href = '/TCC/index.html'; // Altere para a URL correta da página de login
          }, 1500);
        },
        error: function (error) {
          console.error(error);
          // Lidar com erros, se necessário
        }
      });
    }
  </script>
</body>

</html>