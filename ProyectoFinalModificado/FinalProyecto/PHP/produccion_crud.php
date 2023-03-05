<?php

class Master
{
    /**
     * Obtener todos los datos JSON
     */
    function get_all_data()
    {
        $json = (array) json_decode(file_get_contents('../DATOS/ordenProduccion.json'));
        $data = [];
        foreach ($json as $row) {
            $data[$row->id] = $row;
        }
        return $data;
    }
    /**
     * Obtener datos JSON únicos
     */
    function get_data($id = '')
    {
        if (!empty($id)) {
            $data = $this->get_all_data();
            if (isset($data[$id])) {
                return $data[$id];
            }
        }
        return (object) [];
    }

    function descarga(){
        
        //$datos = $this->get_all_data();
        $datos =  json_decode(file_get_contents('../DATOS/ordenProduccion.json'));
        $filename = '../descargas/produccion.json';
        $json = json_encode(array_values($datos), JSON_PRETTY_PRINT);
        $insert = file_put_contents($filename, $json);
       

    }

    function buscar(){
        $registros = [];
        $i = 1;
        
        $data = $this->get_all_data();

        if (isset($_POST['search'])) {
            if (strlen($_POST['keywords']) > 0) {
                $keywords = $_POST['keywords'];
                //echo '1'.$keywords;
                foreach($data as $reg){
           
                    if (strpos($reg->nombreIn, $keywords) !== false ){
                        //echo $keywords;
                        $registros[$i] = $reg;
                        $i++;
                    }
                }
                return  $registros;
            }
        }    
        
        return $data;

        
    }
    /**
     * Insertar datos en un archivo JSON
     */
    function insert_to_json()
    {
        $nrorden = addslashes($_POST['nrorden']);
        $fecha = addslashes($_POST['fecha']);
        $ncliente = addslashes($_POST['ncliente']);
        $data = $this->get_all_data();
        $id = array_key_last($data) + 1;
        $data[$id] = (object) [
            "id" => $id,
            "fecha" => $fecha,
            "ncliente" => $ncliente
        ];
        $json = json_encode(array_values($data), JSON_PRETTY_PRINT);
        $insert = file_put_contents('../DATOS/ordenProduccion.json', $json);
        if ($insert) {
            $resp['status'] = 'success';
        } else {
            $resp['failed'] = 'failed';
        }
        return $resp;
    }
    /**
     * Actualizar datos del archivo JSON
     */
    function update_json_data()
    {
        $id = $_POST['id'];
        $ncliente = addslashes($_POST['ncliente']);
        $fecha = addslashes($_POST['fecha']);
        
        $data = $this->get_all_data();
        $data[$id] = (object) [
            "id" => $id,
            "ncliente" => $ncliente,
            "fecha" => $fecha
        ];
        $json = json_encode(array_values($data), JSON_PRETTY_PRINT);
        $update = file_put_contents('../DATOS/ordenProduccion.json', $json);
        if ($update) {
            $resp['status'] = 'success';
        } else {
            $resp['failed'] = 'failed';
        }
        return $resp;
    }
    /**
     * Eliminar datos del archivo JSON
     */
    function delete_data($id = '')
    {
        if (empty($id)) {
            $resp['status'] = 'failed';
            $resp['error'] = 'El ID de miembro dado está vacío.';
        } else {
            $data = $this->get_all_data();
            if (isset($data[$id])) {
                unset($data[$id]);
                $json = json_encode(array_values($data), JSON_PRETTY_PRINT);
                $update = file_put_contents('../DATOS/ordenProduccion.json', $json);
                if ($update) {
                    $resp['status'] = 'success';
                } else {
                    $resp['failed'] = 'failed';
                }
            } else {
                $resp['status'] = 'failed';
                $resp['error'] = 'El ID de miembro dado no existe en el archivo JSON.';
            }
        }
        return $resp;
    }
}
