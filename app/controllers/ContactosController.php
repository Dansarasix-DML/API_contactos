<?php
    /**
     * @author Daniel Marín López
     * @version 0.01a
     * 
     */

    namespace App\Controllers;

    use App\Models\Contacto;

    class ContactosController {

        private $requestMethod;
        private $contactoID;
        // private $contactoProv;
        private $contacto;

        /**
         * Constructor
         */
        public function __construct($requestMethod, $contactoID) {
            $this->requestMethod = $requestMethod;
            $this->contactoID = $contactoID;
            $this->contacto = Contacto::getInstancia();
        }

        /**
         * Función que procesa la petición
         */
        public function processRequest() {
            switch ($this->requestMethod) {
                case 'GET':
                    if ($this->contactoID) {
                        $response = $this->getContacto($this->contactoID);
                    } else {
                        $response = $this->getAllContacto(); //Depende de la situación, es mejor poner un límite
                    };
                    break;
                case 'POST':
                    $response = $this->createContactoFromRequest();
                    break;
                case 'PUT':
                    $response = $this->updateContactoFromRequest($this->contactoID);
                    break;
                case 'DELETE':
                    $response = $this->deleteContactoFromRequest($this->contactoID);
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

        private function getContacto($id) {
            $result = $this->contacto->get($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $response["status_code_header"] = "HTTP/1.1 200 OK";
            $response["body"] = json_encode($result);
            return $response;
        }

        private function getAllContacto() {
            $result = $this->contacto->getAll();
            $response["status_code_header"] = "HTTP/1.1 200 OK";
            $response["body"] = json_encode($result);
            return $response;
        }
        private function getContactoProv($provincia) {
            $result = $this->contacto->getContactosByProvincia($provincia);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $response["status_code_header"] = "HTTP/1.1 200 OK";
            $response["body"] = json_encode($result);
            return $response;
        }
        private function createContactoFromRequest() {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateContacto($input)) {
                return $this->unprocessableEntityResponse();
            }
            $this->contacto->set($input);
            $response["status_code_header"] = "HTTP/1.1 201 OK";
            $response["body"] = json_encode([
                'mensaje' => 'Contacto creado'
            ]);
            return $response;
        }

        private function validateContacto($input) {
            if (!isset($input['nombre'])) {
                return false;
            }
            if (!isset($input['telefono'])) {
                return false;
            }
            return true;
        }

        private function updateContactoFromRequest($id) {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            $result = $this->contacto->get($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateContacto($input)) {
                return $this->unprocessableEntityResponse();
            }
            $this->contacto->edit($id, $input);
            $response["status_code_header"] = "HTTP/1.1 202 OK";
            $response["body"] = json_encode([
                'mensaje' => 'Contacto modificado'
            ]);
            return $response;
        }

        private function deleteContactoFromRequest($id) {
            $result = $this->contacto->get($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $this->contacto->delete($id);
            $response["status_code_header"] = "HTTP/1.1 203 OK";
            $response["body"] = json_encode([
                'mensaje' => 'Contacto eliminado'
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