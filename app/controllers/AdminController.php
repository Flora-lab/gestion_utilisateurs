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

    public function deleteUser($data) {
        $userId = $data['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => "ID utilisateur manquant."]);
            exit();
        }
    
        $result = $this->userModel->deleteUser($userId);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => "Erreur lors de la suppression."]);
        }
        exit(); // IMPORTANT : Empêche l'affichage HTML
    }
    
    public function toggleUserStatus($data) {
        $userId = $data['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => "ID utilisateur manquant."]);
            exit();
        }
    
        $result = $this->userModel->toggleStatus($userId);
        if ($result) {
            $newStatus = $this->userModel->getUserStatus($userId);
            echo json_encode(['success' => true, 'new_status' => $newStatus]);
        } else {
            echo json_encode(['success' => false, 'message' => "Erreur lors du changement de statut."]);
        }
        exit(); // IMPORTANT : Empêche l'affichage HTML
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

    public function editUser($data) {
        $userId = $data['user_id'] ?? null;
        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
    
        if (!$userId || !$username || !$email) {
            echo json_encode(['success' => false, 'message' => "Tous les champs sont requis."]);
            exit();
        }
    
        // Log pour vérifier les données reçues
        error_log("Données reçues: userId = $userId, username = $username, email = $email");
    
        $result = $this->userModel->editUser($userId, $username, $email);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => "Erreur lors de la mise à jour."]);
        }
        exit();
    }
    
    public function addUser($data) {
        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
    
        if (!$username || !$email || !$password) {
            echo json_encode(['success' => false, 'message' => "Tous les champs sont requis."]);
            exit();
        }
    
        $result = $this->userModel->createUserByAdmin($username, $email, $password, 2); // 2 = rôle Client
    
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => "Erreur lors de l'ajout de l'utilisateur."]);
        }
        exit(); // IMPORTANT : Empêche l'affichage HTML
    }
    
    

}
?>
