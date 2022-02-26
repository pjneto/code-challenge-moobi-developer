<?php

//--------------------------------------------------CONEXÃO-------------------------------------------------------------------------
$localhost = "127.0.0.1";
$username = "root";
$password = "";
$namedb = "mydb";

try {
    $pdo = new \PDO("mysql:dbname=$namedb; host=$localhost", $username, $password);
} catch (PDOException $e){  
    echo "erro com o banco de dados: " .$e->getMessage();
} catch(exception $e) {
    echo "Erro genérico: " .$e->getMessage();
}

?>
