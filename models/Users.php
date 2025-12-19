<?php

require_once "./config/config.php";

abstract class Users
{ // we make an abstract class user wich contains the common variables and methods for the teacher and student

    private $username; // make all the variables private to protect them from outside access 
    private $email;
    private $password;
    private $member;
    private $spcode;

    protected function __construct($username, $email, $password, $member, $spcode)
    { // we set the constravtor protected for be accessible only for the child classes 
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->member = $member;
        $this->spcode = $spcode;
    }

    public function getUsername()
    { // we make setter and getters for all variables 
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getMember()
    {
        return $this->member;
    }

    public function setMember($member)
    {
        $this->member = $member;
    }

    public function getSpcode()
    {
        return $this->spcode;
    }

    public function setSpcode($spcode)
    {
        $this->spcode = $spcode;
    }

    public function signup()// we make abstract methods so, the child of this class will be obliged to implement them  
    {
        // we took the values from the form and trim them to remove spaces
        $this->username = trim($this->username); //save the value from the field 'username' at var $username
        $this->email = trim($this->email);// the same for mail 
        $this->password = trim($this->password); // the same for password
        $this->member = trim($this->member); //....
        $this->spcode = trim($this->spcode); //....

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {//validation for right email
            return ['success' => false, "error" => "mail"]; // if we found error we retturn an array with success false and the type of error to controler
        }

        if (strlen($this->password) < 6) {//validation for password
            return ['success' => false, "error" => "passL"];
        }

        $codes = [ //array which contain the codes for each role (student/professor)
            "0" => "PROF2025",
            "1" => "STUD2025"
        ];

        if (!isset($codes[$this->member]) || $codes[$this->member] !== $this->spcode) { //check if the role of user matches with the code of reception 
            return ['success' => false, "error" => "memCode"];
        }


        $pdo = $GLOBALS['pdo']; // we take the pdo variable from config file to have access to the database
        $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");// we make a query to base to chech if we already have the same value on username or mail 
        $statement->execute([':username' => $this->username, ':email' => $this->email]);
        $userExists = $statement->fetch();

        if ($userExists) {
            if ($userExists['username'] === $this->username) {
                return ['success' => false, "error" => "userEx"];
            }
            if ($userExists['email'] === $this->email) {
                return ['success' => false, "error" => "mailEx"];
            }
        } else {
            $hashPassword = password_hash($this->password, PASSWORD_DEFAULT); // _hash is a method which make hashing the first argument with the way of second argument

            $insertStatment = $pdo->prepare("INSERT INTO users (username, email, password, role_id, spCode) VALUES (:username, :email, :password, :role_id, :spCode)");
            $insertStatment->execute([
                ':username' => $this->username,
                ':email' => $this->email,
                ':password' => $hashPassword,
                ':role_id' => (int) $this->member,
                ':spCode' => $this->spcode
            ]);
            $userId = $pdo->lastInsertId();//we took the id and put it in a variable 

            return [
                'success' => true,
                'user_id' => $userId,
                'username' => $this->username,
                'role_id' => $this->member
            ];
        }
    }

    public static function login($email, $password)
    {
        $pdo = $GLOBALS['pdo'];
        // echo "hereeeeeeee";
        $email = trim($email);
        $password = trim($password);

        if (empty($email) || empty($password)) {
            return ['success' => false, 'error' => 'empty'];
        }

        $selectStatement = $pdo->prepare("SELECT * FROM users WHERE email = :email");// we make a query to base to find if the user with email exists
        $selectStatement->execute([':email' => $email]);// we execute the query
        $userExists = $selectStatement->fetch(); // we fetch the result

        if (empty($userExists) || !$userExists) {
            return ['success' => false, 'error' => 'user'];
        } else {
            $checkPass = password_verify($password, $userExists['password']);
            if (!$checkPass) {
                return ['success' => false, 'error' => 'pass'];
            }
            return [
                'success' => true,
                'user_id' => $userExists['user_id'],
                'username' => $userExists['username'],
                'role_id' => $userExists['role_id']
            ];
        }
    }


    public static function findById($userId)//find a user by id 
    {
        $pdo = $GLOBALS['pdo'];
        $select = $pdo->prepare("SELECT * FROM users WHERE user_id = :id");
        $select->execute([':id' => $userId]);
        return $select->fetch();
    }
}


?>