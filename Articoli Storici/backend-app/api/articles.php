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
    $stmt = $pdo->prepare("SELECT a.*, GROUP_CONCAT(t.tag) as tags 
                          FROM articles a 
                          LEFT JOIN article_tags t ON a.id = t.article_id 
                          WHERE a.id = ? 
                          GROUP BY a.id");
    $stmt->execute([$id]);
    $article = $stmt->fetch();
    if ($article) {
        $article['tags'] = $article['tags'] ? explode(',', $article['tags']) : [];
    }
    echo json_encode($article);
    exit;
}

if ($method === 'GET') {
    $stmt = $pdo->query("SELECT a.*, GROUP_CONCAT(t.tag) as tags 
                        FROM articles a 
                        LEFT JOIN article_tags t ON a.id = t.article_id 
                        GROUP BY a.id 
                        ORDER BY published_at DESC");
    $articles = $stmt->fetchAll();
    foreach ($articles as &$article) {
        $article['tags'] = $article['tags'] ? explode(',', $article['tags']) : [];
    }
    echo json_encode($articles);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';
    $image_url = $data['image_url'] ?? '';
    $tags = $data['tags'] ?? [];

    if ($title && $content) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO articles (title, content, image_url, published_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$title, $content, $image_url]);
            $articleId = $pdo->lastInsertId();

            if (!empty($tags)) {
                $tagStmt = $pdo->prepare("INSERT INTO article_tags (article_id, tag) VALUES (?, ?)");
                foreach ($tags as $tag) {
                    $tagStmt->execute([$articleId, $tag]);
                }
            }

            $pdo->commit();
            echo json_encode(["success" => true]);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(["error" => $e->getMessage()]);
        }
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
    $tags = $data['tags'] ?? [];

    if ($title && $content) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ?, image_url = ? WHERE id = ?");
            $stmt->execute([$title, $content, $image_url, $id]);

            // Rimuovi i tag esistenti
            $deleteStmt = $pdo->prepare("DELETE FROM article_tags WHERE article_id = ?");
            $deleteStmt->execute([$id]);

            // Inserisci i nuovi tag
            if (!empty($tags)) {
                $tagStmt = $pdo->prepare("INSERT INTO article_tags (article_id, tag) VALUES (?, ?)");
                foreach ($tags as $tag) {
                    $tagStmt->execute([$id, $tag]);
                }
            }

            $pdo->commit();
            echo json_encode(["success" => true]);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(["error" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Missing fields"]);
    }
    exit;
}

if ($method === 'DELETE' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $pdo->beginTransaction();
    try {
        // I tag verranno eliminati automaticamente grazie alla foreign key ON DELETE CASCADE
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $pdo->commit();
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["error" => $e->getMessage()]);
    }
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Method Not Allowed"]);
exit;