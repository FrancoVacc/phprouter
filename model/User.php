<?php

use Laminas\Diactoros\Response\JsonResponse;

require './config/dbconection.php';


class User
{
    //? Ver Todos los Usuarios
    public function getUsers()
    {
        global $con;

        $query = "SELECT * FROM users";

        $request = $con->prepare($query);
        $request->execute();
        $result = $request->get_result();

        $data_arr = [];
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_object()) {
                array_push($data_arr, $data);
            }

            return $data_arr;
        } else {
            return ['Message' => 'Data Not Found'];
        }
    }

    //? Ver usuario por id
    public function getUser($id)
    {
        global $con;

        $query = "SELECT * FROM users WHERE id = ? LIMIT 1";


        $request = $con->prepare($query);
        $request->bind_param('i', $id);
        $request->execute();
        $result = $request->get_result();

        $data_arr = [];
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_object()) {
                array_push($data_arr, $data);
            }

            return $data_arr;
        } else {
            return ['Message' => 'Data not Found'];
        }
    }

    //? Cargar nuevo usuario
    public function save($request)
    {
        global $con;
        $name = $request['name'];
        $email = $request["email"];
        $password = $request["password"];



        $stmt = $con->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
        $stmt->bind_param('sss', $name, $email, $password);
        $stmt->execute();

        if ($stmt->error) {
            return json_encode(["Message" => "Error with the Add Action"]);
        } else {
            return json_encode(["Message" => "New user added"]);
        }
    }

    //? Modificar usuario
    public function update($id, $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;


        global $con;
        $stmt = $con->prepare("UPDATE users SET name=?, email=?, password=? WHERE id = ?");
        $stmt->bind_param('sssi', $name, $email, $password, $id);
        $stmt->execute();

        if ($stmt->num_rows() == 0) {
            return ['Message' => 'Data updated successfully'];
        } else {
            return ['Message' => 'Error on data update'];
        }
    }

    //? Eliminar Usuario
    public function delete($id)
    {
        global $con;
        $stmt = $con->prepare("DELETE FROM users WHERE id LIKE ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->num_rows() == 0) {
            return ['Message' => 'User deleted successfully'];
        } else {
            return ['Message' => 'Error on delete user'];
        }
    }
}
