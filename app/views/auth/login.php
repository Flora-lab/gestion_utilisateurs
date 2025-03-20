<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="bg-white p-6 rounded-xl shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center text-indigo-600">Connexion</h2>
        <p class="text-center text-gray-500 mb-4">Connectez-vous à votre compte</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="/index.php?action=login" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom d'utilisateur :</label>
                <input type="text" name="username" required autocomplete="off"

                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            

            <div>
                <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                Se connecter
            </button>
        </form>

        <div class="flex justify-between mt-4 text-sm">
            <p>Pas encore inscrit ?<a href="/index.php?action=register" class="text-indigo-600"> S'inscrire</a></p>
        </div>
    </div>

</body>
</html>
