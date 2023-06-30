<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login funcionario</title>
    <link rel="stylesheet" href="/CSS/login_proprietario.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
</head>
<body>

    <div class="main">

        <div class="left">
            <div class="card">
                <div class="textfield">

                    <h1>Entre na sua <span>conta</span> <br> como <span>funcionário</span>!</h1>

                        <div class="textfield">
                        <label for="email">Email</label>
                        <input type="text" placeholder="Digite seu Email..." id="email">
                    </div>

                    <div class="textfield">
                        <label for="senha">Senha</label>
                        <input type="password" placeholder="Digite sua senha..." id="senha">
                    </div>

                    <button type="button" class="btn" onclick="logar()">Entrar</button>
                </div>


                <a href="/HTML/index.html" class="link"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
            </div>
        </div>

        <div class="right"> 
            <img src="" class="img">

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
    <script>
        function validarEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        function logar() {
            const email = document.getElementById("email").value;
            const senha = document.getElementById("senha").value;

            if (email === "" || senha === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Por favor, preencha todos os campos.'
                });
                return;
            }


            if (!validarEmail(email)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Por favor, insira um endereço de email válido.'
                });
                return;
            }

            if (senha.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'A senha deve conter pelo menos 8 dígitos.'
                });
                return;
            }

            // Envie os dados do formulário para o servidor ou realize outras ações aqui
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: 'Login realizado com sucesso!',
                showConfirmButton: false,
                timer: 1500,
                didClose: () => {
                    window.location.href = "/HTML/home.html"; // Redirecionar para a página de home após o cadastro
                }
            });
        }

    function validarSenha() {
        const senha = document.getElementById("senha").value;

        // Verificar se a senha contém apenas letras e números
        const regex = /^[a-zA-Z0-9]+$/;
        if (!regex.test(senha)) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'A senha deve conter apenas letras e números, sem espaços.'
            });
            return false; // Impede o envio do formulário
        }

        return true; // Permite o envio do formulário
    }


    </script>

    
</body>
</html>