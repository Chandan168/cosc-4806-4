<?php

class Reminders extends Controller {

    public function index() {
        $user_id = $this->getUserId();
        if (!$user_id) {
            header('Location: /login');
            die;
        }

        $reminder = $this->model('Reminder');
        $data['reminders'] = $reminder->getAllByUser($user_id);

        $this->view('reminders/index', $data);
    }

    public function create() {
        $user_id = $this->getUserId();
        if (!$user_id) {
            header('Location: /login');
            die;
        }

        if ($_POST) {
            $reminder = $this->model('Reminder');
            $subject = $_POST['subject'] ?? '';
            $content = $_POST['content'] ?? '';

            if (!empty($subject)) {
                if ($reminder->create($user_id, $subject, $content)) {
                    header('Location: /reminders');
                    die;
                } else {
                    $data['error'] = 'Failed to create reminder';
                }
            } else {
                $data['error'] = 'Subject is required';
            }
        }

        $this->view('reminders/create', $data ?? []);
    }

    public function edit($id = null) {
        $user_id = $this->getUserId();
        if (!$user_id || !$id) {
            header('Location: /reminders');
            die;
        }

        $reminder = $this->model('Reminder');
        $data['reminder'] = $reminder->getById($id, $user_id);

        if (!$data['reminder']) {
            header('Location: /reminders');
            die;
        }

        if ($_POST) {
            $subject = $_POST['subject'] ?? '';
            $content = $_POST['content'] ?? '';
            $completed = isset($_POST['completed']) ? 1 : 0;

            if (!empty($subject)) {
                if ($reminder->update($id, $user_id, $subject, $content, $completed)) {
                    header('Location: /reminders');
                    die;
                } else {
                    $data['error'] = 'Failed to update reminder';
                }
            } else {
                $data['error'] = 'Subject is required';
            }
        }

        $this->view('reminders/edit', $data);
    }

    public function delete($id = null) {
        $user_id = $this->getUserId();
        if (!$user_id || !$id) {
            header('Location: /reminders');
            die;
        }

        $reminder = $this->model('Reminder');
        $reminder->delete($id, $user_id);
        header('Location: /reminders');
        die;
    }

    public function toggle($id = null) {
        $user_id = $this->getUserId();
        if (!$user_id || !$id) {
            header('Location: /reminders');
            die;
        }

        $reminder = $this->model('Reminder');
        $reminder->toggleComplete($id, $user_id);
        header('Location: /reminders');
        die;
    }

    private function getUserId() {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['username'])) {
            return false;
        }

        $user = $this->model('User');
        $userData = $user->getUserByUsername($_SESSION['username']);
        return $userData['id'] ?? false;
    }
}