<?php

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )  {   die('Direct access to this page denied');   }

return array (
    'database' =>
    array (
        'driver' => 'Pdo_Mysql',
        'name' => '',
        'username' => '',
        'password' => '',
        'type' => 'mysql',
        'host' => ''
    ),
    'debugIPs' => array(
        ''
    ),
    'system' =>
    array (
        'defaultTimezone' => 'America/Chicago',
        'router' => 'Yggdrasil'
    )
);
