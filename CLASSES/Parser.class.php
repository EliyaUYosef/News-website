<?php

namespace Classes;

class Parser
{
    public $title;
    public $id;

    public function parseAsJsonData($data)
    {
        $mainArr = json_decode($data, true);

        return $mainArr;
    }
}
