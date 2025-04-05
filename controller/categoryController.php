<?php

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

require './model/Category.php';

class categoryController
{
    public function getAll()
    {
        $category = new Category;
        return new JsonResponse($category->index());
    }
    public function newCategory(ServerRequest $request)
    {
        $data = $request->getParsedBody();

        if (empty($data)) {
            $json = $request->getBody()->getContents();
            $data = json_decode($json) ?? [];
        }

        $name = $data->name;

        if (!preg_match('^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$^', $name)) {
            return new JsonResponse(['Message' => 'Error on validation category name']);
        }

        $category = new Category;
        return new JsonResponse($category->storage($name));
    }
}
