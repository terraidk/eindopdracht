<?php class Database
{
    private $host = 'localhost';
    private $db_name = 'eindopdracht';
    private $username = 'root';
    private $password = 'emir2006';
    public $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function editUser($id, $email, $password, $address)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("UPDATE users SET email = :email, password = :password, address = :address WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $id);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :user_id"); // selects the user where the user_id matches with the user_id asked by a website
        $stmt->bindParam(':user_id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql); // needed to use the "prepare" function with OOP
    }

    public function registerUser($name, $email, $password, $address)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // hashes the password

        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, address) VALUES (:name, :email, :password, :address)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function escape($var)
    {
        return htmlspecialchars($var);
    }
    public function addAdmin($name, $email, $password)
    {
        try {
            $stmt = $this->prepare("INSERT INTO users (name, email, password, address, is_admin) VALUES (?, ?, ?, '', 1)");
            $stmt->execute([$name, $email, $password]);
            return true;
        } catch (PDOException $e) {
            ($e->getMessage());
            return false;
        }
    }

    public function getAllRents()
    {
        try {
            $stmt = $this->prepare("SELECT * FROM renting");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function addWorker($name, $email, $password, $address)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, address, is_admin) VALUES (?, ?, ?, ?, 2)");
            return $stmt->execute([$name, $email, $password, $address]);
        } catch (PDOException $e) {
            // Handle the exception as needed, you might want to log it or display an error message
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


}
?>