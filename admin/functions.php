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


class Login {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Fungsi untuk login
    public function login($username, $password) {
        // Menyiapkan query untuk mencari username
        $stmt = $this->conn->prepare("SELECT password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            // Verifikasi password
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['admin_logged_in'] = true;
                return true;
            }
        }

        return false; 
    }
}

class User {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function register($name, $username, $email, $no_hp, $password) {
        $query = "INSERT INTO users (name, username, email, no_hp, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $name, $username, $email, $no_hp, $passwordHash);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function getAllUsers() {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($userId) {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

class Doctor {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAllDoctors() {
        $query = "SELECT * FROM doctors WHERE available = 1";
        $result = $this->conn->query($query);

        $doctors = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $doctors[] = $row;
            }
        }
        return $doctors;
    }

    public function getDoctorById($doctorId) {
        $query = "SELECT * FROM doctors WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addDoctor($doctorName, $poliId) {
        $query = "INSERT INTO doctors (doctor_name, poli_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $doctorName, $poliId);
        return $stmt->execute();
    }

    public function updateDoctor($doctorId, $newDoctorName) {
        $query = "UPDATE doctors SET doctor_name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $newDoctorName, $doctorId);
        return $stmt->execute();
    }

    public function deleteDoctor($doctorId) {
        $query = "DELETE FROM doctors WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $doctorId);
        return $stmt->execute();
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

    public function addPoli($poliName) {
        $query = "INSERT INTO poli (poli_name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $poliName);
        return $stmt->execute();
    }
}


class Queue {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getQueues() {
        $query = "SELECT * FROM queues";
        $result = $this->conn->query($query);
        return $result;
    }

    public function resetQueues() {
        $query = "DELETE FROM queues";  // Atau jika ada mekanisme reset lain
        return $this->conn->query($query);
    }
}

class Session {
    public function __construct() {
        // Mulai sesi jika belum dimulai
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Fungsi untuk logout
    public function logout() {
        // Menghancurkan sesi
        session_unset();  // Hapus semua variabel sesi
        session_destroy();  // Hancurkan sesi
    }
}



?>