<?php
namespace Classes;
use Classes\MonolithApiException;

include_once("GeneralObject.class.php");
class Category extends GeneralObject
{
    public $title;
    public $id;
    public $product_count;
    public $labels_counter;

    public function __construct($id, $title, $labels = [])
    {
        parent::__construct($id, $title);
        foreach ($labels as $label) {
            if (isset($this->labels_counter[$label]))
                $this->labels_counter[$label]++;
            else
                $this->labels_counter[$label] = 1;
        }
    }
}
