<?php
    /**
     * @author Daniel Marín López
     * @version 0.01a
     * 
     */

    namespace App\Models;


    class Usuario extends DBAbstractModel{
        private static $instancia;
        public static function getInstancia(){
            if (!isset(self::$instancia)) {
                $miclase = __CLASS__;
                return self::$instancia = new $miclase;
            }
            return self::$instancia;
        }
        public function __clone(){
            trigger_error("CLONACIÓN NO PERMITIDA", E_USER_ERROR);
        }

        public function login($usuario, $passwd) {
            $this->query = "SELECT *
            FROM usuario
            WHERE usuario = :usuario and
            password = :password";

            $this->parametros["usuario"] = $usuario;
            $this->parametros["password"] = $passwd;

            $this->getResultsFromQuery();
            if(count($this->rows) == 1) {
                foreach ($this->rows[0] as $propiedad=>$valor) {
                    $this->$propiedad = $valor;
                }
                $this->mensaje = 'sh encontrado';
                
            } else {
                $this->mensaje = 'sh no encontrado';
            }
            return $this->rows[0] ?? null;

        }

        public function get($id=""){
            if($id != '') {
                $this->query = "SELECT *
                FROM usuario WHERE id = :id";
                //Cargamos los parámetros.
                $this->parametros['id']= $id;
                
                //Ejecutamos consulta que devuelve registros.
                $this->getResultsFromQuery();
                
            } 
            if(count($this->rows) == 1) {
                foreach ($this->rows[0] as $propiedad=>$valor) {
                    $this->$propiedad = $valor;
                }
                $this->mensaje = 'sh encontrado';
                // return $this->rows;
                
            } else {
                $this->mensaje = 'sh no encontrado';
            }
            return $this->rows[0] ?? null;
        }

        public function getAll(){
            $this->query = "SELECT * FROM usuario";

            $this->getResultsFromQuery();

            // Verificamos si hay resultados.
            if (count($this->rows) > 0) {
                // Si hay al menos un resultado, asignamos los valores al objeto actual.
                foreach ($this->rows as $indice => $fila) {
                    // Aquí, $fila representa un registro en forma de array asociativo.
                    foreach ($fila as $propiedad => $valor) {
                        $this->$propiedad = $valor;
                    }
                }
                $this->mensaje = 'Registros encontrados';
            } else {
                $this->mensaje = 'No se encontraron registros';
            }

            // Devolvemos los registros (puede ser un array de registros o null si no hay registros).
            return $this->rows ?? null;
        }

        public function set($data = []){
            foreach ($data as $campo=>$valor) {
                $$campo = $valor;                
            }
            
            $this->query = "INSERT INTO usuario (usuario, password)
            VALUES(:usuario, :password)";
            $this->parametros['usuario']= $usuario;
            $this->parametros['password']= $password;
            $this->getResultsFromQuery();
            //$this->execute_single_query();
            $this->mensaje = 'SH añadido';
        }
        public function edit($id = "", $user_data = []){
            // $fecha = new \DateTime();
            foreach ($user_data as $campo=>$valor) {
                $$campo = $valor;
                
            }
            $this->query = "UPDATE usuario
            SET usuario=:usuario,
            password=:password
            WHERE id = :id";

            $this->parametros['usuario'] = $usuario;
            $this->parametros['password'] = $password;
            // $this->parametros['fecha'] = date("Y-m-d H:m:s", $fecha->getTimestamp());
            $this->getResultsFromQuery();
            $this->mensaje = 'SH modificado';
        }
        public function delete($id = ""){
            $this->query = "DELETE FROM usuario
            WHERE id = :id";
            $this->parametros['id']=$id;
            $this->getResultsFromQuery();
            $this->mensaje = 'Usuario eliminado';
        }

    }



?>