<?php
require_once '../config/db.php';

$username = 'a';
$password = 'a';
$role = 'admin';

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $hash, $role]);

echo "✅ Admin creato: $username | Password: $password";
