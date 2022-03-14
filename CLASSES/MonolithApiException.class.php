<?php

namespace Classes;

class MonolithApiException extends \Exception
{
    public static function debug($object, $object1 = [], $object2 = [], $object3 = [], $object4 = [])
    {
        die("FOR " . date("d-m-Y - G:i:s", time() + 7200) . "<hr><pre/><div style='background:black;color:#eeeeee;'>" . print_r([$object, $object1, $object2, $object3, $object4], true) . "</div><hr>");
    }
}
