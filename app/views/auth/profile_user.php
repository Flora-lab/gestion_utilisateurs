<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php?action=login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
</head>
<body>
    <h1>Profil de <?php echo $_SESSION['username']; ?></h1>
    <p>Email : <?php echo $_SESSION['email'] ?? 'Non disponible'; ?></p>
    <a href="/index.php?action=home">Accueil</a> |
    <a href="/index.php?action=logout">Déconnexion</a>
</body>
</html>

