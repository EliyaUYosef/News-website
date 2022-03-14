<?php

namespace Classes;

use Classes\MonolithApiException as Exception;

class MonolithApiManager
{
    public function getData($url)
    {
        if (!$url) {
            throw new Exception('URL is Empty');
        }
        $data = file_get_contents($url);
        if (!$data) {
            throw new Exception('No Data');
        }

        return $data;
    }
}
