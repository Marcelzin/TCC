<?php
session_start();

if (isset($_SESSION['comercio_id']) && isset($_SESSION['usuario_id'])) {
  include_once('config.php');

  // Inicialize a variável $nivel_acesso com um valor padrão
  $nivel_acesso = '';

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
            <a class="nav-link" style="color: #fff !important;" href="/TCC/PAGES/home.php">HOME</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="color: #fff !important;" href="/TCC/PAGES/vendas.php">VENDAS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="color: #fff !important; font-weight: 600;"
              href="/TCC/PAGES/cadastro_prod.php">PRODUTOS</a>
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
            <h3>Vendas realizadas:</h3>
            <h3>Faturamento: R$00,00</h3>
            <h2>Lucro Total: R$00,00</h2>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title">Semana</h1>
            <h3>Vendas realizadas:</h3>
            <h3>Faturamento: R$00,00</h3>
            <h2>Lucro Total: R$00,00</h2>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title">Mês</h1>
            <h3>Vendas realizadas:</h3>
            <h3>Faturamento: R$00,00</h3>
            <h2>Lucro Total: R$00,00</h2>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const list = document.querySelectorAll(".list");
    function activelink() {
      list.forEach((item) => item.classList.remove("active"));
      this.classList.add("active");
    }
    list.forEach((item) => item.addEventListener("click", activeLink));
  </script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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