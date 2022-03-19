<?php
namespace Classes;
use Classes\MonolithApiException;

include_once("GeneralObject.class.php");
class Product extends GeneralObject
{
    public $title;
    public $id;
    public $categories = [];
    public $labels = [];
    public $price = 0;

    public function __construct($id, $title, $categoriesArray, $price, $labels)
    {
        parent::__construct($id, $title);

        $this->price = $price;
        $this->labels = $labels;

        foreach ($categoriesArray as $category) {
            $this->categories[] = $category['id'];
        }
    }
}
