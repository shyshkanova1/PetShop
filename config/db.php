<?php

$server = "localhost";
$database = "PetStoreDB";
$username = "petshop_user";
$password = "PetShop123!"; 

try {
    $pdo = new PDO(
        "sqlsrv:Server=$server;Database=$database",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Помилка підключення до бази: " . $e->getMessage());
}
