<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "error" => "Method Not Allowed"]);
    exit();
}

require 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['Номер']) || !isset($data['Дата_выполнения'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "error" => "Invalid data"]);
    exit();
}

$номер = $data['Номер'];
$дата_выполнения = $data['Дата_выполнения'];

$stmt = $conn->prepare("UPDATE Задачи SET Выполнено = 'true', Дата_выполнения = ? WHERE Номер = ?");
if ($stmt) {
    $stmt->bind_param("si", $дата_выполнения, $номер);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "error" => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "error" => $conn->error]);
}

$conn->close();