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

    public function getUsers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql); // needed to use the "prepare" function with OOP
    }

    public function registerUser($name, $email, $licensenumber, $phonenumber, $password, $address)
    {
        $hashedPassword = md5($password); // hashes the password

        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, licensenumber, phonenumber, password, address) VALUES (:name, :email, :licensenumber, :phonenumber, :password, :address)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':licensenumber', $licensenumber);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phonenumber', $phonenumber);
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
    public function addAdmin($name, $email, $password, $address)
    {
        try {
            $hashedPassword = md5($password);

            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, licensenumber, phonenumber, address, is_admin) VALUES (?, ?, ?, NULL, NULL, ?, 1)");
            return $stmt->execute([$name, $email, $hashedPassword, $address]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
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
            $hashedPassword = md5($password);

            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, licensenumber, phonenumber, address, is_admin) VALUES (?, ?, ?, NULL, NULL, ?, 2)");
            return $stmt->execute([$name, $email, $hashedPassword, $address]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    
    public function updateUser($user_id, $name, $licensenumber, $email, $address)
    {
        try {
            $stmt = $this->prepare("UPDATE users SET name = ?, licensenumber = ?, email = ?, address = ? WHERE user_id = ?");
            $stmt->bindParam(1, $name, PDO::PARAM_STR);
            $stmt->bindParam(2, $licensenumber, PDO::PARAM_INT);
            $stmt->bindParam(3, $email, PDO::PARAM_STR);
            $stmt->bindParam(4, $address, PDO::PARAM_STR);
            $stmt->bindParam(5, $user_id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getCars()
    {
        try {
            $stmt = $this->prepare("SELECT * FROM cars");
            $stmt->execute();

            $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $cars;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getCarById($car_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE car_id = ?");
        $stmt->execute([$car_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCar($car_id, $brand, $model, $year, $license_plate, $availability, $daily_price, $picture)
    {
        $stmt = $this->pdo->prepare("UPDATE cars SET car_brand = ?, car_model = ?, car_year = ?, car_licenseplate = ?, car_availability = ?, car_dailyprice = ?, car_picture = ? WHERE car_id = ?");
        return $stmt->execute([$brand, $model, $year, $license_plate, $availability, $daily_price, $picture, $car_id]);
    }
}
?>