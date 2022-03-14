<?php

use Classes\MonolithApiException;

include_once("Attribute.class.php");
include_once("Label.class.php");
include_once("Product.class.php");
include_once("Category.class.php");
class GeneralObject
{
    public $id;
    public $title;

    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
    }
}
