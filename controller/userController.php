<?php

use Laminas\Diactoros\Request;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

require './model/User.php';

class userController
{

    public function getUsers()
    {
        $user = new User;
        $users = $user->getUsers();
        return new JsonResponse($users);
    }

    public function getUser($id)
    {
        $user = new User;
        $users = $user->getUser($id);
        return new JsonResponse($users);
    }

    public function newUser(ServerRequest $request)
    {
        $data = $request->getParsedBody();

        if (empty($data)) {
            $json = $request->getBody()->getContents();
            $data = json_decode($json) ?? [];
        }

        $name = $data->name;
        $email = $data->email;
        $password = $data->password;

        // //? Validations

        if (!preg_match("/^[a-zA-Z]*$/", $name)) {
            return ['Message' => 'Error on validation ' . $name];
        }

        if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)) {
            return ['Message' => 'Error on validation ' . $email];
        }

        if (!preg_match("^(?=.*[A-Za-z]).{8,}$^", $password)) {
            return ['Message' => 'Error on validation ' . $password];
        }


        // //? Guardado en la base de datos

        $request = ["name" => $name, "email" => $email, "password" => password_hash($password, PASSWORD_DEFAULT)];
        $user = new User;
        return $user->save($request);
    }

    public function updateUser($id, ServerRequest $request)
    {
        $user = new User;
        $data = $request->getParsedBody();

        if (empty($data)) {
            $json = $request->getBody()->getContents();
            $data = json_decode($json) ?? [];
        }

        $data->password = password_hash($data->password, PASSWORD_DEFAULT);

        return new JsonResponse($user->update($id, $data));
    }
    public function deleteUser($id)
    {
        $user = new User;
        return new JsonResponse($user->delete($id));
    }
}
