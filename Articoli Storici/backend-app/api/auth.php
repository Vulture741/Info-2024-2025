<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

if ($action === 'login') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$input['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($input['password'], $user['password'])) {
        $_SESSION['user'] = ['id' => $user['id'], 'role' => $user['role']];
        echo json_encode(['message' => 'Login successful', 'role' => $user['role']]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
} elseif ($action === 'logout') {
    session_destroy();
    echo json_encode(['message' => 'Logged out']);
} elseif ($action === 'check') {
    echo json_encode($_SESSION['user'] ?? ['role' => 'guest']);
}
