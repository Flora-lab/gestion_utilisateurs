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
    <title>Modifier le Profil - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-md max-w-lg w-full">
        <h2 class="text-2xl font-semibold mb-4 text-center">Modifier le Profil Admin</h2>
        
        <form action="/index.php?action=updateProfileAdmin" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Nom d'utilisateur</label>
                <input type="text" name="username" value="<?php echo $_SESSION['username']; ?>" 
                    class="border p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>" 
                    class="border p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Mot de passe (laisser vide si inchangé)</label>
                <input type="password" name="password" class="border p-2 w-full rounded">
            </div>
            <div class="flex justify-end">
                <button type="submit" 
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Mettre à jour</button>
                <a href="/index.php?action=profile_admin" 
                    class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
