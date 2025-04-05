<?php

use Laminas\Diactoros\Response\JsonResponse;

require './model/Category.php';

class categoryController
{
    public function getAll()
    {
        $category = new Category;
        return new JsonResponse($category->index());
    }
}
