
<?php

class Unlock extends Controller {

    public function index() {
        // Simple form to unlock a user
        echo '<h2>Reset User Lockout</h2>';
        echo '<form method="post" action="/unlock/reset" style="margin: 20px;">
                <label>Username to unlock:</label><br>
                <input type="text" name="username" required style="margin: 10px 0; padding: 5px;">
                <br><button type="submit" style="padding: 10px; background: #007bff; color: white; border: none;">Reset Lockout</button>
              </form>';
        echo '<p><a href="/login">Go to Login</a></p>';
    }
    
    public function reset() {
        if (isset($_POST['username'])) {
            $username = strtolower(trim($_POST['username']));
            $user = $this->model('User');
            
            if ($user->resetUserLockout($username)) {
                // Also clear session data
                unset($_SESSION['failedAuth']);
                unset($_SESSION['lockout_message']);
                unset($_SESSION['last_failed_time']);
                
                echo '<h2>Success!</h2>';
                echo '<p>Lockout reset successfully for user: <strong>' . htmlspecialchars($username) . '</strong></p>';
                echo '<p><a href="/login" style="background: #28a745; color: white; padding: 10px; text-decoration: none;">Go to Login</a></p>';
            } else {
                echo '<h2>Error</h2>';
                echo '<p>Failed to reset lockout for user: ' . htmlspecialchars($username) . '</p>';
                echo '<p><a href="/unlock">Try Again</a></p>';
            }
        } else {
            echo '<h2>Error</h2>';
            echo '<p>No username provided</p>';
            echo '<p><a href="/unlock">Go Back</a></p>';
        }
    }
}
