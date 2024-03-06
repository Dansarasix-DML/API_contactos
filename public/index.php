<?php
    /**
     * @author Daniel Marín López
     * @version 0.01a
     * 
     * API Rest crud contactos
     * end points
     * Añadir:      POST    /contactos/
     * Leer:        GET     /contactos/{id}
     * Modificar:   PUT     /contactos/{id}
     * Borrar:      DELETE /contactos/
     */

    require "../bootstrap.php";

    
    use App\Core\Router;
    use App\Controllers\ContactosController;
    use App\Controllers\UsuariosController;
    use App\Controllers\AuthController;
    use \Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    
    //Cabeceras puñeteras
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");

    // echo "HOLA MUNDO";

    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if ($requestMethod == "OPTIONS") {
        die();
    }


    $requestMethod = $_SERVER['REQUEST_METHOD'];

    $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $request);

    $Id = null;
    if (isset($uri[2])) {
        $Id =  (int) $uri[2];
    }

    if (KK) {
        //Proceso login
        if ($request == "/login") {
            $auth = new AuthController($requestMethod);
            if (!$auth->loginFromRequest()) {
                exit(http_response_code(401));
            }
        }

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        //Autentificación
        $autHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $arr = explode(" ", $autHeader);
        $jwt = $arr[1];

        if($jwt){
            try {
                $decoded = (JWT::decode($jwt, new Key(KEY, "HS256")));
            } catch (Exception $e) {
                echo json_encode(array(
                    "message" => "Access denied",
                    "error" => $e->getMessage()
                ));
                exit(http_response_code(401));
            }
        }
    }

    //Si pasamos, el token es válido

    $router = new Router();

    $router->add (array(
        'name'=>'home',
        'path'=>'/^\/contactos\/([0-9]+)?$/',
        'action'=>ContactosController::class),
        
    );

    $router->add (array(
        'name'=>'usuarios',
        'path'=>'/^\/usuarios\/([0-9]+)?$/',
        'action'=>UsuariosController::class),
        
    );
        
    //Comprobamos ruta válida
    $route = $router->match($request);

    if ($route) {
        //Si hay ruta válida, llama controlador
        $controllerName = $route['action'];
        $controller = new $controllerName($requestMethod, $Id);
        $controller->processRequest();    
    } else {
        //Si no, mensaje de error
        $response["status_code_header"] = "HTTP/1.1 404 Not Found";
        $response["body"] = null;
        return json_encode($response);
    }


?>