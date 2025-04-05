<?php

use Laminas\Diactoros\Request;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use MiladRahimi\PhpRouter\Routing\Route;

require './config/dbconection.php';

class Products
{
    public function getAllproducts()
    {

        global $con;

        $stmt = $con->prepare("SELECT products.id, products.barcode, products.name, products.price, category.name as category FROM products JOIN category ON products.category = category.id");
        $stmt->execute();
        $results = $stmt->get_result();

        $data_arr = [];

        if ($results->num_rows > 0) {
            while ($data = $results->fetch_object()) {
                array_push($data_arr, $data);
            }

            return $data_arr;
        } else {
            return new RedirectResponse('/notfound');
            return ['message' => 'Resourse Error'];
        }
    }

    public function barcode($barcode)
    {
        global $con;
        $stmt = $con->prepare(
            "SELECT products.id, products.barcode, products.name, products.price, category.name as category 
                                FROM products 
                                JOIN category 
                                ON products.category = category.id 
                                WHERE barcode = ? 
                                LIMIT 1"
        );
        $stmt->bind_param('s', $barcode);
        $stmt->execute();
        $results = $stmt->get_result();

        if ($results->num_rows > 0) {
            return $results->fetch_object();
        } else {
            return ['message' => 'Error on resourses'];
        }
    }
    public function name($name)
    {
        global $con;
        $stmt = $con->prepare("SELECT products.id, products.barcode, products.name, products.price, category.name as category FROM products JOIN category ON products.category = category.id WHERE products.name LIKE ? ORDER BY products.name DESC");
        $search = "%" . $name . "%";
        $stmt->bind_param('s', $search);
        $stmt->execute();
        $results = $stmt->get_result();
        $data_arr = [];

        if ($results->num_rows > 0) {
            while ($data = $results->fetch_object()) {
                array_push($data_arr, $data);
            }
            return $data_arr;
        } else {
            return ['message' => 'Error on resourses'];
        }
    }

    public function storage($product)
    {
        $query = "INSERT INTO products (barcode, name, price, category) VALUES (?,?,?,?)";
        global $con;

        $stmt = $con->prepare($query);
        $stmt->bind_param('ssss', $product['barcode'], $product['name'], $product['price'], $product['category']);
        $stmt->execute();
        if ($stmt->error) {
            return ['Message' => 'Error on saving new product'];
        } else {
            return ['Message' => 'Product save successfully'];
        }
    }

    public function update($product, $id)
    {
        global $con;
        $stmt = $con->prepare('UPDATE products SET barcode = ?, name=?, price = ?, category = ?, updated_at = CURRENT_TIMESTAMP WHERE id LIKE ?');
        $stmt->bind_param('ssssi', $product['barcode'], $product['name'], $product['price'], $product['category'], $id);
        $stmt->execute();
        if ($stmt->error) {
            return ['Message' => 'Error on update resourse'];
        } else {
            return ['Message' => 'Resourse updated successfully'];
        }
    }

    public function destroy($id)
    {
        global $con;
        $stmt = $con->prepare("DELETE FROM products WHERE id LIKE ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        if ($stmt->error) {
            return ['Message' => 'Error on delete'];
        } else {
            return ['Message' => 'Resourse deleted successfully'];
        }
    }
}
