<?php
require_once __DIR__ . '/../core/Database.php';

$pdo = Database::connect();

$username = 'admin';
$email = 'admin@example.com';
$password = password_hash('admin', PASSWORD_BCRYPT);
$role_id = 1; 

$sql = "INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$username, $email, $password, $role_id])) {
    echo "Admin ajouté avec succès !";
} else {
    echo "Erreur lors de l'insertion.";
}
?>