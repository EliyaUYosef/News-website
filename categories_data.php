<?php

include_once("Classes/MonolithGetPageData.class.php");
include_once("Classes/DataRender.class.php");

use Classes\DataRender;

$Data = DataRender::getInstance();

$categories = $Data->getCategories();

echo json_encode($categories);

?>