<?php

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'hermes_db';

// Estabelecer conexão

$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);


/* // Verificar conexão

if($conexao->connect_errno) {

    echo "Erro";

}   else    {

    echo "Conexão efetuada com sucesso!";

} */

?>