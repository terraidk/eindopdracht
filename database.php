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
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function registerUser($name, $email, $password, $address)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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
}
?>