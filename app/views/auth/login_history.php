<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php?action=login");
    exit();
}

// Récupérer l'historique de connexion depuis la base de données
require_once __DIR__ . '/../../models/User.php';
$userModel = new User();
$loginLogs = $userModel->getLoginLogsByUserId($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique de Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-4 flex justify-center items-center">



<div class="max-w-4xl w-full bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-semibold mb-4">📊 Historique de Connexion</h2>
    
    <?php if (empty($loginLogs)): ?>
        <p class="text-gray-500">Aucune connexion trouvée.</p>
    <?php else: ?>
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4">Date</th>
                    <th class="py-2 px-4">Heure</th>
                    <th class="py-2 px-4">Adresse IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loginLogs as $log): ?>
                    <tr class="border-t">
                        <td class="py-2 px-4"><?php echo date('d/m/Y', strtotime($log['login_time'])); ?></td>
                        <td class="py-2 px-4"><?php echo date('H:i:s', strtotime($log['login_time'])); ?></td>
                        <td class="py-2 px-4"><?php echo $log['ip_address']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="mt-4">
        <a href="/index.php?action=profile_user" class="bg-blue-500 text-white px-4 py-2 rounded">Retour au Profil</a>
    </div>
</div>

</body>
</html>
