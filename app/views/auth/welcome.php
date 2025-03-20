<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php?action=login");
    exit();
}

$username = $_SESSION['username'] ?? 'Utilisateur';
$role = $_SESSION['role'] ?? 'Client';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    
    <div class="bg-white p-6 rounded-xl shadow-lg w-96 text-center">
        <h2 class="text-2xl font-bold text-indigo-600">Bienvenue, <?= htmlspecialchars($username) ?> !</h2>
        <p class="text-gray-500 mt-2">Vous êtes connecté en tant que <strong><?= htmlspecialchars($role) ?></strong></p>

        <div class="mt-4 space-y-2">
            <a href="/index.php?action=profile" class="block bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                Voir mon profil
            </a>
            <a href="/index.php?action=logout" class="block bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                Déconnexion
            </a>
        </div>
    </div>

</body>
</html>
