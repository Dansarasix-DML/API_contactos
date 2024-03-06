<?php
    /**
     * @author Daniel Marín López
     * @version 0.01a
     * 
     */

    
    namespace App\Models;
    require_once "DBAbstractModel.php";
    
    class Contacto extends DBAbstractModel {
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

        // private $mensaje;

        public function getMensaje() {
            return $this->mensaje;
        }

        public function get($id = "") {
            if($id != '') {
                $this->query = "SELECT *
                FROM contacto WHERE id = :id";
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
            return [$this->rows[0] ?? null];
        }

        public function getAll(){
            $this->query = "SELECT * FROM contacto";

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

        public function set($data=[]) {
            foreach ($data as $campo=>$valor) {
                $$campo = $valor;                
            }
            
            $this->query = "INSERT INTO contacto (nombre, telefono, email, provincia)
            VALUES(:nombre, :telefono, :email, :provincia)";
            $this->parametros['nombre']= $nombre;
            $this->parametros['telefono']= $telefono;
            $this->parametros['email']= $email;
            $this->parametros['provincia']= $provincia;
            $this->getResultsFromQuery();
            //$this->execute_single_query();
            $this->mensaje = 'SH añadido';
        }

        public function edit($id = "", $user_data = []) {
            $fecha = new \DateTime();
            foreach ($user_data as $campo=>$valor) {
                $$campo = $valor;
                
            }
            $this->query = "UPDATE contacto
            SET nombre=:nombre,
            telefono=:telefono,
            email=:email,
            provincia=:provincia,
            updated_at=:fecha
            WHERE id = :id";

            $this->parametros['nombre'] = $nombre;
            $this->parametros['telefono'] = $telefono;
            $this->parametros['email'] = $email;
            $this->parametros['provincia']= $provincia;
            $this->parametros['fecha'] = date("Y-m-d H:m:s", $fecha->getTimestamp());
            $this->getResultsFromQuery();
            $this->mensaje = 'SH modificado';

        }

        public function delete($id="") {
            $this->query = "DELETE FROM contacto
            WHERE id = :id";
            $this->parametros['id']=$id;
            $this->getResultsFromQuery();
            $this->mensaje = 'Contacto eliminado';
        }

        public function getContactosByProvincia($provincia = "") {
            $this->query = "SELECT * FROM contacto WHERE provincia = :provincia";

            $this->parametros['provincia'] = $provincia;

            $this->getResultsFromQuery();

            if (count($this->rows) > 0) {
                foreach ($this->rows as $indice => $fila) {
                    foreach ($fila as $propiedad => $valor) {
                        $this->$propiedad = $valor;
                    }
                }
                $this->mensaje = 'Registros encontrados';
            } else {
                $this->mensaje = 'No se encontraron registros';
            }
            return [$this->rows ?? null];
        }
    }
    

?>