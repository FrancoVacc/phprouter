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
}
