<?php
class Conf {
    private $server;
    private $user;
    private $password;
    private $db;
    private $conn;


    public function __construct() {
        $this->server = 'localhost';
        $this->user = 'root';
        $this->password = '';
        $this->db = 'stationeryDistributor';
    }


    protected function connect(){
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=".$this->server."; dbname=".$this->db, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo  "Error en la conexion: " . $e->getMessage();
        }

        return $this->conn;
    }

    protected function disconnect(){
        $this->conn = null;
    }

    public function exec_query($query, $params=[]){
        $this->connect();

        try {
            $stmt = $this->conn->prepare($query);

            $stmt->execute($params);

            $this->disconnect();

            return $stmt;
        }catch (PDOException $e) {
            echo "Error en la ejecucion de la consulta: ". $e->getMessage();
            return false;
        }
    }
}
?>