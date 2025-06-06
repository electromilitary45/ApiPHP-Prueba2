<?php
require_once __DIR__ . '/../config/database.php';
class userController{
    private $conn;

    /* Constructor to initialize the database connection
     * This will be called when an instance of userController is created.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        header('Content-Type: application/json');
    }

    //----------- METODOS PARA USUARIO -----------
    public function crearUsuario() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['nombre']) || !isset($data['profesion']) || !isset($data['telefono'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Faltan datos necesarios']);
            return;
        }
        $nombre = $data['nombre'];
        $profesion = $data['profesion'];
        $telefono = $data['telefono'];
        $edad = isset($data['edad']) ? $data['edad'] : null;
        $peso = isset($data['peso']) ? $data['peso'] : null;
        $estatura = isset($data['estatura']) ? $data['estatura'] : null;
        
        $sql= "INSERT INTO users (nombre, profesion, telefono, edad, peso, estatura)
                VALUES (:nombre, :profesion, :telefono, :edad, :peso, :estatura)";

        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':profesion', $profesion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':estatura', $estatura);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Usuario creado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al crear el usuario']);
        }
    }//fin crearUsuario

    public function buscarUsuario() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'ID de usuario no proporcionado']);
            return;
        }

        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE userId = :id");
            $stmt->execute([':id' => $id]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                http_response_code(200);
                echo json_encode($usuario);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Usuario no encontrado']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al buscar usuario', 'detalle' => $e->getMessage()]);
        }
    }


    public function listarUsuarios(){
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($users) {
            http_response_code(200);
            echo json_encode($users);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'No hay usuarios registrados']);
        }
    }

    public function actualizarUsuario(){
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['userId']) || !isset($data['nombre']) || !isset($data['profesion']) || !isset($data['telefono'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Faltan datos necesarios']);
            return;
        }
        
        $userId = $data['userId'];
        $nombre = $data['nombre'];
        $profesion = $data['profesion'];
        $telefono = $data['telefono'];
        $edad = isset($data['edad']) ? $data['edad'] : null;
        $peso = isset($data['peso']) ? $data['peso'] : null;
        $estatura = isset($data['estatura']) ? $data['estatura'] : null;

        $sql= "UPDATE users SET nombre = :nombre, profesion = :profesion, telefono = :telefono, 
                edad = :edad, peso = :peso, estatura = :estatura WHERE userId = :userId";

        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':profesion', $profesion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':estatura', $estatura);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Usuario actualizado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al actualizar el usuario']);
        }
    }

    public function eliminarUsuario(){
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'ID de usuario no proporcionado']);
            return;
        }

        $stmt = $this->conn->prepare("DELETE FROM users WHERE userId = :id");
        if ($stmt->execute([':id' => $id])) {
            http_response_code(200);
            echo json_encode(['message' => 'Usuario eliminado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al eliminar el usuario']);
        }
    }

}//fin userController
?>