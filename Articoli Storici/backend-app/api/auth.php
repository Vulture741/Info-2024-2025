<?php
$origin = 'http://localhost:5173';
header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Origin: http://localhost:5173");
  header("Access-Control-Allow-Credentials: true");
  header("Access-Control-Allow-Headers: Content-Type");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  exit(0);
}


session_start();
require_once '../config/db.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

if ($action === 'login') {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$input['username']]);
  $user = $stmt->fetch();

  if ($user && password_verify($input['password'], $user['password'])) {
      $_SESSION['user'] = ['id' => $user['id'], 'role' => $user['role']];
      echo json_encode(['message' => 'Login successful', 'role' => $user['role'], 'id' => $user['id']]);
  } else {
      http_response_code(401);
      echo json_encode(['error' => 'Credenziali errate']);
  }
}

