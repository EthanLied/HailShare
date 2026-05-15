<?php
// ============ DATABASE CONNECTION & CRUD FUNCTIONS ============

class DatabaseConnection {
    private $connection;
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "myDB";

    public function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        
        $this->connection->set_charset("utf8");
    }

    // ============ READ OPERATIONS ============
    
    /**
     * Get all users (accounts)
     */
    public function getAllAccounts($search = '', $filter = '') {
        $query = "SELECT user_id as id, CONCAT(first_name, ' ', last_name) as name, 
                  CASE 
                    WHEN role_id = 1 THEN 'Admin'
                    WHEN role_id = 2 THEN 'Staff'
                    WHEN role_id = 3 THEN 'Customer'
                    ELSE 'Unknown'
                  END as type,
                  email, account_status as status FROM users WHERE 1=1";
        
        if (!empty($filter) && $filter !== 'all') {
            $filter_role = match($filter) {
                'Admin' => 1,
                'Staff' => 2,
                'Customer' => 3,
                default => -1
            };
            if ($filter_role !== -1) {
                $query .= " AND role_id = " . $filter_role;
            }
        }
        
        if (!empty($search)) {
            $search = $this->connection->real_escape_string($search);
            $query .= " AND (CONCAT(first_name, ' ', last_name) LIKE '%$search%' OR email LIKE '%$search%')";
        }
        
        $query .= " ORDER BY user_id DESC";
        
        $result = $this->connection->query($query);
        
        if ($result) {
            $accounts = [];
            while ($row = $result->fetch_assoc()) {
                $accounts[] = $row;
            }
            return $accounts;
        }
        
        return [];
    }

    /**
     * Get single account by ID
     */
    public function getAccountById($id) {
        $id = intval($id);
        $query = "SELECT * FROM users WHERE user_id = $id";
        $result = $this->connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    /**
     * Get account by email
     */
    public function getAccountByEmail($email) {
        $email = $this->connection->real_escape_string($email);
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    // ============ UPDATE OPERATIONS ============
    
    /**
     * Update account information
     */
    public function updateAccount($id, $data) {
        $id = intval($id);
        
        $updates = [];
        
        if (isset($data['first_name'])) {
            $updates[] = "first_name = '" . $this->connection->real_escape_string($data['first_name']) . "'";
        }
        
        if (isset($data['last_name'])) {
            $updates[] = "last_name = '" . $this->connection->real_escape_string($data['last_name']) . "'";
        }
        
        if (isset($data['email'])) {
            $updates[] = "email = '" . $this->connection->real_escape_string($data['email']) . "'";
        }
        
        if (isset($data['phone_number'])) {
            $updates[] = "phone_number = '" . $this->connection->real_escape_string($data['phone_number']) . "'";
        }
        
        if (isset($data['date_of_birth'])) {
            $updates[] = "date_of_birth = '" . $this->connection->real_escape_string($data['date_of_birth']) . "'";
        }
        
        if (isset($data['account_status'])) {
            $updates[] = "account_status = '" . $this->connection->real_escape_string($data['account_status']) . "'";
        }
        
        if (isset($data['role_id'])) {
            $updates[] = "role_id = " . intval($data['role_id']);
        }
        
        if (isset($data['password_hash'])) {
            $updates[] = "password_hash = '" . $this->connection->real_escape_string($data['password_hash']) . "'";
        }
        
        if (isset($data['security_question'])) {
            $updates[] = "security_question = '" . $this->connection->real_escape_string($data['security_question']) . "'";
        }
        
        if (isset($data['security_question_answer'])) {
            $updates[] = "security_question_answer = '" . $this->connection->real_escape_string($data['security_question_answer']) . "'";
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $updates[] = "updated_at = NOW()";
        
        $query = "UPDATE users SET " . implode(", ", $updates) . " WHERE user_id = $id";
        
        return $this->connection->query($query);
    }

    // ============ DELETE OPERATIONS ============
    
    /**
     * Delete account
     */
    public function deleteAccount($id) {
        $id = intval($id);
        
        // First, delete related records
        $this->connection->query("DELETE FROM ride_chat_messages WHERE ride_chat_id IN (SELECT ride_chat_id FROM ride_chat_rooms WHERE ride_id IN (SELECT ride_id FROM rides WHERE user_id = $id))");
        $this->connection->query("DELETE FROM ride_chat_rooms WHERE ride_id IN (SELECT ride_id FROM rides WHERE user_id = $id)");
        $this->connection->query("DELETE FROM ratings WHERE rater_user_id = $id OR rated_user_id = $id");
        $this->connection->query("DELETE FROM ride_participants WHERE user_id = $id");
        $this->connection->query("DELETE FROM rides WHERE user_id = $id");
        $this->connection->query("DELETE FROM support_chat_messages WHERE support_chat_id IN (SELECT support_chat_id FROM support_chat_rooms WHERE customer_user_id = $id OR staff_user_id = $id)");
        $this->connection->query("DELETE FROM support_chat_rooms WHERE customer_user_id = $id OR staff_user_id = $id");
        $this->connection->query("DELETE FROM notifications WHERE user_id = $id");
        
        // Finally, delete the user
        $query = "DELETE FROM users WHERE user_id = $id";
        return $this->connection->query($query);
    }

    // ============ UTILITY FUNCTIONS ============
    
    /**
     * Get role name from ID
     */
    public function getRoleName($role_id) {
        return match($role_id) {
            1 => 'Admin',
            2 => 'Staff',
            3 => 'Customer',
            default => 'Unknown'
        };
    }

    /**
     * Get role ID from name
     */
    public function getRoleId($role_name) {
        return match($role_name) {
            'Admin' => 1,
            'Staff' => 2,
            'Customer' => 3,
            default => 3
        };
    }

    /**
     * Close database connection
     */
    public function close() {
        $this->connection->close();
    }
}

?>
