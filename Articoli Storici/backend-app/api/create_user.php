<?php
require_once '../config/db.php';

$username = 'u';
$password = 'u';
$role = 'user';

$hashed = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashed, $role]);
    echo "Utente creato: $username | Ruolo: $role";
} catch (PDOException $e) {
    echo "Errore: " . $e->getMessage();
}
