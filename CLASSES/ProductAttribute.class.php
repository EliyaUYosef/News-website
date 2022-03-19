<?php
namespace Classes;
use Classes\MonolithApiException;

include_once("GeneralObject.class.php");
class ProductAttribute extends GeneralObject
{
    public $title;
    public $id;
    public $labels;
    function __construct($id, $title)
    {
        parent::__construct($id, $title);
    }

    public function set_labels($labels = [])
    {
        $this->labels = $labels;
    }
}
