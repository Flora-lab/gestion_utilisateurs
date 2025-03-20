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
        // Vérifier si l'utilisateur est un admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
            $_SESSION['error'] = "Accès refusé.";
            header("Location: /index.php?action=welcome");
            exit();
        }

        $users = $this->userModel->getAllUsers(); // Récupérer tous les utilisateurs
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
}
?>
