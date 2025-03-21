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

    /**
     * Inscription d'un nouvel utilisateur
     */
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

        if ($this->userModel->getUserByUsername($username)) {
            $_SESSION['error'] = "Ce nom d'utilisateur est déjà utilisé.";
            header("Location: /index.php?action=register");
            exit();
        }

        if ($this->userModel->createUser($username, $email, $password, 2)) {
            $_SESSION['success'] = "Inscription réussie ! Connectez-vous.";
            header("Location: /index.php?action=login");
            exit();
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de l'inscription.";
            header("Location: /index.php?action=register");
            exit();
        }
    }

    /**
     * Connexion de l'utilisateur
     */
    public function login($username, $password) {
        $user = $this->userModel->getUserByUsername($username);

        if (!$user) { 
            $_SESSION['error'] = "Aucun utilisateur trouvé avec ce nom d'utilisateur.";
            header("Location: /index.php?action=login");
            exit();
        }

        if (!password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Mot de passe incorrect.";
            header("Location: /index.php?action=login");
            exit();
        }

        // Stocker les infos de l'utilisateur après connexion réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $this->userModel->getUserRole($user['id']); 

        // Mettre à jour le dernier login
        $this->userModel->updateLastLogin($user['id']);

        // Redirection selon le rôle
        if ($_SESSION['role'] === "Admin") {
            header("Location: /index.php?action=profile_admin");
        } else {
            header("Location: /index.php?action=profile_user");
        }
        exit();
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout() {
        session_destroy();
        header("Location: /index.php?action=home");
        exit();
    }
}
?>
