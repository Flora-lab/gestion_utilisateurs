<?php
require_once __DIR__ . '/../../core/Database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function createUserByAdmin($username, $email, $password, $role_id) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (username, email, password, role_id, status, created_at) 
                    VALUES (?, ?, ?, ?, 'active', NOW())";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$username, $email, $hashedPassword, $role_id]);
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return false;
        }
    }

    public function createUser($username, $email, $password, $role_id = 2) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, email, password, role_id, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$username, $email, $hashedPassword, $role_id]);
    }

    public function getUserRole($userId) {
        $sql = "SELECT roles.name FROM users 
                JOIN roles ON users.role_id = roles.id 
                WHERE users.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        return $role ? $role['name'] : 'Client';
    }

    public function countUsers() {
        $sql = "SELECT COUNT(*) AS total FROM users";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function countClients() {
        $sql = "SELECT COUNT(*) AS total FROM users WHERE role_id = 2";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function countAdmins() {
        $sql = "SELECT COUNT(*) AS total FROM users WHERE role_id = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function getAllUsers() {
        $sql = "SELECT users.id, users.username, users.email, roles.name AS role, users.status FROM users 
                JOIN roles ON users.role_id = roles.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteUser($userId) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId]);
    }
    public function logLogin($userId, $ipAddress) {
        $sql = "INSERT INTO login_logs (user_id, ip_address) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $ipAddress]);
    }
    
    
    public function toggleStatus($userId) {
        $sql = "SELECT status FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            $newStatus = ($user['status'] === 'active') ? 'inactive' : 'active';
            $updateSql = "UPDATE users SET status = ? WHERE id = ?";
            $updateStmt = $this->pdo->prepare($updateSql);
            return $updateStmt->execute([$newStatus, $userId]);
        }
        return false;
    }
    public function getLoginLogsByUserId($userId) {
        $sql = "SELECT login_time, ip_address FROM login_logs WHERE user_id = ? ORDER BY login_time DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserStatus($userId) {
        $sql = "SELECT status FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['status'] : null;
    }
    

    public function updateUser($userId, $username, $email, $role_id) {
        $sql = "UPDATE users SET username = ?, email = ?, role_id = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$username, $email, $role_id, $userId]);
    }

    public function updateUserStatus($userId, $status) {
        $sql = "UPDATE users SET status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $userId]);
    }

    public function updateLastLogin($userId) {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId]);
    }

    public function updateUserWithPassword($userId, $username, $email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$username, $email, $hashedPassword, $userId]);
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return false;
        }
    }
    

    public function getRecentUsers($limit = 5) {
        $sql = "SELECT username, email FROM users ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLoginLogs() {
        $sql = "SELECT DATE(last_login) as day, COUNT(*) as count FROM users WHERE last_login IS NOT NULL GROUP BY day ORDER BY day DESC LIMIT 7";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function verifyUser($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user && password_verify($password, $user['password']) ? $user : false;
    }

    public function getUserById($id) {
        $sql = "SELECT id, username, email, role_id, created_at, last_login FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function editUser($userId, $username, $email) {
        try {
            $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$username, $email, $userId]);
    
            if (!$result) {
                error_log("Erreur SQL: " . implode(" | ", $stmt->errorInfo()));
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Exception PDO: " . $e->getMessage());
            return false;
        }
    }
    
    
}
?>