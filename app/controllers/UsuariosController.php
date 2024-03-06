<?php
    /**
     * @author Daniel Marín López
     * @version 0.01a
     * 
     */

    namespace App\Controllers;

    use App\Models\Usuario;

    class UsuariosController {
        private $requestMethod;
        private $usuarioID;
        private $usuario;

        /**
         * Constructor
         */
        public function __construct($requestMethod, $usuarioID) {
            $this->requestMethod = $requestMethod;
            $this->usuarioID = $usuarioID;
            $this->usuario = Usuario::getInstancia();
        }


        /**
         * Función que procesa la petición
         */
        public function processRequest() {
            switch ($this->requestMethod) {
                case 'GET':
                    $response = ($this->usuarioID) ?
                    $this->getUsuario($this->usuarioID) :
                    $this->getAllUsuario(); //Depende de la situación, es mejor poner un límite
                    break;
                case 'POST':
                    $response = $this->createUsuarioFromRequest();
                    break;
                case 'PUT':
                    $response = $this->updateUsuarioFromRequest($this->usuarioID);
                    break;
                case 'DELETE':
                    $response = $this->deleteUsuarioFromRequest($this->usuarioID);
                    break;
                default:
                    $response = $this->notFoundResponse();
                    break;
            }
            header($response["status_code_header"]);
            if ($response["body"]) {
               echo $response["body"];
            }
        }

        private function getUsuario($id) {
            $result = $this->usuario->get($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $response["status_code_header"] = "HTTP/1.1 200 OK";
            $response["body"] = json_encode($result);
            return $response;
        }

        private function getAllUsuario() {
            $result = $this->usuario->getAll();
            $response["status_code_header"] = "HTTP/1.1 200 OK";
            $response["body"] = json_encode($result);
            return $response;
        }

        private function createUsuarioFromRequest() {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateUsuario($input)) {
                return $this->unprocessableEntityResponse();
            }
            $this->usuario->set($input);
            $response["status_code_header"] = "HTTP/1.1 201 OK";
            $response["body"] = json_encode([
                'mensaje' => 'Usuario creado'
            ]);
            return $response;
        }

        private function validateUsuario($input) {
            if (!isset($input['usuario'])) {
                return false;
            }
            if (!isset($input['password'])) {
                return false;
            }
            return true;
        }

        private function updateUsuarioFromRequest($id) {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            $result = $this->usuario->get($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateUsuario($input)) {
                return $this->unprocessableEntityResponse();
            }
            $this->usuario->edit($id, $input);
            $response["status_code_header"] = "HTTP/1.1 202 OK";
            $response["body"] = json_encode([
                'mensaje' => 'Usuario modificado'
            ]);
            return $response;
        }

        private function deleteUsuarioFromRequest($id) {
            $result = $this->usuario->get($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $this->usuario->delete($id);
            $response["status_code_header"] = "HTTP/1.1 203 OK";
            $response["body"] = json_encode([
                'mensaje' => 'Usuario eliminado'
            ]);
            return $response;
        }

        private function unprocessableEntityResponse() {
            $response["status_code_header"] = "HTTP/1.1 422 Unprocessable Entity";
            $response["body"] = json_encode([
                'error' => 'Invalid input'
            ]);
            return $response;
        }
        
        private function notFoundResponse() {
            $response["status_code_header"] = "HTTP/1.1 404 Not Found";
            $response["body"] = "No se ha encontrado"; //Antes null
            return $response;
        }


    }
    


?>