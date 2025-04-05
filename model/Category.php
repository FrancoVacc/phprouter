<?php

require './config/dbconection.php';

class Category
{

    public function index()
    {
        global $con;
        $stmt = $con->prepare("SELECT * FROM category");
        $stmt->execute();
        $result = $stmt->get_result();

        $data_arr = [];
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_object()) {
                array_push($data_arr, $data);
            }

            return $data_arr;
        } else {
            return ['Message' => 'No category registred'];
        }
    }
    public function storage($category)
    {
        global $con;
        $stmt = $con->prepare("INSERT INTO category (name) VALUES(?)");
        $stmt->bind_param('s', $category);
        $stmt->execute();

        if ($stmt->error) {
            return ['Message' => 'Error on adding resourse'];
        } else {
            return ['Message' => 'Resourse added successfully'];
        }
    }
}
