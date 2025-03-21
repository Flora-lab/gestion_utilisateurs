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
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">

    <!-- Sidebar -->
    <div class="w-64 bg-blue-900 text-white flex flex-col p-4">
        <h2 class="text-xl font-bold">SiteName</h2>
        <ul class="mt-6 space-y-4">
            <li><a href="/index.php?action=dashboard" class="block px-4 py-2 rounded hover:bg-blue-700">Dashboard</a></li>
            <li><a href="/index.php?action=students" class="block px-4 py-2 rounded hover:bg-blue-700">Students</a></li>
            <li><a href="/index.php?action=logout" class="block px-4 py-2 rounded hover:bg-red-600">Déconnexion</a></li>
        </ul>
    </div>

    <!-- Contenu Principal -->
    <div class="flex-1 p-6">
        <header class="flex justify-between items-center bg-white shadow p-4 rounded">
            <h1 class="text-xl font-semibold">Tableau de bord</h1>
            <div class="relative">
                <img src="/uploads/<?php echo $_SESSION['profile_pic'] ?? 'default.jpg'; ?>" class="w-10 h-10 rounded-full border" alt="Profil">
                <div class="absolute right-0 mt-2 w-48 bg-white shadow rounded hidden" id="profileMenu">
                    <a href="/index.php?action=editProfile" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Modifier Profil</a>
                    <a href="/index.php?action=logout" class="block px-4 py-2 text-red-600 hover:bg-gray-200">Se Déconnecter</a>
                </div>
            </div>
        </header>

        <!-- Stats -->
        <section class="grid grid-cols-3 gap-6 mt-6">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold">Utilisateurs</h3>
                <p class="text-2xl font-bold text-blue-600"><?php echo $stats['total_users']; ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold">Clients</h3>
                <p class="text-2xl font-bold text-green-600"><?php echo $stats['total_clients']; ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold">Administrateurs</h3>
                <p class="text-2xl font-bold text-red-600"><?php echo $stats['total_admins']; ?></p>
            </div>
        </section>

        <!-- Nouveaux utilisateurs -->
        <section class="mt-6 bg-white p-6 rounded shadow">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Nouveaux utilisateurs</h3>
                <button onclick="window.location.href='/index.php?action=addUser'"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</button>
            </div>
            <ul class="mt-4 space-y-2">
                <?php foreach ($recentUsers as $user): ?>
                    <li class="text-gray-700"><?php echo $user['username']; ?> - <?php echo $user['email']; ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Logs de connexion -->
        <section class="mt-6 bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold">Logs de connexion</h3>
            <canvas id="loginChart"></canvas>
        </section>

        <!-- Liste des utilisateurs -->
        <section class="mt-6 bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold">Liste des utilisateurs</h3>
            <table class="w-full mt-4 border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4">Nom</th>
                        <th class="py-2 px-4">Email</th>
                        <th class="py-2 px-4">Rôle</th>
                        <th class="py-2 px-4">Statut</th>
                        <th class="py-2 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-t">
                            <td class="py-2 px-4"><?php echo $user['username']; ?></td>
                            <td class="py-2 px-4"><?php echo $user['email']; ?></td>
                            <td class="py-2 px-4"><?php echo $user['role']; ?></td>
                            <td class="py-2 px-4"><?php echo $user['status']; ?></td>
                            <td class="py-2 px-4">
                                <button onclick="modifyUser(<?php echo $user['id']; ?>)"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded">Modifier</button>
                                <button onclick="deleteUser(<?php echo $user['id']; ?>)"
                                    class="bg-red-500 text-white px-2 py-1 rounded">Supprimer</button>
                                <button onclick="toggleStatus(<?php echo $user['id']; ?>)"
                                    class="bg-gray-500 text-white px-2 py-1 rounded">
                                    <?php echo $user['status'] == 'active' ? 'Désactiver' : 'Activer'; ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</div>

<script>
    var loginData = <?php echo json_encode($loginLogs ?? []); ?>;
    var labels = loginData.map(d => d.day);
    var counts = loginData.map(d => d.count);

    var ctx = document.getElementById('loginChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Connexions par jour',
                data: counts,
                backgroundColor: 'orange',
                borderColor: 'red',
                borderWidth: 1
            }]
        }
    });

    // Menu déroulant du profil
    document.querySelector('.profile img').addEventListener('click', function() {
        document.getElementById('profileMenu').classList.toggle('hidden');
    });
</script>

</body>
</html>

