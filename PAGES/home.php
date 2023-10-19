<?php
session_start();

if (isset($_SESSION['comercio_id']) && isset($_SESSION['usuario_id'])) {
  include_once('config.php');

  $nivel_acesso = '';
  $comercio_id = $_SESSION['comercio_id'];
  $usuario_id = $_SESSION['usuario_id'];

  $sql = "SELECT nivel_acesso FROM usuario WHERE ID = '$usuario_id'";
  $result = mysqli_query($conexao, $sql);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $nivel_acesso = $row['nivel_acesso'];
  } else {
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

$sqlHoje = "SELECT 
    COUNT(*) AS vendas_realizadas, 
    SUM(valor_total) AS faturamento, 
    SUM(lucro_obtido) AS lucro
FROM pedido 
WHERE DATE(data_pedido) = CURDATE() AND comercio_id = '$comercio_id';";

$sqlSemana = "SELECT 
    COUNT(*) AS vendas_realizadas, 
    SUM(valor_total) AS faturamento, 
    SUM(lucro_obtido) AS lucro 
FROM pedido 
WHERE YEARWEEK(data_pedido, 1) = YEARWEEK(NOW(), 1) AND comercio_id = '$comercio_id';";

$sqlMes = "SELECT 
    COUNT(*) AS vendas_realizadas, 
    SUM(valor_total) AS faturamento, 
    SUM(lucro_obtido) AS lucro 
FROM pedido 
WHERE YEAR(data_pedido) = YEAR(NOW()) AND MONTH(data_pedido) = MONTH(NOW()) AND comercio_id = '$comercio_id';";

$resultHoje = mysqli_query($conexao, $sqlHoje);
$resultSemana = mysqli_query($conexao, $sqlSemana);
$resultMes = mysqli_query($conexao, $sqlMes);

if (!$resultHoje || !$resultSemana || !$resultMes) {
  echo "Erro na consulta: " . mysqli_error($conexao);
  exit();
}

$rowHoje = mysqli_fetch_assoc($resultHoje);
$rowSemana = mysqli_fetch_assoc($resultSemana);
$rowMes = mysqli_fetch_assoc($resultMes);

$vendasHoje = $rowHoje['vendas_realizadas'];
$faturamentoHoje = $rowHoje['faturamento'];
$lucroHoje = $rowHoje['lucro'];

$vendasSemana = $rowSemana['vendas_realizadas'];
$faturamentoSemana = $rowSemana['faturamento'];
$lucroSemana = $rowSemana['lucro'];

$vendasMes = $rowMes['vendas_realizadas'];
$faturamentoMes = $rowMes['faturamento'];
$lucroMes = $rowMes['lucro'];

$sqlTiposPagamentoHoje = "SELECT forma_pagamento.tipo, COUNT(*) AS quantidade
FROM pedido
INNER JOIN forma_pagamento ON pedido.pagamento_id = forma_pagamento.id
WHERE DATE(pedido.data_pedido) = CURDATE() AND pedido.comercio_id = '$comercio_id'
GROUP BY forma_pagamento.tipo;";

$sqlTiposPagamentoSemana = "SELECT forma_pagamento.tipo, COUNT(*) AS quantidade
FROM pedido
INNER JOIN forma_pagamento ON pedido.pagamento_id = forma_pagamento.id
WHERE YEARWEEK(pedido.data_pedido, 1) = YEARWEEK(NOW(), 1) AND pedido.comercio_id = '$comercio_id'
GROUP BY forma_pagamento.tipo;";

$sqlTiposPagamentoMes = "SELECT forma_pagamento.tipo, COUNT(*) AS quantidade
FROM pedido
INNER JOIN forma_pagamento ON pedido.pagamento_id = forma_pagamento.id
WHERE DATE(pedido.data_pedido) >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND pedido.comercio_id = '$comercio_id'
GROUP BY forma_pagamento.tipo;";

// Executar as consultas SQL para obter os tipos de pagamento
$resultTiposPagamentoHoje = mysqli_query($conexao, $sqlTiposPagamentoHoje);
$resultTiposPagamentoSemana = mysqli_query($conexao, $sqlTiposPagamentoSemana);
$resultTiposPagamentoMes = mysqli_query($conexao, $sqlTiposPagamentoMes);

if (!$resultTiposPagamentoHoje || !$resultTiposPagamentoSemana || !$resultTiposPagamentoMes) {
  echo "Erro na consulta de tipos de pagamento: " . mysqli_error($conexao);
  exit();
}

// Inicializar arrays para armazenar os tipos de pagamento
$tiposPagamentoHoje = array();
$tiposPagamentoSemana = array();
$tiposPagamentoMes = array();

// Obter os tipos de pagamento para hoje
while ($row = mysqli_fetch_assoc($resultTiposPagamentoHoje)) {
  $tiposPagamentoHoje[] = $row;
}

// Obter os tipos de pagamento para a semana
while ($row = mysqli_fetch_assoc($resultTiposPagamentoSemana)) {
  $tiposPagamentoSemana[] = $row;
}

// Obter os tipos de pagamento para o mês
while ($row = mysqli_fetch_assoc($resultTiposPagamentoMes)) {
  $tiposPagamentoMes[] = $row;
}

$sqlResponsavelHoje = "SELECT 
    usuario.nome AS nome_responsavel, 
    COUNT(*) AS vendas_realizadas, 
    SUM(pedido.valor_total) AS faturamento, 
    SUM(pedido.lucro_obtido) AS lucro
FROM pedido 
INNER JOIN usuario ON pedido.responsavel_id = usuario.id
WHERE DATE(pedido.data_pedido) = CURDATE() AND pedido.comercio_id = '$comercio_id'
GROUP BY usuario.nome;";

$sqlResponsavelSemana = "SELECT 
    usuario.nome AS nome_responsavel, 
    COUNT(*) AS vendas_realizadas, 
    SUM(pedido.valor_total) AS faturamento, 
    SUM(pedido.lucro_obtido) AS lucro 
FROM pedido 
INNER JOIN usuario ON pedido.responsavel_id = usuario.id
WHERE YEARWEEK(pedido.data_pedido, 1) = YEARWEEK(NOW(), 1) AND pedido.comercio_id = '$comercio_id'
GROUP BY usuario.nome;";

$sqlResponsavelMes = "SELECT 
    usuario.nome AS nome_responsavel, 
    COUNT(*) AS vendas_realizadas, 
    SUM(pedido.valor_total) AS faturamento, 
    SUM(pedido.lucro_obtido) AS lucro 
FROM pedido 
INNER JOIN usuario ON pedido.responsavel_id = usuario.id
WHERE DATE(pedido.data_pedido) >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND pedido.comercio_id = '$comercio_id'
GROUP BY usuario.nome;";

// Array para armazenar os resultados do responsável pelas vendas de hoje
$responsavelHojeData = array();
$resultResponsavelHoje = mysqli_query($conexao, $sqlResponsavelHoje);

if (!$resultResponsavelHoje) {
  echo "Erro na consulta do responsável pelas vendas de hoje: " . mysqli_error($conexao);
  exit();
}

while ($row = mysqli_fetch_assoc($resultResponsavelHoje)) {
  $responsavelHojeData[] = $row;
}

// Array para armazenar os resultados do responsável pelas vendas da semana
$responsavelSemanaData = array();
$resultResponsavelSemana = mysqli_query($conexao, $sqlResponsavelSemana);

if (!$resultResponsavelSemana) {
  echo "Erro na consulta do responsável pelas vendas da semana: " . mysqli_error($conexao);
  exit();
}

while ($row = mysqli_fetch_assoc($resultResponsavelSemana)) {
  $responsavelSemanaData[] = $row;
}

// Array para armazenar os resultados do responsável pelas vendas do mês
$responsavelMesData = array();
$resultResponsavelMes = mysqli_query($conexao, $sqlResponsavelMes);

if (!$resultResponsavelMes) {
  echo "Erro na consulta do responsável pelas vendas do mês: " . mysqli_error($conexao);
  exit();
}

while ($row = mysqli_fetch_assoc($resultResponsavelMes)) {
  $responsavelMesData[] = $row;
}

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
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <title>Pedidos & Relatórios</title>
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

  <div class="container mt-4 text-end">
    <label for="seletorGraficos" style="width: 99.5%; text-align: left; font-weight: bold">Gráficos</label>
    <select id="seletorGraficos" class="form-select" style="width: 25%">
      <option value="">Selecione</option>
      <option value="faturamento">Faturamento</option>
      <option value="tiposPagamentos">Tipos de Pagamentos</option>
      <option value="responsaveis">Responsáveis</option>
    </select>

    <div class="container" style="justify-content: space-between; display: flex; margin-top: 1%; display: none;"
      id="faturamento">
      <div id="graficoHoje" style="width: 33%">
        <h4 style="text-align: center">Hoje - Faturamento</h4>
      </div>
      <div id="graficoSemana" style="width: 33%">
        <h4 style="text-align: center">Semana - Faturamento</h4>
      </div>
      <div id="graficoMes" style="width: 33%">
        <h4 style="text-align: center">Mês - Faturamento</h4>
      </div>
    </div>

    <!-- Gráficos tipos de pagamentos -->
    <div class="container" style="justify-content: space-between; display: flex; margin-top: 1%; display: none;"
      id="tiposPagamentos">
      <div id="graficoHojePagamento" style="width: 33%">
        <h4 style="text-align: center">Hoje - Tipos de Pagamentos</h4>
      </div>
      <div id="graficoSemanaPagamento" style="width: 33%">
        <h4 style="text-align: center">Semana - Tipos de Pagamentos</h4>
      </div>
      <div id="graficoMesPagamento" style="width: 33%">
        <h4 style="text-align: center">Mês - Tipos de Pagamentos</h4>
      </div>
    </div>

    <!-- Gráficos responsáveis -->
    <div class="container" style="justify-content: space-between; display: flex; margin-top: 1%; display: none;"
      id="responsaveis">
      <div id="graficoHojeResponsavel" style="width: 33%">
        <h4 style="text-align: center">Hoje - Responsáveis</h4>
      </div>
      <div id="graficoSemanaResponsavel" style="width: 33%">
        <h4 style="text-align: center">Semana - Responsáveis</h4>
      </div>
      <div id="graficoMesResponsavel" style="width: 33%">
        <h4 style="text-align: center">Mês - Responsáveis</h4>
      </div>
    </div>
  </div>

  <div class="container" style="margin-top: 1%">
    <h4 style="text-align: center">Tabela de pedidos</h4>

    <form id="filtro_form">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="data_pedido">Data do Pedido:</label>
            <input type="text" id="data_pedido" class="form-control" onkeypress="filtrarTabela()">
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="valor_total">Valor Total:</label>
            <input type="text" id="valor_total" class="form-control" onkeypress="filtrarTabela()">
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="lucro_obtido">Lucro Obtido:</label>
            <input type="text" id="lucro_obtido" class="form-control" onkeypress="filtrarTabela()">
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="responsavel">Responsável:</label>
            <input type="text" id="responsavel" class="form-control" onkeypress="filtrarTabela()">
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="forma_pagamento">Forma de Pagamento:</label>
            <input type="text" id="forma_pagamento" class="form-control" onkeypress="filtrarTabela()">
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="comercio">Comércio:</label>
            <input type="text" id="comercio" class="form-control" onkeypress="filtrarTabela()">
          </div>
        </div>
      </div>
    </form>
    <div>
      <div class="container" style="margin-top: 1%">
        <table class="table table-striped" id="tabela_relatorio" style="text-align: center;">
          <thead>
            <tr>
              <th style="text-align: center;">Data do Pedido</th>
              <th style="text-align: center;">Valor Total</th>
              <th style="text-align: center;">Lucro Obtido</th>
              <th style="text-align: center;">Responsável</th>
              <th style="text-align: center;">Pagamento</th>
              <th style="text-align: center;">Comércio</th>
              <th style="text-align: center;">Ações</th> <!-- Nova coluna para ações -->
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_SESSION['comercio_id'])) {
              $comercio_id = $_SESSION['comercio_id'];

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
                  echo "<td style='text-align: center;'>" . $row["nome_usuario"] . "</td>";
                  echo "<td style='text-align: center;'>" . $row["nome_forma_pagamento"] . "</td>";
                  echo "<td style='text-align: center;'>" . $row["nome_comercio"] . "</td>";

                  // Adicione links para editar e excluir
                  echo "<td style='text-align: center;'>
  <a onclick='editarPedido(" . $row['id'] . ")'>Editar</a> |
  <a onclick='excluirPedido(" . $row['id'] . ")'>Excluir</a>
</td>";


                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='8' style='text-align: center;'>Nenhum registro encontrado.</td></tr>";
              }
            } else {
              echo "A variável de sessão comercio_id não está definida.";
            }
            ?>
          </tbody>
        </table>

      </div>

      <!-- Modal de Edição -->
      <div class="modal fade" id="editarPedidoModal" tabindex="-1" aria-labelledby="editarPedidoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editarPedidoModalLabel">Editar Pedido</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- Formulário de Edição -->
              <form id="formEdicaoPedido">
                <div class="form-group">
                  <label for="editDataPedido">Data do Pedido:</label>
                  <input type="text" id="editDataPedido" class="form-control">
                </div>
                <div class="form-group">
                  <label for="editValorTotal">Valor Total:</label>
                  <input type="text" id="editValorTotal" class="form-control">
                </div>
                <!-- Outros campos do formulário de edição -->
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              <button type="button" class="btn btn-primary" onclick="salvarEdicaoPedido()">Salvar</button>
            </div>
          </div>
        </div>
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
            }
          });
        }
      </script>

      <script>
        window.addEventListener('load', function () {

          var faturamentoHoje = <?php echo $faturamentoHoje; ?>;
          var lucroHoje = <?php echo $lucroHoje; ?>;
          var faturamentoSemana = <?php echo $faturamentoSemana; ?>;
          var lucroSemana = <?php echo $lucroSemana; ?>;
          var faturamentoMes = <?php echo $faturamentoMes; ?>;
          var lucroMes = <?php echo $lucroMes; ?>;

          // Função para criar um gráfico de colunas
          function createColumnChart(id, faturamento, lucro, title) {
            var columnOptions = {
              chart: {
                type: 'bar',
                height: 350,
              },
              plotOptions: {
                bar: {
                  horizontal: false,
                  columnWidth: '55%',
                },
              },
              dataLabels: {
                enabled: false
              },
              series: [
                {
                  name: "Faturamento",
                  data: [faturamento],
                },
                {
                  name: "Lucro",
                  data: [lucro],
                },
              ],
              xaxis: {
                categories: [title],
              },
              colors: ['#2e93d9', '#5bc0de'], // Defina as cores desejadas
              title: {
                text: 'Desempenho de Vendas',
                align: 'center',
              },
              yaxis: {
                labels: {
                  formatter: function (value) {
                    return "R$ " + value; // Formate o eixo Y com R$
                  }
                }
              },
            };

            var columnChart = new ApexCharts(document.querySelector(id), columnOptions);
            columnChart.render();
          }

          createColumnChart("#graficoHoje", faturamentoHoje, lucroHoje, "Hoje");
          createColumnChart("#graficoSemana", faturamentoSemana, lucroSemana, "Semana");
          createColumnChart("#graficoMes", faturamentoMes, lucroMes, "Mês");
        });
      </script>


      <script>
        window.addEventListener('load', function () {
          var tiposPagamentoHoje = <?php echo json_encode($tiposPagamentoHoje); ?>;
          var tiposPagamentoSemana = <?php echo json_encode($tiposPagamentoSemana); ?>;
          var tiposPagamentoMes = <?php echo json_encode($tiposPagamentoMes); ?>;

          // Função para criar um gráfico de barras
          function createBarChart(id, data, title) {
            var categories = [];
            var dataValues = [];

            data.forEach(function (item) {
              categories.push(item.tipo); // Adicione o tipo de pagamento como categoria
              dataValues.push(item.quantidade); // Adicione a quantidade como valor de dados
            });

            var barOptions = {
              chart: {
                type: 'bar',
                height: 350,
              },
              plotOptions: {
                bar: {
                  horizontal: false,
                  columnWidth: '55%',
                },
              },
              dataLabels: {
                enabled: false,
              },
              series: [
                {
                  name: "Quantidade",
                  data: dataValues, // Use os valores de quantidade
                },
              ],
              xaxis: {
                categories: categories, // Use as categorias (tipos de pagamento)
              },
              colors: ['#5cb85c'], // Defina a cor desejada
              title: {
                text: 'Tipos de Pagamento',
                align: 'center',
              },
              yaxis: {
                title: {
                  text: 'Quantidade',
                },
                labels: {
                  formatter: function (value) {
                    return value; // Mantenha a formatação padrão para o eixo Y
                  }
                }
              },
            };

            var barChart = new ApexCharts(document.querySelector(id), barOptions);
            barChart.render();
          }

          createBarChart("#graficoHojePagamento", tiposPagamentoHoje, "Hoje");
          createBarChart("#graficoSemanaPagamento", tiposPagamentoSemana, "Semana");
          createBarChart("#graficoMesPagamento", tiposPagamentoMes, "Mês");
        });
      </script>


      <script>
        window.addEventListener('load', function () {
          var responsavelHojeData = <?php echo json_encode($responsavelHojeData); ?>;
          var responsavelSemanaData = <?php echo json_encode($responsavelSemanaData); ?>;
          var responsavelMesData = <?php echo json_encode($responsavelMesData); ?>;

          // Função para criar um gráfico de barras
          function createBarChart(id, data, title) {
            var categories = [];
            var dataValues = [];

            data.forEach(function (item) {
              categories.push(item.nome_responsavel); // Adicione o nome do responsável como categoria
              dataValues.push(item.vendas_realizadas); // Adicione a quantidade de vendas realizadas como valor de dados
            });

            var barOptions = {
              chart: {
                type: 'bar',
                height: 350,
              },
              plotOptions: {
                bar: {
                  horizontal: false,
                  columnWidth: '55%',
                },
              },
              dataLabels: {
                enabled: false,
              },
              series: [
                {
                  name: "Vendas Realizadas",
                  data: dataValues, // Use os valores de vendas realizadas
                },
              ],
              xaxis: {
                categories: categories, // Use as categorias (nomes dos responsáveis)
              },
              colors: ['#f0ad4e'], // Defina a cor desejada
              title: {
                text: 'Desempenho dos Responsáveis',
                align: 'center',
              },
              yaxis: {
                title: {
                  text: 'Vendas Realizadas',
                },
                labels: {
                  formatter: function (value) {
                    return value; // Mantenha a formatação padrão para o eixo Y
                  }
                }
              },
            };

            var barChart = new ApexCharts(document.querySelector(id), barOptions);
            barChart.render();
          }

          createBarChart("#graficoHojeResponsavel", responsavelHojeData, "Hoje");
          createBarChart("#graficoSemanaResponsavel", responsavelSemanaData, "Semana");
          createBarChart("#graficoMesResponsavel", responsavelMesData, "Mês");
        });
      </script>



      <script>
        var responsavelHojeData = <?php echo json_encode($responsavelHojeData); ?>;
        var responsavelSemanaData = <?php echo json_encode($responsavelSemanaData); ?>;
        var responsavelMesData = <?php echo json_encode($responsavelMesData); ?>;

        console.log(responsavelHojeData); // Exibe o conteúdo da variável responsavelHojeData
        console.log(responsavelSemanaData); // Exibe o conteúdo da variável responsavelSemanaData
        console.log(responsavelMesData); // Exibe o conteúdo da variável responsavelMesData
      </script>

      <script>
        // Função para controlar a exibição dos gráficos com base na seleção do <select>
        document.getElementById("seletorGraficos").addEventListener("change", function () {
          // Oculta todos os containers de gráficos
          document.getElementById("faturamento").style.display = "none";
          document.getElementById("tiposPagamentos").style.display = "none";
          document.getElementById("responsaveis").style.display = "none";

          // Obtém o valor selecionado no <select>
          var selectedValue = this.value;

          // Exibe o container de gráficos correspondente à seleção
          document.getElementById(selectedValue).style.display = "flex";
        });
      </script>

      <script>
        // Função para filtrar a tabela
        function filtrarTabela() {
          // Recolha dos valores dos campos de filtro
          var data_pedido = document.getElementById("data_pedido").value;
          var valor_total = document.getElementById("valor_total").value;
          var lucro_obtido = document.getElementById("lucro_obtido").value;
          var responsavel = document.getElementById("responsavel").value;
          var forma_pagamento = document.getElementById("forma_pagamento").value;
          var comercio = document.getElementById("comercio").value;

          // Enviar uma requisição AJAX para filtrar os dados
          // Certifique-se de ajustar a URL e os parâmetros de acordo com a sua configuração
          // Aqui está um exemplo básico de como isso pode ser feito:

          $.ajax({
            url: "/TCC/QUERYS/filtra_pedido.php",
            method: "POST",
            data: {
              data_pedido: data_pedido,
              valor_total: valor_total,
              lucro_obtido: lucro_obtido,
              responsavel: responsavel,
              forma_pagamento: forma_pagamento,
              comercio: comercio
            },
            success: function (response) {
              // Atualizar a tabela com os dados filtrados
              $("#tabela_relatorio tbody").html(response);
            }
          });
        }
      </script>

      <script>
        function editarPedido(pedidoId) {
          // Recolha os dados do pedido a ser editado (substitua com os dados reais)
          var dataPedido = document.getElementById('data_pedido').value;
          var valorTotal = document.getElementById('valor_total').value;

          // Preencha o formulário de edição com os dados do pedido
          document.getElementById('editDataPedido').value = dataPedido;
          document.getElementById('editValorTotal').value = valorTotal;

          // Abra a modal de edição
          $('#editarPedidoModal').modal('show');
        }

      </script>

</body>

</html>