<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'avii';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function connect(){
        $this->conn = null;
        try{
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }//fin connect
    
}//fin class Database
?>