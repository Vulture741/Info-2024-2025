<?php
$origin = 'http://localhost:5173';
header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Credentials: true");
    exit(0);
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
    exit;
}

if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM articles ORDER BY published_at DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';
    $image_url = $data['image_url'] ?? '';

    if ($title && $content) {
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, image_url, published_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$title, $content, $image_url]);
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Missing fields"]);
    }
    exit;
}

if ($method === 'PUT' && isset($_GET['id'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = intval($_GET['id']);

    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';
    $image_url = $data['image_url'] ?? '';

    if ($title && $content) {
        $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$title, $content, $image_url, $id]);
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Missing fields"]);
    }
    exit;
}

if ($method === 'DELETE' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["success" => true]);
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Method Not Allowed"]);
exit;
