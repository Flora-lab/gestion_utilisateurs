<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/controllers/AuthController.php';

class Router {
    public static function route() {
        $authController = new AuthController();
        $action = $_GET['action'] ?? 'home';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($action === "register") {
                $username = $_POST['username'] ?? null;
                $email = $_POST['email'] ?? null;
                $password = $_POST['password'] ?? null;
                $confirm_password = $_POST['confirm_password'] ?? null;

                if ($username && $email && $password && $confirm_password) {
                    $authController->register($username, $email, $password, $confirm_password);
                } else {
                    $_SESSION['error'] = "Tous les champs sont requis.";
                    header("Location: /index.php?action=register");
                    exit();
                }
            } elseif ($action === "login") {
                $email = $_POST['email'] ?? null;
                $password = $_POST['password'] ?? null;

                if ($email && $password) {
                    $authController->login($email, $password);
                } else {
                    $_SESSION['error'] = "Email et mot de passe requis.";
                    header("Location: /index.php?action=login");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Action non autorisée.";
                header("Location: /index.php?action=home");
                exit();
            }
        } else {
            switch ($action) {
                case "home":
                    require_once __DIR__ . '/../app/views/home.php';
                    break;
                case "register":
                    require_once __DIR__ . '/../app/views/auth/register.php';
                    break;
                case "login":
                    require_once __DIR__ . '/../app/views/auth/login.php';
                    break;
                case "welcome":
                    // Vérification de session pour éviter une redirection vers home
                    if (!isset($_SESSION['user_id'])) {
                        $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
                        header("Location: /index.php?action=login");
                        exit();
                    }
                    require_once __DIR__ . '/../app/views/auth/welcome.php';
                    break;
                case "profile":
                    require_once __DIR__ . '/../app/views/auth/profile.php';
                    break;
                case "logout":
                    $authController->logout();
                    break;
                default:
                    $_SESSION['error'] = "Page non trouvée.";
                    header("Location: /index.php?action=home");
                    exit();
            }
        }
    }
}
?>
