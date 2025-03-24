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
                <button onclick="openAddUserPopup()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter un utilisateur</button>
                <!-- Popup pour ajouter un utilisateur -->
            <div id="addUserPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-2xl font-semibold mb-4">Ajouter un utilisateur</h2>
                    <form id="addUserForm">
                        <div>
                            <label class="block mb-2">Nom d'utilisateur</label>
                            <input type="text" name="username" class="border p-2 w-full rounded" required>
                        </div>
                        <div>
                            <label class="block mb-2">Email</label>
                            <input type="email" name="email" class="border p-2 w-full rounded" required>
                        </div>
                        <div>
                            <label class="block mb-2">Mot de passe</label>
                            <input type="password" name="password" class="border p-2 w-full rounded" required>
                        </div>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Ajouter</button>
                        <button type="button" onclick="closeAddUserPopup()" class="bg-gray-400 text-white px-4 py-2 rounded ml-2">Annuler</button>
                    </form>
                    <div id="addUserMessage" class="mt-4 text-green-600 hidden">Utilisateur ajouté avec succès !</div>
                    <div id="addUserError" class="mt-4 text-red-600 hidden">Une erreur s'est produite.</div>
                </div>
            </div>
            <!-- Ouvrir le popup d'ajout -->
        <script>
        function openAddUserPopup() {
            document.getElementById('addUserPopup').classList.remove('hidden');
        }

        // Fermer le popup d'ajout
        function closeAddUserPopup() {
            document.getElementById('addUserPopup').classList.add('hidden');
        }

        // Gérer la soumission du formulaire d'ajout
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/index.php?action=addUser', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('addUserMessage').classList.remove('hidden');
                    document.getElementById('addUserError').classList.add('hidden');

                    // Mise à jour du tableau sans rechargement
                    const userList = document.querySelector('tbody'); // Assurez-vous que tbody a un bon ID ou est bien sélectionné
                    const newRow = `
                        <tr class="border-t">
                            <td class="py-2 px-4">${formData.get('username')}</td>
                            <td class="py-2 px-4">${formData.get('email')}</td>
                            <td class="py-2 px-4">Client</td>
                            <td class="py-2 px-4">Active</td>
                            <td class="py-2 px-4">
                                <button class="bg-yellow-500 text-white px-2 py-1 rounded">Modifier</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded">Supprimer</button>
                                <button class="bg-gray-500 text-white px-2 py-1 rounded">Désactiver</button>
                            </td>
                        </tr>
                    `;
                    userList.insertAdjacentHTML('beforeend', newRow);

                    // Fermer le popup après 2 secondes
                    setTimeout(() => {
                        closeAddUserPopup();
                    }, 2000);
                } else {
                    document.getElementById('addUserError').textContent = data.message;
                    document.getElementById('addUserError').classList.remove('hidden');
                }
            })
            .catch(err => {
                document.getElementById('addUserError').textContent = "Une erreur s'est produite : " + err;
                document.getElementById('addUserError').classList.remove('hidden');
            });
        });

        </script>

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
                                <!-- Bouton Modifier -->
                                <button onclick="openEditModal(<?php echo $user['id']; ?>, '<?php echo $user['username']; ?>', '<?php echo $user['email']; ?>')" 
                                        class="text-white px-2 py-1 rounded">🖊️</button>

                                <!-- Modal Popup -->
                                <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                                        <h2 class="text-2xl font-semibold mb-4">Modifier l'utilisateur</h2>
                                        <form id="editForm">
                                            <input type="hidden" name="user_id" id="userId">
                                            <div class="mb-4">
                                                <label class="block text-gray-700">Nom d'utilisateur</label>
                                                <input type="text" id="username" name="username" class="border p-2 w-full rounded" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-gray-700">Email</label>
                                                <input type="email" id="email" name="email" class="border p-2 w-full rounded" required>
                                            </div>
                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Modifier</button>
                                            <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Annuler</button>
                                        </form>
                                        <div id="editMessage" class="mt-4 text-green-600 hidden">Modifications enregistrées !</div>
                                        <div id="editErrorMessage" class="mt-4 text-red-600 hidden"></div>
                                    </div>
                                </div>

                                <script>
                                // Ouvrir le modal avec les valeurs existantes
                                function openEditModal(userId, username, email) {
                                    document.getElementById('userId').value = userId;
                                    document.getElementById('username').value = username;
                                    document.getElementById('email').value = email;
                                    document.getElementById('editModal').classList.remove('hidden');
                                }

                                // Fermer le modal
                                function closeEditModal() {
                                    document.getElementById('editModal').classList.add('hidden');
                                }

                                // Gérer la soumission du formulaire
                                document.getElementById('editForm').addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    const formData = new FormData(this);

                                    fetch('/index.php?action=editUser', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            document.getElementById('editMessage').classList.remove('hidden');
                                            document.getElementById('editErrorMessage').classList.add('hidden');
                                            setTimeout(() => location.reload(), 1500); // Recharger après 1,5s
                                        } else {
                                            document.getElementById('editErrorMessage').textContent = data.message;
                                            document.getElementById('editErrorMessage').classList.remove('hidden');
                                        }
                                    })
                                    .catch(err => {
                                        document.getElementById('editErrorMessage').textContent = "Une erreur s'est produite.";
                                        document.getElementById('editErrorMessage').classList.remove('hidden');
                                    });
                                });
                                </script>

                                <button onclick="deleteUser(<?php echo $user['id']; ?>)"
                                    class="text-white px-2 py-1 rounded">🗑️</button>
                                <button onclick="toggleStatus(<?php echo $user['id']; ?>)"
                                    class=" text-white px-2 py-1 rounded">
                                    <?php echo $user['status'] == 'active' ? '🔕' : 'Activer'; ?>
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

