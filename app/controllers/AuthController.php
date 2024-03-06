<?php
    /**
     * @author Daniel Marín López
     * @version 0.01a
     * 
     */

    namespace App\Controllers;

    use \Firebase\JWT\JWT;
    // use \Firebase\JWT\Key;
    use App\Models\Usuario;

    class AuthController{
        private $requestMethod;
        private $userId;
        private $users;

        public function __construct($requestMethod){
            $this->requestMethod = $requestMethod;
            $this->users = Usuario::getInstancia();
        }

        public function loginFromRequest() {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);

            $usuario = $input["usuario"];
            $passwd = $input["password"];

            $dataUser = $this->users->login($usuario, $passwd);

            if ($dataUser) {
                // $key = "1234567890123456";
                $issuer_claim = "http://contactos.es";
                $audience_claim = "http://contactos.es";
                $issuedat_claim = time();
                $notbefore_claim = time();
                $expire_claim = $issuedat_claim + 3600;

                $token = array(
                    'iss' => $issuer_claim,
                    'aud' => $audience_claim,
                    'iat' => $issuedat_claim,
                    'nbf' => $notbefore_claim,
                    'exp' => $expire_claim,
                    "data" => array(
                        "usuario" => $usuario,
                    )
                );

                $jwt = JWT :: encode($token, KEY,'HS256');

                $res = json_encode(
                    array(
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "email" => $usuario,
                    "expireAt" => $expire_claim
                ));

                $response['status_code_header'] = 'HTTP/1.1 201 Created';
                $response['body'] = $res;

            } else {
                $response['status_code_header'] = 'HTTP/1.1 401 Login failed';
                $response['body'] = null;
            }
            header($response['status_code_header']);
            if ($response['body' ]) {
                echo $response['body'];
            }

        }
    }




?>