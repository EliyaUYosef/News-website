<?php

include_once("Classes/MonolithGetPageData.class.php");
include_once("Classes/DataRender.class.php");

use Classes\DataRender;

$Data = DataRender::getInstance();

$products = $Data->getProducts();
echo json_encode($products);
?>