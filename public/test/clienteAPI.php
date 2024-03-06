<?php
    /**
     * @author Daniel Marín López
     * @version 0.01a
     * 
     */

    require "../../bootstrap.php";

    //Dirección API
    $issuer ='http://contactos.es';

    //Credenciales
    $credenciales = [
        'usuario' => 'Admin',
        'password'=> 'admin'
    ];

    //Obtenemos el token de acceso.
    $token = obtainToken($credenciales, $issuer);
    echo $token;

    //Tests

    //Recuperamos todos los contactos.
    getAllContactos($token) ;

    //Recuperamos el contacto cuyo id es 11
    getContacto($token, 5);

    $contacto = [
        "nombre" => "kk",
        "telefono" => "123456789",
        "email" => "kk@gmail.com"
    ];
    
    // setContacto($token, $contacto);

    // deleteContacto($token, 9);

    $editContacto = [
        "nombre" => "Laura Luque",
        "telefono" => "618367935",
        "email" => "noodiophpal100por100@gmail.com"
    ];
    editContacto($token, $editContacto, 1);



    function obtainToken($datos, $issuer) {
        echo "Obteniendo token ...<br/>";

        //Comprobamos si tenemos el token

        //Cargamos ruta de login
        $uri = $issuer . "/login";

        // Petición curl

        //Inicio
        $ch = curl_init();

        //Parametrización
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Petición
        $response = curl_exec($ch);

        //Comprabamos que el token es correcto
        $response = json_decode($response, true);
        if (!isset($response['jwt'])) {
            exit('failed, exiting. ');
        }

        echo "Token OK <br/>";
        //Almacenamiento local del token.

        return $response['jwt'];

    }

    function getAllContactos($token) {
        echo "<br/>Obteniendo todos los contactos ...<br/>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://contactos.es/contactos/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', "Authorization: Bearer $token"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        echo $response;
    }

    function getContacto($token, $id) {
        echo "<br/>Obteniendo el contacto con id: $id ...<br/>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://contactos.es/contactos/$id");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', "Authorization: Bearer $token"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        echo $response;
    }

    function setContacto($token, $datos) {
        echo "<br/>Creando nuevo contacto ...<br/>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://contactos.es/contactos/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json', 
            "Authorization: Bearer $token"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if ($response === false) {
            echo 'Error cURL: ' . curl_error($ch);
        } else {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code >= 200 && $http_code < 300) {
                echo "Contacto creado exitosamente.";
            } else {
                echo "Error al crear el contacto. Código de respuesta: $http_code";
                // Puedes agregar más manejo de errores según tus necesidades
            }
        }
    }

    function editContacto($token, $datos, $id) {
        echo "<br/>Modificando contacto con id: $id ...<br/>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://contactos.es/contactos/$id");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json', 
            "Authorization: Bearer $token"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);
        echo $response;

    }

    function deleteContacto($token, $id) {
        echo "<br/>Eliminando contacto con id: $id ...<br/>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://contactos.es/contactos/$id");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json', 
            "Authorization: Bearer $token"
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);
        echo $response;
    }


?>