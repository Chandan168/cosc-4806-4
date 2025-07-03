
<?php

require_once 'app/database.php';

class Note {
    
    public function createNote($user_id, $subject, $content = '') {
        $dbh = db_connect();
        if (!$dbh) return false;
        
        try {
            $sql = "INSERT INTO notes (user_id, subject, content, created_at, completed, deleted) VALUES (?, ?, ?, NOW(), 0, 0)";
            $stmt = $dbh->prepare($sql);
            return $stmt->execute([$user_id, $subject, $content]);
        } catch (PDOException $e) {
            error_log("Create note error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getUserNotes($user_id) {
        $dbh = db_connect();
        if (!$dbh) return [];
        
        try {
            $sql = "SELECT * FROM notes WHERE user_id = ? AND deleted = 0 ORDER BY created_at DESC";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get user notes error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getNote($note_id, $user_id) {
        $dbh = db_connect();
        if (!$dbh) return false;
        
        try {
            $sql = "SELECT * FROM notes WHERE id = ? AND user_id = ? AND deleted = 0";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$note_id, $user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get note error: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateNote($note_id, $user_id, $subject, $content = '', $completed = 0) {
        $dbh = db_connect();
        if (!$dbh) return false;
        
        try {
            $sql = "UPDATE notes SET subject = ?, content = ?, completed = ? WHERE id = ? AND user_id = ? AND deleted = 0";
            $stmt = $dbh->prepare($sql);
            return $stmt->execute([$subject, $content, $completed, $note_id, $user_id]);
        } catch (PDOException $e) {
            error_log("Update note error: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteNote($note_id, $user_id) {
        $dbh = db_connect();
        if (!$dbh) return false;
        
        try {
            $sql = "UPDATE notes SET deleted = 1 WHERE id = ? AND user_id = ?";
            $stmt = $dbh->prepare($sql);
            return $stmt->execute([$note_id, $user_id]);
        } catch (PDOException $e) {
            error_log("Delete note error: " . $e->getMessage());
            return false;
        }
    }
    
    public function toggleCompleted($note_id, $user_id) {
        $dbh = db_connect();
        if (!$dbh) return false;
        
        try {
            $sql = "UPDATE notes SET completed = NOT completed WHERE id = ? AND user_id = ? AND deleted = 0";
            $stmt = $dbh->prepare($sql);
            return $stmt->execute([$note_id, $user_id]);
        } catch (PDOException $e) {
            error_log("Toggle completed error: " . $e->getMessage());
            return false;
        }
    }
}
