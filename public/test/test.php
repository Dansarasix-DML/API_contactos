<?php
    /**
     * @author Daniel Marín López
     * @version 0.01a
     * 
     */
    
    use App\Models\Contacto;

    include "../app/config/config.php";
    include "../app/models/Contacto.php";
    

    $datos = array(
        "nombre" => "laura", 
        "telefono" => "618367935", 
        "email" => "noodiophpal100por100@gmail.com"
    );

    echo "Clases sin instanciar <br/>";
    $sh_singleton1=Contacto::getInstancia();

    // $sh_singleton1->set($datos);
    // var_dump($sh_singleton1->edit("1", ["freddy", "618367935", "freddyfazbear1996@gmail.com"]));
    var_dump($sh_singleton1->get(1));
    var_dump($sh_singleton1->getContactosByProvincia("C\u00f3rdoba"));

    echo $sh_singleton1->getMensaje();




?>