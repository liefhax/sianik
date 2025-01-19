<?php 

class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'sianik';
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}

class User {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function register($name, $username, $email, $no_hp, $password) {
        // Hashing password untuk keamanan
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk memasukkan data pengguna ke database
        $query = "INSERT INTO users (name, username, email, no_hp, password) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $this->conn->prepare($query)) {
            // Bind parameter dan eksekusi query
            $stmt->bind_param("sssss", $name, $username, $email, $no_hp, $passwordHash);
            $stmt->execute();

            // Mengecek apakah query berhasil dijalankan
            if ($stmt->affected_rows > 0) {
                return true;  // Pengguna berhasil terdaftar
            } else {
                return false;  // Gagal memasukkan data pengguna
            }
        } else {
            return false;  // Gagal mempersiapkan statement
        }
    }

        public function isUsernameExists($username) {
        $query = "SELECT username FROM users WHERE username = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;  // Mengembalikan true jika username ada
        }
        return false;
    }

    public function login($username, $password) {
        $query = "SELECT password FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                session_start();
                $_SESSION['username'] = $username;
                return true;
            }
        }
        return false;
    }

    public function getUserInfo($username) {
        $query = "SELECT name, email, no_hp FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateUser($username, $data) {
        $sql = "UPDATE users SET name = ?, email = ?, no_hp = ?";
        $params = [$data['name'], $data['email'], $data['no_hp']];

        if (!empty($data['new_password'])) {
            $sql .= ", password = ?";
            $params[] = password_hash($data['new_password'], PASSWORD_BCRYPT);
        }

        $sql .= " WHERE username = ?";
        $params[] = $username;

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function isLoggedIn() {
        session_start();
        return isset($_SESSION['username']);
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}

class Queue {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function takeQueue($username, $doctorId) {
        $query = "SELECT * FROM queues WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "You already have an active queue.";
        }

        $query = "SELECT MAX(queue_number) AS max_queue FROM queues WHERE doctor_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $nextQueue = $row['max_queue'] + 1;

        $query = "INSERT INTO queues (username, doctor_id, queue_number) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sii", $username, $doctorId, $nextQueue);
        $stmt->execute();

        return "Success! Your queue number is: $nextQueue.";
    }

    public function getUserQueue($username) {
        $query = "SELECT q.queue_number, d.doctor_name FROM queues q JOIN doctors d ON q.doctor_id = d.id WHERE q.username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getQueueHistory($username) {
        $query = "SELECT q.queue_number, q.queue_date, d.doctor_name, p.poli_name 
                  FROM queues q 
                  JOIN doctors d ON q.doctor_id = d.id 
                  JOIN poli p ON d.poli_id = p.id 
                  WHERE q.username = ? 
                  ORDER BY q.queue_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteQueue($username) {
        $query = "DELETE FROM queues WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        return $stmt->execute();
    }
}

class Doctor {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAvailableDoctors() {
        $query = "SELECT * FROM doctors WHERE available = 1";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDoctorById($doctorId) {
        $query = "SELECT * FROM doctors WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Tambahkan metode ini
    public function getDoctorsByPoli($poliId) {
        $query = "SELECT * FROM doctors WHERE poli_id = ? AND available = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $poliId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}


class Poli {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAllPoli() {
        $query = "SELECT * FROM poli";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
}

class Session {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
    }
}

?>
