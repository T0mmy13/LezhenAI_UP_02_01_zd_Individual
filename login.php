// public/login.php
<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Подготовка SQL-запроса с правильными названиями столбцов
    $stmt = $conn->prepare("SELECT * FROM Авторизация WHERE Логин = ? AND Пароль = ?");
    
    // Проверка на ошибки в подготовке запроса
    if (!$stmt) {
        die("Ошибка в запросе: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $login, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;
        if ($user['Админ'] == 'true') {
            header("Location: admin_panel.php");
        } else {
            header("Location: user_panel.php");
        }
    } else {
        echo "Неправильный логин или пароль";
    }
}
?>