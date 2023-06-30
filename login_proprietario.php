
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login proprietário</title>
    <link rel="stylesheet" href="/TCC/CSS/login_proprietario.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
</head>
<body>
<form action="login_prop.php" method="POST">
    <div class="main">
    
            <div class="left">
            
                <div class="card">
                
                    <div class="textfield">
                   
                        <h1>Entre na sua <span>conta</span> <br> como <span>proprietário</span>!</h1>
                        
                        <div class="textfield">
                            <label for="email">Email</label>
                            <input type="text" placeholder="Digite seu Email..." id="email" name="email">
                        </div>

                        <div class="textfield">
                            <label for="senha">Senha</label>
                            <input type="password" placeholder="Digite sua senha..." id="senha" name="senha">
                        </div>

                        <button type="submit" class="btn" id="submit" name="submit" >Entrar</button>
                        <a href="/TCC/index.html" class="link"><i class="fas fa-arrow-left"></i> Voltar</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="right"> 
            <img src="" class="img">
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
</body>
</html>
