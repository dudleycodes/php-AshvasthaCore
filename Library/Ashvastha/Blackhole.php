<?php
/**
* @version    $Id:$
* @since      File available since release XYZ
*/

if ($_SERVER['SCRIPT_FILENAME'] == __FILE__ )    {	die('Direct access to this page denied');	}

/**
* Class description
*
* @category   Ashvastha
* @package
*/
class Blackhole
{
    public function __call($name, $arguments)
    {
        return false;
    }

    public static function __callStatic($name, $arguments)
    {
        return false;
    }
}
