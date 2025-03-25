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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

<div class="max-w-3xl w-full bg-white rounded-lg shadow-md p-6">
    <div class="text-center">
        <img src="/uploads/profile_pics/<?php echo $_SESSION['profile_pic'] ?? 'default_profile.jpg'; ?>" 
             alt="Profil" class="w-32 h-32 mx-auto rounded-full border-4 border-blue-500 shadow-md">
        <h2 class="mt-4 text-2xl font-semibold" id="usernameDisplay"><?php echo $_SESSION['username']; ?></h2>
        <p class="text-gray-600" id="emailDisplay"><?php echo $_SESSION['email']; ?></p>
        <p class="text-gray-500 text-sm mt-2">Inscrit depuis le : <?php echo $_SESSION['created_at'] ?? 'N/A'; ?></p>
    </div>

    <!-- Informations supplémentaires -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-4">Informations du Compte</h3>
        <ul class="space-y-2">
            <li><strong>Nom d'utilisateur :</strong> <span id="usernameInfo"><?php echo $_SESSION['username']; ?></span></li>
            <li><strong>Email :</strong> <span id="emailInfo"><?php echo $_SESSION['email']; ?></span></li>
            <li><strong>Dernière connexion :</strong> <?php echo $_SESSION['last_login'] ?? 'Aucune'; ?></li>
        </ul>
    </div>

    <!-- Boutons d'action -->
    <div class="mt-6 flex justify-between">
        <button onclick="toggleEditForm()" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Modifier le Profil</button>
        <a href="/index.php?action=loginHistory" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">📊 Historique de Connexion</a>
        <a href="/index.php?action=logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Se Déconnecter</a>
    </div>

    <!-- Formulaire de modification -->
    <div id="editForm" class="mt-6 bg-gray-100 p-4 rounded shadow hidden">
        <h3 class="text-lg font-semibold mb-4">Modifier les informations</h3>
        <form id="updateProfileForm">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

            <div class="mb-4">
                <label for="username" class="block text-gray-700">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>" 
                       class="border p-2 w-full rounded" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" 
                       class="border p-2 w-full rounded" required>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Enregistrer</button>
            <button type="button" onclick="toggleEditForm()" class="bg-gray-400 text-white px-4 py-2 rounded ml-2">Annuler</button>
        </form>

        <div id="successMessage" class="mt-4 text-green-600 hidden">Informations mises à jour avec succès !</div>
        <div id="errorMessage" class="mt-4 text-red-600 hidden"></div>
    </div>
</div>

<script>
// Fonction pour basculer l'affichage du formulaire
function toggleEditForm() {
    document.getElementById('editForm').classList.toggle('hidden');
}

// Gérer la soumission du formulaire
document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/index.php?action=updateProfile', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('successMessage').classList.remove('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
            
            // Mettre à jour les informations affichées
            document.getElementById('usernameDisplay').textContent = formData.get('username');
            document.getElementById('emailDisplay').textContent = formData.get('email');
            document.getElementById('usernameInfo').textContent = formData.get('username');
            document.getElementById('emailInfo').textContent = formData.get('email');

            // Cacher le formulaire après mise à jour
            setTimeout(() => {
                toggleEditForm();
            }, 2000);
        } else {
            document.getElementById('errorMessage').textContent = data.message;
            document.getElementById('errorMessage').classList.remove('hidden');
        }
    })
    .catch(err => {
        document.getElementById('errorMessage').textContent = "Une erreur s'est produite.";
        document.getElementById('errorMessage').classList.remove('hidden');
    });
});
</script>

</body>
</html>
