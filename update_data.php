<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
    exit();
}

require 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['tab'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid data"]);
    exit();
}

$tab = $data['tab'];
$updates = $data['updates'];
$newEntries = $data['newEntries']; // Get new entries

$response = [];

// Обновление данных в таблице "Авторизация"
if ($tab === 'auth') {
    foreach ($updates as $update) {
        $stmt = $conn->prepare("UPDATE Авторизация SET Логин = ?, Пароль = ?, Админ = ? WHERE Номер = ?");
        if ($stmt) {
            $stmt->bind_param("sssi", $update['Логин'], $update['Пароль'], $update['Админ'], $update['Номер']);
            if ($stmt->execute()) {
                $response[] = ["Номер" => $update['Номер'], "status" => "success"];
            } else {
                $response[] = ["Номер" => $update['Номер'], "status" => "error", "message" => $stmt->error];
            }
            $stmt->close();
        } else {
            $response[] = ["Номер" => $update['Номер'], "status" => "error", "message" => $conn->error];
        }
    }

    // Добавление новых данных в таблицу "Авторизация"
    foreach ($newEntries as $newEntry) {
        $stmt = $conn->prepare("INSERT INTO Авторизация (Логин, Пароль, Админ) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $newEntry['Логин'], $newEntry['Пароль'], $newEntry['Админ']);
            if ($stmt->execute()) {
                $response[] = ["status" => "success"];
            } else {
                $response[] = ["status" => "error", "message" => $stmt->error];
            }
            $stmt->close();
        } else {
            $response[] = ["status" => "error", "message" => $conn->error];
        }
    }
} elseif ($tab === 'tasks') { // Обновление данных в таблице "Задачи"
    foreach ($updates as $update) {
        $stmt = $conn->prepare("UPDATE Задачи SET Задание = ?, Исполняющий = ?, Срок_выполнения = ?, Выполнено = ?, Дата_выполнения = ? WHERE Номер = ?");
        if ($stmt) {
            $stmt->bind_param("sssssi", $update['Задание'], $update['Исполняющий'], $update['Срок_выполнения'], $update['Выполнено'], $update['Дата_выполнения'], $update['Номер']);
            if ($stmt->execute()) {
                $response[] = ["Номер" => $update['Номер'], "status" => "success"];
            } else {
                $response[] = ["Номер" => $update['Номер'], "status" => "error", "message" => $stmt->error];
            }
            $stmt->close();
        } else {
            $response[] = ["Номер" => $update['Номер'], "status" => "error", "message" => $conn->error];
        }
    }

    // Добавление новых данных в таблицу "Задачи"
    foreach ($newEntries as $newEntry) {
        $дата_выполнения = ($newEntry['Дата_выполнения'] === 'NULL') ? null : $newEntry['Дата_выполнения'];
        $stmt = $conn->prepare("INSERT INTO Задачи (Задание, Исполняющий, Срок_выполнения, Выполнено, Дата_выполнения) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $newEntry['Задание'], $newEntry['Исполняющий'], $newEntry['Срок_выполнения'], $newEntry['Выполнено'], $дата_выполнения);
            if ($stmt->execute()) {
                $response[] = ["status" => "success"];
            } else {
                $response[] = ["status" => "error", "message" => $stmt->error];
            }
            $stmt->close();
        } else {
            $response[] = ["status" => "error", "message" => $conn->error];
        }
    }
}

$conn->close();

echo json_encode($response);
