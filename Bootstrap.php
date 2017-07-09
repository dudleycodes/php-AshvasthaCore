<?php

//=
//= SECURITY TWEAKS
//=
    unset($_REQUEST);
    
    //= Add noise to make timing attacks require more samples.
    time_nanosleep(0, rand(0, 97043));
    
    ini_set('expose_php', 'off');
    
    //Session Fixation & Hijacking
    // Don't include identifier in URL and don't read URL for identifier
    if (ini_get('session.use_trans_sid')) ini_set('session.use_trans_sid', 0);
    // Never use URLs with session identifiers
    if (!ini_get('session.use_only_cookies')) ini_set('session.use_only_cookies', 1);
    // Increase bits of entrophy
    ini_set('session.entrophy_length', 164);
    // Make session cookie only accesible through http (not client side scripting).  Not supported by all browsers
    if (!ini_get('session.cookie_httponly')) ini_set('session.cookie_httponly', true);
    //change from default to stronger hash
    ini_set('session.hash_function', 'sha512');
    //shorter session identifier but more characters
    ini_set('session.hash_bits_per_character', 6);

    ini_set('display_errors', 'Off');


//=
//= ENVIRONMENT
//=
    set_time_limit(30);
    
    // GLOBAL CONSTANTS
    IF (!defined('ASHVASTHA_FRONTEND_STARTTIME')) DEFINE('ASHVASTHA_FRONTEND', FALSE);
    DEFINE('ASHVASTHA_CORE_STARTTIME', microtime(true));
    $p = explode('/', strtolower($_SERVER['SERVER_PROTOCOL']));
    DEFINE('IS_HTTPS', (($p[0] == 'https')? TRUE: FALSE));
    DEFINE('PROTOCOL', $p[0]);
    DEFINE('PROTOCOL_VERSION', $p[1]);
    unset($p);
    DEFINE('PATH_CORE', __DIR__. DIRECTORY_SEPARATOR);
    DEFINE('PATH_LIBRARY', PATH_CORE . 'Library' . DIRECTORY_SEPARATOR);
    unset($pathCore);
    
    // Local Environment
    require_once(PATH_CORE. 'Global.php');


//=
//= CONFIGURATION
//=
    $fileName = PATH_CORE. 'Configs'. DIRECTORY_SEPARATOR . strtolower(DOMAIN) . '.php';

    if (!file_exists($fileName))
    {
        $fileName = PATH_CORE. 'Configs'. DIRECTORY_SEPARATOR .'default.php';

        if (!file_exists($fileName)) trigger_error('Configuration not found!', E_USER_ERROR);
    }


    $config = new \Zend\Config\Config(require_once $fileName);


    if (!$config->valid()) trigger_error('Invalid configuration!', E_USER_ERROR);
    unset($fileName);


//=
//= LOGGING
//=
    define('DEBUG_MODE', in_array(filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP), array($config->debugIP)));

    ini_set('log_errors', FALSE);

    $Logger = new \Zend\Log\Logger;
    
    if (DEBUG_MODE)
    {
        require PATH_LIBRARY . 'FirePHP\FirePHP.class.php';
        require PATH_LIBRARY . 'FirePHP\fb.php';

        $Logger->addWriter(new \Zend\Log\Writer\FirePhp);
        $Logger->info('Running in DEBUG MODE');
        
        ini_set('display_errors', 'On');
        error_reporting(-1);
    }
    else
    {
        register_shutdown_function('stream_frontendCrashout');

        $fileName = PATH_CORE. 'Logs'. DIRECTORY_SEPARATOR. date("Y.m.d") .'.log';

        if (!file_exists($fileName)) touch($fileName);
        $Logger->addWriter(new Zend\Log\Writer\Stream(PATH_CORE. 'Logs'. DIRECTORY_SEPARATOR. date("Y.m.d") .'.log'));

        error_reporting(E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING);
    }

    set_error_handler('\error_handler', E_ALL);
    set_exception_handler('\exception_handler');


//=
//= CONNECT TO DATABASE
//=
    $Database = new Zend\Db\Adapter\Adapter(array(
                                                'driver'   => $config->database->driver,
                                                'database' => $config->database->name,
                                                'hostname' => $config->database->host,
                                                'username' => $config->database->username,
                                                'password' => $config->database->password,
                                                'charset' => 'UTF-8'
                                            ));


//=
//= ROUTER SWITCH
//=         \\Routers gonna route
//=

    $routerFile = str_replace(DIRECTORY_SEPARATOR, null, $config->system->router);
    $routerFile = PATH_CORE .'Routers'. DIRECTORY_SEPARATOR . $routerFile . '.php';

    if (file_exists($routerFile))
    {
        $Arguments = new Ashvastha\PathWalker\PathWalker('session');

        require_once $routerFile;
    }
    else
    {
        trigger_error("Could not find router: $routerFile", E_USER_ERROR);
    }
