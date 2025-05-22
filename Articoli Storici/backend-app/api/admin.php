<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST': // create
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, image_url) VALUES (?, ?, ?)");
        $stmt->execute([$data['title'], $data['content'], $data['image_url'] ?? null]);
        echo json_encode(['message' => 'Article added']);
        break;

    case 'PUT': // update
        parse_str($_SERVER['QUERY_STRING'], $params);
        $stmt = $pdo->prepare("UPDATE articles SET title=?, content=?, image_url=? WHERE id=?");
        $stmt->execute([$data['title'], $data['content'], $data['image_url'] ?? null, $params['id']]);
        echo json_encode(['message' => 'Article updated']);
        break;

    case 'DELETE': // delete
        parse_str($_SERVER['QUERY_STRING'], $params);
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id=?");
        $stmt->execute([$params['id']]);
        echo json_encode(['message' => 'Article deleted']);
        break;
}
