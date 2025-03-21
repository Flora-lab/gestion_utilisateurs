<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/User.php';

class AdminController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function dashboard() {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
            $_SESSION['error'] = "Accès refusé.";
            header("Location: /index.php?action=welcome");
            exit();
        }
        $stats = [
            'total_users' => $this->userModel->countUsers(),
            'total_clients' => $this->userModel->countClients(),
            'total_admins' => $this->userModel->countAdmins()
        ];

        // Récupérer les données pour le tableau de bord
        $totalUsers = $this->userModel->countUsers();
        $totalClients = $this->userModel->countClients();
        $totalAdmins = $this->userModel->countAdmins();
        $recentUsers = $this->userModel->getRecentUsers();
        $loginLogs = $this->userModel->getLoginLogs();
        $users = $this->userModel->getAllUsers();

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function deleteUser($userId) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
            $_SESSION['error'] = "Accès refusé.";
            header("Location: /index.php?action=welcome");
            exit();
        }

        if ($this->userModel->deleteUser($userId)) {
            $_SESSION['success'] = "Utilisateur supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }

        header("Location: /index.php?action=dashboard");
        exit();
    }

    public function toggleUserStatus($userId) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
            $_SESSION['error'] = "Accès refusé.";
            header("Location: /index.php?action=welcome");
            exit();
        }

        if ($this->userModel->toggleStatus($userId)) {
            $_SESSION['success'] = "Statut de l'utilisateur mis à jour.";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du statut.";
        }

        header("Location: /index.php?action=dashboard");
        exit();
    }

    public function updateUser($userId, $username, $email, $role) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
            $_SESSION['error'] = "Accès refusé.";
            header("Location: /index.php?action=welcome");
            exit();
        }

        if ($this->userModel->updateUser($userId, $username, $email, $role)) {
            $_SESSION['success'] = "Utilisateur mis à jour avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour.";
        }

        header("Location: /index.php?action=dashboard");
        exit();
    }

    public function updateProfilePicture($userId, $file) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $userId) {
            $_SESSION['error'] = "Accès refusé.";
            header("Location: /index.php?action=welcome");
            exit();
        }

        $targetDir = __DIR__ . "/../uploads/";
        $targetFile = $targetDir . basename($file["name"]);

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            $this->userModel->updateProfilePicture($userId, $file["name"]);
            $_SESSION['success'] = "Photo de profil mise à jour.";
        } else {
            $_SESSION['error'] = "Erreur lors du téléchargement.";
        }

        header("Location: /index.php?action=dashboard");
        exit();
    }
}
?>
