<?php

namespace Classes;

use Attribute;
use Category;
use Label;
use Product;

include_once("MonolithGetPageData.class.php");
include_once("Attribute.class.php");
include_once("Label.class.php");
include_once("Product.class.php");
include_once("Category.class.php");
include_once("MonolithApiException.class.php");
include_once("MonolithApiException.class.php");
include_once("Parser.class.php");

class DataRender
{
    private static $instance = null;
    private $data = null;

    private $products = [];
    private $categories = [];
    private $labels = [];

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DataRender();
        }

        return self::$instance;
    }

    public function getProducts()
    {
        $this->getData();
        $products_array_is_parsed = [];
        foreach ($this->products as $p_id => $Product) {
            $categories_str = $this->parseCategoriesToProduct($Product->categories);
            $attributes_array_is_parsed = $this->parseAttributesAndLabelsToProduct($Product->labels);

            $products_array_is_parsed[] = [
                'ID' => $p_id,
                'title' => $Product->title,
                'price' => $Product->price,
                'categories' => $categories_str,
                'attributes' => $attributes_array_is_parsed
            ];
        }

        // return json_encode($products_array_is_parsed);
        return $products_array_is_parsed;
    }

    public function getCategories()
    {
        $this->getData();
        $categories_array_is_parsed = [];
        foreach ($this->categories as $c_id => $Category) {
            $attributes_array = $this->parseAttributesAndLabelsForCategories($Category->labels_counter);

            $categories_array_is_parsed[] = [
                'ID' => $c_id,
                'title' => $Category->title,
                'product_amount' => $Category->product_count,
                'attributes' => $attributes_array
            ];
        }
        MonolithApiException::debug($categories_array_is_parsed);
        return $categories_array_is_parsed;
    }

    private function parseAttributesAndLabelsForCategories($category_labels_counter)
    {
        if (!$category_labels_counter || !is_array($category_labels_counter))
            return [];

        $attributes_array = [];
        foreach ($category_labels_counter as $label_id => $label_count) {
            $attributes_array[] = [
                'attribute_name' => $this->labels[$label_id]->attribute_name,
                'label_title' => $this->labels[$label_id]->title,
                'label_count' => $label_count,
            ];
        }

        return $attributes_array;
    }

    private function parseAttributesAndLabelsToProduct($product_labels)
    {
        if (!$product_labels)
            return '';

        $attributes_names_array = [];
        foreach ($product_labels as $label) {
            $attributes_names_array[$this->labels[$label]->attribute_name][] = $this->labels[$label]->title;
        }
        $attributes_array_is_parsed = '';
        foreach ($attributes_names_array as $attribute_name => $labels) {
            $attributes_array_is_parsed .=  $attribute_name . " : " . implode('/', $labels);
            if ($labels === end($attributes_names_array))
                $attributes_array_is_parsed .= ".";
            else
                $attributes_array_is_parsed .= " | ";
        }

        return $attributes_array_is_parsed;
    }

    private function parseCategoriesToProduct($product_categories)
    {
        if (!$product_categories)
            return '';

        $categories_str = '';
        foreach ($product_categories as $cat) {
            if ($categories_str)
                $categories_str .= ', ' . $this->categories[$cat]->title;
            else
                $categories_str .= $this->categories[$cat]->title;
        }

        return $categories_str;
    }

    private function getData()
    {
        if ($this->data) {
            return $this->data;
        }

        $MonolithApiManager = new MonolithGetPageData();
        $data = $MonolithApiManager->getPageData();
        $this->data = $this->parseData($data);

        return $this->data;
    }

    private function parseData($data)
    {
        $this->parseLabels($data['attributes']);
        $this->parseProducts($data['products']);
        foreach ($this->products as $Product) {
            foreach ($Product->labels as $label_id) {
                $Label = $this->labels[$label_id];
                $Label->product_count += 1;
                $this->labels[$label_id] = $Label;
            }
        }

        return [
            'products' => $this->products,
            'categories' => $this->categories,
            'labels' => $this->labels
        ];
    }

    private function parseProducts($productsArr)
    {
        foreach ($productsArr as $product) {
            $Product = new Product($product['id'], $product['title'], $product['categories'], $product['price'], $product['labels']);
            $categories_list = [];
            foreach ($product['categories'] as $cat) {
                $Category = new Category($cat['id'], $cat['title'], $product['labels']);
                $categories_list[] = $Category->id;
                if (isset($this->categories[$cat['id']])) {
                    unset($Category);
                    $Category = $this->categories[$cat['id']];
                    $Category->product_count++;
                } else {
                    $Category->product_count = 1;
                    $this->categories[$cat['id']] = $Category;
                }
            }

            if (isset($this->products[$product['id']]))
                continue;

            $this->products[$product['id']] = $Product;
        }
    }

    private function parseLabels($attributes)
    {
        foreach ($attributes as $attribute) {
            $Attribute = new Attribute($attribute['id'], $attribute['title']);
            $attribute_labels_array = [];
            foreach ($attribute['labels'] as $label) {
                $attribute_labels_array[] = $label['id'];
                if (isset($this->labels[$label['id']]))
                    continue;

                $this->labels[$label['id']] = new Label($label['id'], $label['title'], $Attribute->title);
            }
            $Attribute->set_labels($attribute_labels_array);
        }
    }
}
