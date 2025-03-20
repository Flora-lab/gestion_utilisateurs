<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register($username, $email, $password, $confirm_password) {
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
            header("Location: /index.php?action=register");
            exit();
        }

        if ($this->userModel->getUserByEmail($email)) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
            header("Location: /index.php?action=register");
            exit();
        }

        if ($this->userModel->createUser($username, $email, $password)) {
            $_SESSION['success'] = "Inscription réussie ! Connectez-vous.";
            header("Location: /index.php?action=login");
            exit();
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de l'inscription.";
            header("Location: /index.php?action=register");
            exit();
        }
    }

    public function login($username, $password) {
        $userModel = new User();
        $user = $userModel->getUserByUsername($username); // Nouvelle méthode dans User.php
    
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username']; // Stocke le nom d'utilisateur
            header("Location: /index.php?action=welcome");
            exit();
        } else {
            $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
            header("Location: /index.php?action=login");
            exit();
        }
        if (!$user) { // Vérifier si l'utilisateur existe
            $_SESSION['error'] = "Aucun utilisateur trouvé avec cet email.";
            header("Location: /index.php?action=login");
            exit();
        }
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $this->userModel->getUserRole($user['id']); // Récupérer le rôle
            if ($_SESSION['role'] === "Admin") {
                header("Location: /index.php?action=dashboard");
            } else {
                header("Location: /index.php?action=welcome");
            }
            exit();
    

        } else {
            $_SESSION['error'] = "Mot de passe incorrect.";
            header("Location: /index.php?action=login");
            exit();
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /index.php?action=home");
        exit();
    }
}
?>
