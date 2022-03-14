<?php

namespace Classes;

include_once("MonolithApiManager.class.php");
include_once("Parser.class.php");


class MonolithGetPageData
{
    public function getPageData()
    {
        $MonolithApiManager = new MonolithApiManager();
        $data = $MonolithApiManager->getData("https://backend-assignment.bylith.com/index.php");
        $Parser = new Parser();
        return $Parser->parseAsJsonData($data);
    }
}
