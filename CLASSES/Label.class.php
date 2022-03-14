<?php
include_once("GeneralObject.class.php");
class Label  extends GeneralObject
{
    public $title;
    public $id;
    public $product_count = 0;
    public $attribute_name;

    function __construct($id, $title, $attribute_name = '')
    {
        parent::__construct($id, $title);

        $this->attribute_name = $attribute_name;
    }
}
