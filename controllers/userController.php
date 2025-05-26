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

    public function buscarUsuario(){
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'ID de usuario no proporcionado']);
            return;
        }

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE userId = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            http_response_code(200);
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Usuario no encontrado']);
        }
        
    }//fin buscarUsuario

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

}//fin userController
?>