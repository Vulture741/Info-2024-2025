<?php
// ⚙️ CONFIG
$host = 'localhost';
$db   = 'historical_articles';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  die("❌ Connessione fallita: " . $e->getMessage());
}

// ✍️ PARAMETRI UTENTE
$username = 'u';
$password_plain = 'u';
$role = 'utente';

// 🔐 HASH PASSWORD
$hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

// 💾 QUERY
$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $hashed_password, $role]);

echo "✅ Utente creato con successo! Username: <b>$username</b>, Role: <b>$role</b>";
