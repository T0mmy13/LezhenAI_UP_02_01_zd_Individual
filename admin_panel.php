<?php
session_start();

// Проверка, вошел ли пользователь как админ
if (!isset($_SESSION['user']) || $_SESSION['user']['Админ'] !== 'true') {
    header("Location: index.html");
    exit();
}

require 'config.php';

// Получение всех данных для таблицы "Авторизация"
$stmt_auth = $conn->prepare("SELECT * FROM Авторизация");
$stmt_auth->execute();
$result_auth = $stmt_auth->get_result();
$auth_data = [];
while ($row = $result_auth->fetch_assoc()) {
    $auth_data[] = $row;
}
$stmt_auth->close();

// Получение всех данных для таблицы "Задачи"
$stmt_tasks = $conn->prepare("SELECT * FROM Задачи");
$stmt_tasks->execute();
$result_tasks = $stmt_tasks->get_result();
$tasks_data = [];
while ($row = $result_tasks->fetch_assoc()) {
    $tasks_data[] = $row;
}
$stmt_tasks->close();

$conn->close();

// Сохранение логина пользователя для приветствия
$user = $_SESSION['user'];

include 'admin_panel.html';
?>