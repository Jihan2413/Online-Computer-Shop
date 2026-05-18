<?php
require_once __DIR__ . '/../config/db.php';

class AuthModel {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($name, $email, $password, $role) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return ['success' => false, 'error' => 'email', 'message' => 'Email is already registered.'];
        }
        $stmt->close();

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $name, $email, $hash, $role);

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true];
        }

        $stmt->close();
        return ['success' => false, 'error' => 'db', 'message' => 'Registration failed. Please try again.'];
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare(
            "SELECT id, name, email, password_hash, role, profile_picture FROM users WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt->close();
            return false;
        }

        $user = $result->fetch_assoc();
        $stmt->close();

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        return $user;
    }

    public function saveRememberToken($userId, $token) {
        $hash = password_hash($token, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function nameExists($name) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE LOWER(name) = LOWER(?) LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function getUserByRememberToken($token) {
        $stmt = $this->conn->prepare(
            "SELECT id, name, email, role, profile_picture, remember_token FROM users"
        );
        $stmt->execute();
        $result = $stmt->get_result();

        while ($user = $result->fetch_assoc()) {
            if ($user['remember_token'] && password_verify($token, $user['remember_token'])) {
                $stmt->close();
                return $user;
            }
        }
        $stmt->close();
        return null;
    }

    public function clearRememberToken($userId) {
        $stmt = $this->conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }
}
?>
