
<?php

class Reminders {
    private $db;

    public function __construct() {
        try {
            if (DB_TYPE === 'pgsql') {
                $this->db = new PDO("pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATABASE, DB_USER, DB_PASS);
            } else {
                $this->db = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATABASE, DB_USER, DB_PASS);
            }
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getAllByUser($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM notes WHERE user_id = ? AND deleted = 0 ORDER BY created_at DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    public function getByIdAndUser($id, $userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ? AND deleted = 0");
            $stmt->execute([$id, $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function create($userId, $subject, $content = '') {
        try {
            $stmt = $this->db->prepare("INSERT INTO notes (user_id, subject, content, created_at, completed, deleted) VALUES (?, ?, ?, NOW(), 0, 0)");
            return $stmt->execute([$userId, $subject, $content]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $subject, $content, $completed) {
        try {
            $stmt = $this->db->prepare("UPDATE notes SET subject = ?, content = ?, completed = ? WHERE id = ?");
            return $stmt->execute([$subject, $content, $completed, $id]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($id, $completed) {
        try {
            $stmt = $this->db->prepare("UPDATE notes SET completed = ? WHERE id = ?");
            return $stmt->execute([$completed, $id]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("UPDATE notes SET deleted = 1 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
}
