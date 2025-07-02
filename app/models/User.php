<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {

    }

    // Add return type declaration
    public function test(): ?array {
        $db = db_connect();
        if ($db === null) {
            error_log("Database connection failed in User::test()");
            return null;
        }
        $statement = $db->prepare("SELECT * FROM users;");
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
        return $rows ?: null; // Return null if no rows are found
    }
    /**
     * Validate password meets security requirements
     */
    public function validatePassword($password) {
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            return "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            return "Password must contain at least one number.";
        }
        return true;
    }

    /**
     * Register a new user
     * @return bool|string Returns true on success, error message on failure
     */
    public function register($username, $password) {
        $username = strtolower(trim($username));
        
        // Validate password security
        $passwordValidation = $this->validatePassword($password);
        if ($passwordValidation !== true) {
            return $passwordValidation;
        }
        
        $db = db_connect();
        if ($db === null) {
            error_log("Database connection failed in User::register()");
            return "Database connection failed. Please try again.";
        }

        // Check if username already exists
        $statement = $db->prepare("SELECT id FROM users WHERE username = :username");
        $statement->bindValue(':username', $username);
        $statement->execute();
        
        if ($statement->fetch()) {
            return "Username already exists. Please choose a different username.";
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        try {
            $statement = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $statement->bindValue(':username', $username);
            $statement->bindValue(':password', $hashed_password);
            $statement->execute();
            return true;
        } catch (PDOException $e) {
            error_log("User registration failed: " . $e->getMessage());
            return "Registration failed. Please try again.";
        }
    }

    /**
     * Check if user is locked out due to failed attempts
     */
    private function isUserLockedOut($username) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }

        try {
            // Count recent failed attempts within the last 60 seconds
            if (DB_TYPE === 'pgsql') {
                $statement = $db->prepare("SELECT COUNT(*) as count, MAX(attempt_time) as last_attempt FROM login_logs WHERE username = :username AND attempt = 'bad' AND attempt_time > NOW() - INTERVAL '60 seconds'");
            } else {
                $statement = $db->prepare("SELECT COUNT(*) as count, MAX(attempt_time) as last_attempt FROM login_logs WHERE username = :username AND attempt = 'bad' AND attempt_time > DATE_SUB(NOW(), INTERVAL 60 SECOND)");
            }
            $statement->bindValue(':username', $username);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            $failed_count = (int)$result['count'];
            
            // If we have 3 or more failed attempts in the last 60 seconds
            if ($failed_count >= 3) {
                $last_failed_timestamp = strtotime($result['last_attempt']);
                $lockout_end = $last_failed_timestamp + 60; // 60 seconds from last failed attempt
                $seconds_remaining = $lockout_end - time();
                
                if ($seconds_remaining > 0) {
                    return min($seconds_remaining, 60); // Return seconds remaining (max 60)
                } else {
                    // Lockout period has expired, clear the failed attempts
                    $this->resetUserLockout($username);
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error checking lockout status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Clean up old login attempts (older than 60 seconds)
     */
    private function cleanupOldAttempts($username) {
        $db = db_connect();
        if ($db === null) {
            return;
        }

        try {
            if (DB_TYPE === 'pgsql') {
                $statement = $db->prepare("DELETE FROM login_logs WHERE username = :username AND attempt_time < NOW() - INTERVAL '60 seconds'");
            } else {
                $statement = $db->prepare("DELETE FROM login_logs WHERE username = :username AND attempt_time < DATE_SUB(NOW(), INTERVAL 60 SECOND)");
            }
            $statement->bindValue(':username', $username);
            $statement->execute();
        } catch (PDOException $e) {
            error_log("Failed to cleanup old attempts: " . $e->getMessage());
        }
    }

    /**
     * Reset lockout for a user by clearing all their failed attempts
     */
    public function resetUserLockout($username) {
        $db = db_connect();
        if ($db === null) {
            return false;
        }

        try {
            $statement = $db->prepare("DELETE FROM login_logs WHERE username = :username AND attempt = 'bad'");
            $statement->bindValue(':username', $username);
            $statement->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Failed to reset user lockout: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Log login attempt
     */
    private function logLoginAttempt($username, $attempt) {
        $db = db_connect();
        if ($db === null) {
            return;
        }

        try {
            $statement = $db->prepare("INSERT INTO login_logs (username, attempt, attempt_time) VALUES (:username, :attempt, NOW())");
            $statement->bindValue(':username', $username);
            $statement->bindValue(':attempt', $attempt);
            $statement->execute();
        } catch (PDOException $e) {
            error_log("Failed to log login attempt: " . $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function authenticate($username, $password) {
        $username = strtolower($username);
        
        // Check if user is locked out
        $lockout_time = $this->isUserLockedOut($username);
        if ($lockout_time !== false) {
            $_SESSION['lockout_message'] = "Account locked due to multiple failed attempts. Try again in {$lockout_time} seconds.";
            header('Location: /login');
            die;
        }

        $db = db_connect();
        if ($db === null) {
            error_log("Database connection failed in User::authenticate()");
            header('Location: /login');
            die;
        }
        
        $statement = $db->prepare("SELECT * FROM users WHERE username = :name");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        if ($rows && password_verify($password, $rows['password'])) {
            // Successful login
            $this->logLoginAttempt($username, 'good');
            $_SESSION['auth'] = 1;
            $_SESSION['username'] = ucwords($username);
            $_SESSION['user_id'] = $rows['id'];
            unset($_SESSION['failedAuth']);
            unset($_SESSION['lockout_message']);
            header('Location: /home');
            die;
        } else {
            // Failed login
            $this->logLoginAttempt($username, 'bad');
            
            // Count total failed attempts from database for this username (not time-limited for display)
            $db = db_connect();
            if ($db !== null) {
                try {
                    $statement = $db->prepare("SELECT COUNT(*) as count FROM login_logs WHERE username = :username AND attempt = 'bad'");
                    $statement->bindValue(':username', $username);
                    $statement->execute();
                    $result = $statement->fetch(PDO::FETCH_ASSOC);
                    $_SESSION['failedAuth'] = $result['count'];
                } catch (PDOException $e) {
                    $_SESSION['failedAuth'] = 1;
                }
            } else {
                $_SESSION['failedAuth'] = 1;
            }
            
            // Track the time of this failed attempt
            $_SESSION['last_failed_time'] = time();
            
            // Check if this was the 3rd failed attempt and lock immediately
            $lockout_time = $this->isUserLockedOut($username);
            if ($lockout_time !== false) {
                $_SESSION['lockout_message'] = "Account locked due to multiple failed attempts. Try again in {$lockout_time} seconds.";
            }
            
            header('Location: /login');
            die;
        }
    }
}
