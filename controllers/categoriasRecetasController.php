<?php 
    require_once 'config/database.php';

    class categoriasrecetasController {
        private $conn;

        //Constructor 

        public function __construct() {
            $database = new Database();
            $this->conn = $database->connect();
            header('Content-Type: application/json');
        }//fin constructor

        //----------- METODOS PARA CATEGORIAS RECETAS -----------

        //-> METODO: Crear nueva categoria de receta
        public function crearCategoriaReceta(){
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['nombre']) || !isset($data['descripcion'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Faltan datos necesarios']);
                return;
            }

            $sql= "INSERT INTO categorias_recetas (nombre, descripcion)
                    VALUES (:nombre, :descripcion)";

            $stmt = $this->conn->prepare($sql);

           // Bind parameters
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':descripcion', $data['descripcion']);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(['message' => 'Categoria de receta creada exitosamente']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Error al crear la categoria de receta']);
            }
        }//fin crearCategoriaReceta

        // Listar todas las categorías
        public function listarCategorias() {
            $stmt = $this->conn->prepare("SELECT * FROM categorias_recetas");
            $stmt->execute();
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($categorias) {
                http_response_code(200);
                echo json_encode($categorias);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'No hay categorías registradas']);
            }
        }

        // Buscar una categoría por ID
        public function buscarCategoria() {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['categoriaId'] ?? null;

            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'ID de categoría no proporcionado']);
                return;
            }

            $stmt = $this->conn->prepare("SELECT * FROM categorias_recetas WHERE categoriaId = :id");
            $stmt->execute([':id' => $id]);
            $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($categoria) {
                http_response_code(200);
                echo json_encode($categoria);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Categoría no encontrada']);
            }
        }

        // Actualizar una categoría
        public function actualizarCategoria() {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['categoriaId']) || !isset($data['nombre']) || !isset($data['descripcion'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Faltan datos necesarios']);
                return;
            }

            $sql = "UPDATE categorias_recetas SET nombre = :nombre, descripcion = :descripcion WHERE categoriaId = :id";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $data['categoriaId']);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':descripcion', $data['descripcion']);

            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(['message' => 'Categoría actualizada exitosamente']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Error al actualizar la categoría']);
            }
        }

        // Eliminar una categoría
        public function eliminarCategoria() {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['categoriaId'] ?? null;

            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'ID de categoría no proporcionado']);
                return;
            }

            $stmt = $this->conn->prepare("DELETE FROM categorias_recetas WHERE categoriaId = :id");

            if ($stmt->execute([':id' => $id])) {
                http_response_code(200);
                echo json_encode(['message' => 'Categoría eliminada exitosamente']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Error al eliminar la categoría']);
            }
        }

    }//fin class Categorias_recetasController
?>