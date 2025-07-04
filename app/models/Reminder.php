<?php

class Reminder {

    public function getAllByUser($userId) {
        $db = db_connect();
        if ($db === null) {
            return [];
        }
        $statement = $db->prepare("SELECT * FROM reminders WHERE user_id = :user_id AND deleted = FALSE ORDER BY created_at DESC");
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id, $userId) {
        $db = db_connect();
        if ($db === null) {
            return null;
        }
        $statement = $db->prepare("SELECT * FROM reminders WHERE id = :id AND user_id = :user_id AND deleted = FALSE");
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create($userId, $subject, $content = '') {
        $db = db_connect();
        if ($db === null) {
            return false;
        }
        $statement = $db->prepare("INSERT INTO reminders (user_id, subject, content) VALUES (:user_id, :subject, :content)");
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':subject', $subject, PDO::PARAM_STR);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        return $statement->execute();
    }

    public function update($id, $userId, $subject, $content, $completed = false) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }
        $statement = $db->prepare("UPDATE reminders SET subject = :subject, content = :content, completed = :completed WHERE id = :id AND user_id = :user_id");
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':subject', $subject, PDO::PARAM_STR);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        $statement->bindValue(':completed', $completed, PDO::PARAM_BOOL);
        return $statement->execute();
    }

    public function delete($id, $userId) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }
        $statement = $db->prepare("UPDATE reminders SET deleted = TRUE WHERE id = :id AND user_id = :user_id");
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        return $statement->execute();
    }

    public function toggleCompleted($id, $userId) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }
        $statement = $db->prepare("UPDATE reminders SET completed = NOT completed WHERE id = :id AND user_id = :user_id");
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        return $statement->execute();
    }
}