<?php
require_once __DIR__ . '/../config/db.php';

class UserModel {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare(
            "SELECT id, name, email, role, profile_picture, created_at FROM users WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function updateProfile($id, $name, $email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return ['success' => false, 'message' => 'Email already in use by another account.'];
        }
        $stmt->close();

        $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $id);
        $stmt->execute();
        $stmt->close();
        return ['success' => true];
    }

    public function updateProfilePicture($id, $picturePath) {
        $stmt = $this->conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        $stmt->bind_param("si", $picturePath, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function changePassword($id, $currentPassword, $newPassword) {
        $stmt = $this->conn->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect.'];
        }

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $id);
        $stmt->execute();
        $stmt->close();
        return ['success' => true];
    }
}
?>
