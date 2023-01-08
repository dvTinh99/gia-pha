<?php 
class ConnectDB {
    private $servername = "mysql";
    private $username = "root";
    private $password = "root";
    private $dbname = "gia_pha";
    public static $conn;

    public function __construct() {
        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($this->conn->connect_error) {
            echo("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    public function query($query) {
        $this->conn->query($query);
    }

    public static function getConnection()
    {
        $temp = new ConnectDB();
        return $temp->getConn();
    }

    protected function getConn()
    {
        return $this->conn;
    }
}
?>