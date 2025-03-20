<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-purple-800 text-white h-screen p-6">
            <h2 class="text-2xl font-bold mb-6">SiteName</h2>
            <nav>
                <ul>
                    <li class="mb-4"><a href="#" class="flex items-center space-x-2"><span>🏢</span><span>Dashboard</span></a></li>
                    <li class="mb-4"><a href="#">Students</a></li>
                    <li class="mb-4"><a href="#">Teachers</a></li>
                    <li class="mb-4"><a href="#">Events</a></li>
                    <li class="mb-4"><a href="#">Finance</a></li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h1 class="text-3xl font-bold text-gray-700">Tableau de bord</h1>
            
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-6 my-6">
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <h3 class="text-2xl font-bold">932</h3>
                    <p class="text-gray-500">Utilisateurs</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <h3 class="text-2xl font-bold">754</h3>
                    <p class="text-gray-500">Clients</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <h3 class="text-2xl font-bold">01</h3>
                    <p class="text-gray-500">Administrateurs</p>
                </div>
            </div>

            <!-- Calendar & Logs -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold">Calendrier</h3>
                    <p class="text-gray-500">Mars 2025</p>
                    <!-- Placeholder for calendar -->
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold">Logs de connexion</h3>
                    <!-- Placeholder for chart -->
                </div>
            </div>
            
            <!-- Liste des utilisateurs -->
            <div class="bg-white p-6 rounded-lg shadow my-6">
                <h3 class="text-lg font-bold">Liste des utilisateurs</h3>
                <table class="w-full mt-4">
                    <thead>
                        <tr>
                            <th class="text-left">Nom</th>
                            <th class="text-left">ID</th>
                            <th class="text-left">Statut</th>
                            <th class="text-left">Solde</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Samantha William</td>
                            <td>123456789</td>
                            <td>Client VII A</td>
                            <td>$50,036</td>
                        </tr>
                        <!-- Autres lignes -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
