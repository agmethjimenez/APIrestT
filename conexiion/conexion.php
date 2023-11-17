<?php 
Class Database{
    private $host = "localhost";
    private $user = "root";
    private $password = "1234";
    private $db = "ventas";
    private $port = "3307";
    private $conexion;

    public function Conectar(){
        $this->conexion = new mysqli($this->host,$this->user,$this->password,$this->db,$this->port);

        if($this->conexion->connect_error){
            die("Conexion fallida". $this->conexion->connect_error);
        }
        return $this->conexion;
    }
}
$conn = new Database();
$conn->Conectar();