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
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="sidebar">
    <h2>SiteName</h2>
    <ul>
        <li><a href="/index.php?action=dashboard">Dashboard</a></li>
        <li><a href="/index.php?action=students">Students</a></li>
        <li><a href="/index.php?action=logout">Déconnexion</a></li>
    </ul>
</div>

<div class="main-content">
    <header>
        <h1>Tableau de bord</h1>
        <div class="profile">
            <img src="/uploads/<?php echo $_SESSION['profile_pic'] ?? 'default.jpg'; ?>" alt="Profil">
            <div class="dropdown">
                <button class="dropbtn"><?php echo $_SESSION['username']; ?></button>
                <div class="dropdown-content">
                    <a href="/index.php?action=editProfile">Modifier Profil</a>
                    <a href="/index.php?action=logout">Se Déconnecter</a>
                </div>
            </div>
        </div>
    </header>

    <section class="stats">
        <div class="stat-card">
            <h3>Utilisateurs</h3>
            <p><?php echo $stats['total_users']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Clients</h3>
            <p><?php echo $stats['total_clients']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Administrateurs</h3>
            <p><?php echo $stats['total_admins']; ?></p>
        </div>
    </section>

    <section class="recent-users">
        <h3>Nouveaux utilisateurs</h3>
        <ul>
            <?php foreach ($recentUsers as $user): ?>
                <li><?php echo $user['username']; ?> - <?php echo $user['email']; ?></li>
            <?php endforeach; ?>
        </ul>
        <button onclick="window.location.href='/index.php?action=addUser'">Ajouter un utilisateur</button>
    </section>

    <section class="logins">
        <h3>Logs de connexion</h3>
        <canvas id="loginChart"></canvas>
    </section>

    <section class="user-list">
        <h3>Liste des utilisateurs</h3>
        <table>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td><?php echo $user['status']; ?></td>
                    <td>
                        <button onclick="modifyUser(<?php echo $user['id']; ?>)">Modifier</button>
                        <button onclick="deleteUser(<?php echo $user['id']; ?>)">Supprimer</button>
                        <button onclick="toggleStatus(<?php echo $user['id']; ?>)">
                            <?php echo $user['status'] == 'active' ? 'Désactiver' : 'Activer'; ?>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
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
</script>

</body>
</html>
