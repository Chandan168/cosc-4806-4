
<?php

class Note {
    
    /**
     * Get all notes for a specific user
     */
    public function getUserNotes($user_id) {
        $db = db_connect();
        if ($db === null) {
            error_log("Database connection failed in Note::getUserNotes()");
            return [];
        }
        
        try {
            $statement = $db->prepare("SELECT * FROM notes WHERE user_id = :user_id AND deleted = 0 ORDER BY created_at DESC");
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user notes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get a specific note by ID and user ID
     */
    public function getNote($note_id, $user_id) {
        $db = db_connect();
        if ($db === null) {
            return null;
        }
        
        try {
            $statement = $db->prepare("SELECT * FROM notes WHERE id = :id AND user_id = :user_id AND deleted = 0");
            $statement->bindValue(':id', $note_id);
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching note: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create a new note
     */
    public function createNote($user_id, $subject, $content = '') {
        $db = db_connect();
        if ($db === null) {
            return false;
        }
        
        try {
            $statement = $db->prepare("INSERT INTO notes (user_id, subject, content, created_at, completed, deleted) VALUES (:user_id, :subject, :content, NOW(), 0, 0)");
            $statement->bindValue(':user_id', $user_id);
            $statement->bindValue(':subject', trim($subject));
            $statement->bindValue(':content', trim($content));
            return $statement->execute();
        } catch (PDOException $e) {
            error_log("Error creating note: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update an existing note
     */
    public function updateNote($note_id, $user_id, $subject, $content = '', $completed = 0) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }
        
        try {
            $statement = $db->prepare("UPDATE notes SET subject = :subject, content = :content, completed = :completed WHERE id = :id AND user_id = :user_id AND deleted = 0");
            $statement->bindValue(':id', $note_id);
            $statement->bindValue(':user_id', $user_id);
            $statement->bindValue(':subject', trim($subject));
            $statement->bindValue(':content', trim($content));
            $statement->bindValue(':completed', $completed);
            return $statement->execute();
        } catch (PDOException $e) {
            error_log("Error updating note: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Soft delete a note
     */
    public function deleteNote($note_id, $user_id) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }
        
        try {
            $statement = $db->prepare("UPDATE notes SET deleted = 1 WHERE id = :id AND user_id = :user_id");
            $statement->bindValue(':id', $note_id);
            $statement->bindValue(':user_id', $user_id);
            return $statement->execute();
        } catch (PDOException $e) {
            error_log("Error deleting note: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle completion status of a note
     */
    public function toggleCompleted($note_id, $user_id) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }
        
        try {
            $statement = $db->prepare("UPDATE notes SET completed = 1 - completed WHERE id = :id AND user_id = :user_id AND deleted = 0");
            $statement->bindValue(':id', $note_id);
            $statement->bindValue(':user_id', $user_id);
            return $statement->execute();
        } catch (PDOException $e) {
            error_log("Error toggling note completion: " . $e->getMessage());
            return false;
        }
    }
}
