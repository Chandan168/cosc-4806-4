
<?php

class Reminders extends Controller {

    public function index() {
        // Check if user is authenticated
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
            header('Location: /login');
            die;
        }
        
        $reminderModel = $this->model('reminders');
        $reminders = $reminderModel->getAllByUser($_SESSION['user_id']);
        
        $this->view('reminders/index', ['reminders' => $reminders]);
    }

    public function create() {
        // Check if user is authenticated
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
            header('Location: /login');
            die;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = trim($_POST['subject'] ?? '');
            $content = trim($_POST['content'] ?? '');
            
            if (empty($subject)) {
                $error = 'Subject is required';
                $this->view('reminders/create', ['error' => $error]);
                return;
            }
            
            $reminderModel = $this->model('reminders');
            if ($reminderModel->create($_SESSION['user_id'], $subject, $content)) {
                header('Location: /reminders');
                die;
            } else {
                $error = 'Failed to create reminder';
                $this->view('reminders/create', ['error' => $error]);
            }
        } else {
            $this->view('reminders/create');
        }
    }

    public function edit($id = null) {
        // Check if user is authenticated
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
            header('Location: /login');
            die;
        }

        if (!$id) {
            header('Location: /reminders');
            die;
        }

        $reminderModel = $this->model('reminders');
        $reminder = $reminderModel->getByIdAndUser($id, $_SESSION['user_id']);
        
        if (!$reminder) {
            header('Location: /reminders');
            die;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = trim($_POST['subject'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $completed = isset($_POST['completed']) ? 1 : 0;
            
            if (empty($subject)) {
                $error = 'Subject is required';
                $this->view('reminders/edit', ['reminder' => $reminder, 'error' => $error]);
                return;
            }
            
            if ($reminderModel->update($id, $subject, $content, $completed)) {
                header('Location: /reminders');
                die;
            } else {
                $error = 'Failed to update reminder';
                $this->view('reminders/edit', ['reminder' => $reminder, 'error' => $error]);
            }
        } else {
            $this->view('reminders/edit', ['reminder' => $reminder]);
        }
    }

    public function delete($id = null) {
        // Check if user is authenticated
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
            header('Location: /login');
            die;
        }

        if (!$id) {
            header('Location: /reminders');
            die;
        }

        $reminderModel = $this->model('reminders');
        $reminder = $reminderModel->getByIdAndUser($id, $_SESSION['user_id']);
        
        if ($reminder) {
            $reminderModel->delete($id);
        }
        
        header('Location: /reminders');
        die;
    }

    public function toggle($id = null) {
        // Check if user is authenticated
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
            header('Location: /login');
            die;
        }

        if (!$id) {
            header('Location: /reminders');
            die;
        }

        $reminderModel = $this->model('reminders');
        $reminder = $reminderModel->getByIdAndUser($id, $_SESSION['user_id']);
        
        if ($reminder) {
            $newStatus = $reminder['completed'] ? 0 : 1;
            $reminderModel->updateStatus($id, $newStatus);
        }
        
        header('Location: /reminders');
        die;
    }
}
