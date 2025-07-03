<?php

class Reminder {

    public function __construct() {

    }

    public function getAllByUser($user_id) {
        $db = db_connect();
        if ($db === null) {
            return ['error' => 'Database connection failed'];
        }

        $statement = $db->prepare("SELECT * FROM reminders WHERE user_id = :user_id AND deleted = 0 ORDER BY created_at DESC");
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($user_id, $subject, $content = '') {
        $db = db_connect();
        if ($db === null) {
            return false;
        }

        $statement = $db->prepare("INSERT INTO reminders (user_id, subject, content) VALUES (:user_id, :subject, :content)");
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':subject', $subject);
        $statement->bindValue(':content', $content);
        return $statement->execute();
    }

    public function getById($id, $user_id) {
        $db = db_connect();
        if ($db === null) {
            return null;
        }

        $statement = $db->prepare("SELECT * FROM reminders WHERE id = :id AND user_id = :user_id AND deleted = 0");
        $statement->bindValue(':id', $id);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $user_id, $subject, $content = '', $completed = 0) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }

        $statement = $db->prepare("UPDATE reminders SET subject = :subject, content = :content, completed = :completed WHERE id = :id AND user_id = :user_id");
        $statement->bindValue(':subject', $subject);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':completed', $completed);
        $statement->bindValue(':id', $id);
        $statement->bindValue(':user_id', $user_id);
        return $statement->execute();
    }

    public function delete($id, $user_id) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }

        $statement = $db->prepare("UPDATE reminders SET deleted = 1 WHERE id = :id AND user_id = :user_id");
        $statement->bindValue(':id', $id);
        $statement->bindValue(':user_id', $user_id);
        return $statement->execute();
    }

    public function toggleComplete($id, $user_id) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }

        $statement = $db->prepare("UPDATE reminders SET completed = NOT completed WHERE id = :id AND user_id = :user_id");
        $statement->bindValue(':id', $id);
        $statement->bindValue(':user_id', $user_id);
        return $statement->execute();
    }
}