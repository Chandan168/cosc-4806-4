<?php
class Reminders extends Controller {
    public function index() {
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
            header('Location: /login');
            die;
        }
        $reminder = $this->model('Reminder');
        $userId = $_SESSION['user_id'];
        $reminders = $reminder->getAllByUser($userId);

        $data = ['reminders' => $reminders];
        $this->view('reminders/index', $data);
    }
    public function create() {
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
            header('Location: /login');
            die;
        }
        $this->view('reminders/create');
    }
    public function store() {
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
            header('Location: /login');
            die;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = $_POST['subject'] ?? '';
            $content = $_POST['content'] ?? '';
            if (!empty($subject)) {
                $reminder = $this->model('Reminder');
                $userId = $_SESSION['user_id'];

                if ($reminder->create($userId, $subject, $content)) {
                    header('Location: /reminders');
                    die;
                }
            }
        }
        header('Location: /reminders/create');
        die;
    }
    public function edit($id = null) {
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
            header('Location: /login');
            die;
        }
        // more code here for retrieving the reminder
    }
    // other methods: update, delete, toggle, etc.
}