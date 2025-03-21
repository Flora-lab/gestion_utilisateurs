<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: /index.php?action=home");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Administrateur</title>
</head>
<body>
    <h1>Profil Admin : <?php echo $_SESSION['username']; ?></h1>
    <p>Email : <?php echo $_SESSION['email'] ?? 'Non disponible'; ?></p>
    <a href="/index.php?action=dashboard">Dashboard</a> | 
    <a href="/index.php?action=logout">Déconnexion</a>
</body>
</html>
