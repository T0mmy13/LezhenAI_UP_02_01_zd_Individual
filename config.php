<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Individual";

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}
?>