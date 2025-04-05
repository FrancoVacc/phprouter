<?php

use Laminas\Diactoros\Request;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

require './model/Products.php';

class productController
{

    //* Devuelve todos los productos
    public function index()
    {
        $products = new Products;
        return new JsonResponse($products->getAllproducts());
    }

    //* Devuelve un producto que coincida con el código de barra
    public function getProduct($barcode)
    {
        $product = new Products;
        return new JsonResponse($product->barcode($barcode));
    }

    //* Devuelve un producto que coincida con el nombre o parte del nombre
    public function getName($name)
    {
        $product = new Products;
        return new JsonResponse($product->name($name));
    }

    //* Agrega un nuevo producto
    public function newProduct(ServerRequest $request)
    {

        $data = $request->getParsedBody();

        if (empty($data)) {
            $json = $request->getBody()->getContents();
            $data = json_decode($json) ?? [];
        }

        //! validacion de la request
        $barcode = $data->barcode;
        $name = $data->name;
        $price = $data->price;
        $category = $data->category;

        if (!preg_match('^(779\d{10}|779\d{5}|\d{8}|\d{13})$^', $barcode)) {
            return new JsonResponse(['Message' => 'Error on barcode ' . $barcode]);
        }

        if (!preg_match('^[A-Za-zÁÉÍÓÚáéíóú0-9 ]+$^', $name)) {
            return new JsonResponse(['Message' => 'Error on name ' . $name]);
        }
        if (!preg_match('^\d+(\.\d{1,2})?$^', $price)) {
            return new JsonResponse(['Message' => 'Error on price ' . $price]);
        }
        if (!preg_match('^\d+$^', $category)) {
            return new JsonResponse(['Message' => 'Error on category ' . $category]);
        }

        $product = ['barcode' => $barcode, 'name' => $name, 'price' => $price, 'category' => $category];
        $products = new Products;
        return new JsonResponse($products->storage($product));
    }

    //* actualiza un producto
    public function updateProduct(ServerRequest $request, $id)
    {
        $data = $request->getParsedBody();

        if (empty($data)) {
            $json = $request->getBody()->getContents();
            $data = json_decode($json) ?? [];
        }

        //! validacion de la request
        $barcode = $data->barcode;
        $name = $data->name;
        $price = $data->price;
        $category = $data->category;

        if (!preg_match('^(779\d{10}|779\d{5}|\d{8}|\d{13})$^', $barcode)) {
            return new JsonResponse(['Message' => 'Error on barcode ' . $barcode]);
        }

        if (!preg_match('^[A-Za-zÁÉÍÓÚáéíóú0-9 ]+$^', $name)) {
            return new JsonResponse(['Message' => 'Error on name ' . $name]);
        }
        if (!preg_match('^\d+(\.\d{1,2})?$^', $price)) {
            return new JsonResponse(['Message' => 'Error on price ' . $price]);
        }
        if (!preg_match('^\d+$^', $category)) {
            return new JsonResponse(['Message' => 'Error on category ' . $category]);
        }

        $product = ['barcode' => $barcode, 'name' => $name, 'price' => $price, 'category' => $category];
        $products = new Products;
        return new JsonResponse($products->update($product, $id));
    }

    //* Elimina un producto
    public function destroyProduct($id)
    {
        $product = new Products;
        return new JsonResponse($product->destroy($id));
    }
}
