<?php
// Shared database connection and Admin data helpers for HailShare.

class DatabaseConnection {
    private $connection;
    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "";
    private $database = "myDB";
    private $port = 3306;

    private $allowedTables = [
        "users",
        "rides",
        "ride_participants",
        "ratings",
        "ride_chat_rooms",
        "ride_chat_messages",
        "support_chat_rooms",
        "support_chat_messages",
        "notifications"
    ];

    public function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database, $this->port);

        if ($this->connection->connect_error) {
            throw new Exception("Connection failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset("utf8mb4");
    }

    public function getConnection() {
        return $this->connection;
    }

    public function getAllRows($table) {
        if (!in_array($table, $this->allowedTables, true)) {
            throw new Exception("Invalid table name");
        }

        $result = $this->connection->query("SELECT * FROM `$table`");
        return $this->fetchRows($result);
    }

    public function runQuery($query) {
        return $this->connection->query($query);
    }

    public function getAllAccounts($search = '', $filter = '') {
        $query = "SELECT user_id AS id,
                         CONCAT(first_name, ' ', last_name) AS name,
                         CASE
                            WHEN role_id = 1 THEN 'Customer'
                            WHEN role_id = 2 THEN 'Staff'
                            WHEN role_id = 3 THEN 'Admin'
                            ELSE 'Unknown'
                         END AS type,
                         email,
                         account_status AS status
                  FROM users
                  WHERE 1 = 1";

        $params = [];
        $types = "";

        if (!empty($filter) && $filter !== "all") {
            $roleId = $this->getRoleId($filter);
            if ($roleId > 0) {
                $query .= " AND role_id = ?";
                $params[] = $roleId;
                $types .= "i";
            }
        }

        if (!empty($search)) {
            $query .= " AND (CONCAT(first_name, ' ', last_name) LIKE ? OR email LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "ss";
        }

        $query .= " ORDER BY user_id DESC";

        return $this->selectPrepared($query, $types, $params);
    }

    public function getAccountById($id) {
        $rows = $this->selectPrepared("SELECT * FROM users WHERE user_id = ? LIMIT 1", "i", [intval($id)]);
        return $rows[0] ?? null;
    }

    public function getAccountByEmail($email) {
        $rows = $this->selectPrepared("SELECT * FROM users WHERE email = ? LIMIT 1", "s", [$email]);
        return $rows[0] ?? null;
    }

    public function getFirstAdminAccount() {
        $rows = $this->selectPrepared("SELECT * FROM users WHERE role_id = 3 ORDER BY user_id ASC LIMIT 1", "", []);
        return $rows[0] ?? null;
    }

    public function updateAccount($id, $data) {
        $allowedColumns = [
            "first_name" => "s",
            "last_name" => "s",
            "email" => "s",
            "phone_number" => "s",
            "date_of_birth" => "s",
            "account_status" => "s",
            "role_id" => "i",
            "password_hash" => "s",
            "security_question" => "s",
            "security_question_answer" => "s"
        ];

        $sets = [];
        $params = [];
        $types = "";

        foreach ($allowedColumns as $column => $type) {
            if (array_key_exists($column, $data)) {
                $sets[] = "`$column` = ?";
                $params[] = $type === "i" ? intval($data[$column]) : $data[$column];
                $types .= $type;
            }
        }

        if (empty($sets)) {
            return false;
        }

        $sets[] = "updated_at = NOW()";
        $params[] = intval($id);
        $types .= "i";

        $query = "UPDATE users SET " . implode(", ", $sets) . " WHERE user_id = ?";
        $statement = $this->connection->prepare($query);

        if (!$statement) {
            return false;
        }

        $statement->bind_param($types, ...$params);
        $success = $statement->execute();
        $statement->close();

        return $success;
    }

    public function deleteAccount($id) {
        $id = intval($id);
        if ($id <= 0) {
            return false;
        }

        $this->connection->begin_transaction();

        try {
            $this->executePrepared(
                "DELETE FROM ride_chat_messages
                 WHERE ride_chat_id IN (
                    SELECT ride_chat_id FROM ride_chat_rooms WHERE guest_user_id = ?
                 )",
                "i",
                [$id]
            );
            $this->executePrepared(
                "DELETE FROM ride_chat_messages
                 WHERE ride_chat_id IN (
                    SELECT ride_chat_id FROM ride_chat_rooms
                    WHERE ride_id IN (SELECT ride_id FROM rides WHERE user_id = ?)
                 )",
                "i",
                [$id]
            );
            $this->executePrepared("DELETE FROM ride_chat_messages WHERE sender_user_id = ?", "i", [$id]);
            $this->executePrepared("DELETE FROM ride_chat_rooms WHERE guest_user_id = ?", "i", [$id]);
            $this->executePrepared(
                "DELETE FROM ride_chat_rooms
                 WHERE ride_id IN (SELECT ride_id FROM rides WHERE user_id = ?)",
                "i",
                [$id]
            );
            $this->executePrepared("DELETE FROM ratings WHERE ride_id IN (SELECT ride_id FROM rides WHERE user_id = ?)", "i", [$id]);
            $this->executePrepared("DELETE FROM ratings WHERE rater_user_id = ? OR rated_user_id = ?", "ii", [$id, $id]);
            $this->executePrepared("DELETE FROM ride_participants WHERE ride_id IN (SELECT ride_id FROM rides WHERE user_id = ?)", "i", [$id]);
            $this->executePrepared("DELETE FROM ride_participants WHERE user_id = ?", "i", [$id]);
            $this->executePrepared("DELETE FROM rides WHERE user_id = ?", "i", [$id]);
            $this->executePrepared(
                "DELETE FROM support_chat_messages
                 WHERE support_chat_id IN (
                    SELECT support_chat_id FROM support_chat_rooms
                    WHERE customer_user_id = ? OR staff_user_id = ?
                 )",
                "ii",
                [$id, $id]
            );
            $this->executePrepared("DELETE FROM support_chat_messages WHERE sender_user_id = ?", "i", [$id]);
            $this->executePrepared("DELETE FROM support_chat_rooms WHERE customer_user_id = ? OR staff_user_id = ?", "ii", [$id, $id]);
            $this->executePrepared("DELETE FROM notifications WHERE user_id = ?", "i", [$id]);
            $this->executePrepared("DELETE FROM users WHERE user_id = ?", "i", [$id]);

            $this->connection->commit();
            return true;
        } catch (Exception $exception) {
            $this->connection->rollback();
            return false;
        }
    }

    public function getDashboardStats() {
        return [
            "active_riders" => $this->countRows("users", "account_status = 'active'"),
            "rides_shared" => $this->countRows("rides"),
            "completed_rides" => $this->countRows("rides", "status = 'completed'"),
            "support_requests" => $this->countRows("support_chat_rooms")
        ];
    }

    public function getRoleName($roleId) {
        switch (intval($roleId)) {
            case 1:
                return "Customer";
            case 2:
                return "Staff";
            case 3:
                return "Admin";
            default:
                return "Unknown";
        }
    }

    public function getRoleId($roleName) {
        switch ($roleName) {
            case "Customer":
                return 1;
            case "Staff":
                return 2;
            case "Admin":
                return 3;
            default:
                return 0;
        }
    }

    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    private function selectPrepared($query, $types = "", $params = []) {
        $statement = $this->connection->prepare($query);

        if (!$statement) {
            return [];
        }

        if (!empty($params)) {
            $statement->bind_param($types, ...$params);
        }

        $statement->execute();
        $result = $statement->get_result();
        $rows = $this->fetchRows($result);
        $statement->close();

        return $rows;
    }

    private function executePrepared($query, $types, $params) {
        $statement = $this->connection->prepare($query);

        if (!$statement) {
            throw new Exception($this->connection->error);
        }

        $statement->bind_param($types, ...$params);
        $statement->execute();
        $statement->close();
    }

    private function countRows($table, $where = "") {
        if (!in_array($table, $this->allowedTables, true)) {
            return 0;
        }

        $query = "SELECT COUNT(*) AS total FROM `$table`";
        if ($where !== "") {
            $query .= " WHERE " . $where;
        }

        $result = $this->connection->query($query);
        $row = $result ? $result->fetch_assoc() : ["total" => 0];

        return intval($row["total"] ?? 0);
    }

    private function fetchRows($result) {
        if (!$result) {
            return [];
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->free();

        return $rows;
    }
}
?>
