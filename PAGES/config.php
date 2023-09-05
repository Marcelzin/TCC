<?php

    $dbHost= 'Localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'PDV';

    // Estabelecer conexão
        $conexao = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);

    /* // Verificar conexão
    if($conexao->connect_errno) {
        echo "Erro";
    }   else    {
        echo "Conexão efetuada com sucesso!";
    } */
?>