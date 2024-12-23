<?php
session_start();

// Проверка, вошел ли пользователь
if (!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit();
}

require 'config.php';
$user = $_SESSION['user'];

// Извлечение задач для текущего пользователя
$stmt = $conn->prepare("SELECT Номер, Задание, Срок_выполнения, Выполнено FROM Задачи WHERE Исполняющий = ?");
if (!$stmt) {
    die("Ошибка подготовки запроса: " . $conn->error);
}

// Привязка параметра логина пользователя
$stmt->bind_param("s", $user['Логин']);
$stmt->execute();
$result = $stmt->get_result();

// Загрузка данных задач
$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

$stmt->close();
$conn->close();

include 'user_panel.html';
?>