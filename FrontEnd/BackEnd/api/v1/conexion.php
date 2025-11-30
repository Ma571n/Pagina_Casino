<?php
class Conexion {
    private $connection;
    private $host;
    private $username;
    private $password;
    private $db;
    private $port;

    public function __construct() {
        $this->host = '127.0.0.1';
        $this->port = 3306;
        $this->db = 'coningen_udp252_losk';
        $this->username = 'coningen_udp252_losk';
        $this->password = 'ubf59fVSk9Kdbpu3aKvz';
        $this->connection = null;
    }

    public function getConnection() {
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->db, $this->port);
        mysqli_set_charset($this->connection, 'utf8');
        if (!$this->connection) {
            die("Error en la conexión: " . mysqli_connect_error());
        }
        return $this->connection;
    }

    public function closeConnection() {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }
}
