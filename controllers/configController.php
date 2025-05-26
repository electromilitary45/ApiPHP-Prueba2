<?php
require_once __DIR__ . '/../config/database.php';
class configController{
    private $conn;

    /* Constructor to initialize the database connection
     * This will be called when an instance of userController is created.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        header('Content-Type: application/json');
    }

    //----------- METODOS PARA config -----------
    public function probarConexion() {
        try {
            $sql = "SELECT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            http_response_code(200);
            echo json_encode(['message' => 'Conexión exitosa a la base de datos']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error al conectar a la base de datos: ' . $e->getMessage()]);
        }
    }
}
?>