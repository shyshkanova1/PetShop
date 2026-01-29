<?php
require_once __DIR__ . '/config/db.php';

$stmt = $pdo->query("SELECT GETDATE() AS server_time");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo "Підключення працює!<br>";
echo "Час сервера: " . $row['server_time'];
