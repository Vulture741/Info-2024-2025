<?php
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

include 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $article_id = $_GET['article_id'];
    $stmt = $conn->prepare('SELECT * FROM comments WHERE article_id = ? ORDER BY created_at DESC');
    $stmt->bind_param('i', $article_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($comments);
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare('INSERT INTO comments (article_id, user_id, username, content) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('iiss', $data['article_id'], $data['user_id'], $data['username'], $data['content']);
    $stmt->execute();
    echo json_encode(['success' => true]);
}

if ($method === 'DELETE') {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM comments WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
}
?>