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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

<div class="max-w-3xl w-full bg-white rounded-lg shadow-md p-6">
    <div class="text-center">
        <img src="/uploads/profile_pics/<?php echo $_SESSION['profile_pic'] ?? 'default_admin.jpg'; ?>" 
             alt="Profil" class="w-32 h-32 mx-auto rounded-full border-4 border-green-500 shadow-md">
        <h2 class="mt-4 text-2xl font-semibold"><?php echo $_SESSION['username']; ?></h2>
        <p class="text-gray-600"><?php echo $_SESSION['email']; ?></p>
        <p class="text-gray-500 text-sm mt-2">Administrateur depuis le : <?php echo $_SESSION['created_at']; ?></p>
    </div>

    <!-- Informations supplémentaires -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-4">Informations du Compte</h3>
        <ul class="space-y-2">
            <li><strong>Nom d'utilisateur :</strong> <?php echo $_SESSION['username']; ?></li>
            <li><strong>Email :</strong> <?php echo $_SESSION['email']; ?></li>
            <li><strong>Dernière connexion :</strong> <?php echo $_SESSION['last_login'] ?? 'Aucune'; ?></li>
        </ul>
    </div>

    <!-- Boutons d'action -->
    <div class="mt-6 flex justify-between">
        <a href="/index.php?action=editProfileAdmin" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Modifier le Profil</a>
        <a href="/index.php?action=dashboard" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Dashboard</a>
        <a href="/index.php?action=loginHistory" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">📊 Historique de Connexion</a>
        <a href="/index.php?action=logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Se Déconnecter</a>
    </div>
</div>

</body>
</html>

