<?php

    session_name('SES'. substr(preg_replace('/[^A-Z]/', '', strtoupper(md5($_SERVER['HTTP_USER_AGENT']) .'P3PpeR')), 2, 6)) .'SION');

    $Administrator = new Ashvastha\Administrator\Administrator('session');
    $routerCache = new \Zend\Session\Container('ashvastha_router_myaccount');

    define('PATH_THEME', PATH_FRONTEND . 'themes' . DIRECTORY_SEPARATOR . 'myaccount2008' . DIRECTORY_SEPARATOR);
    define('URL_THEME', URL_FRONTEND . 'themes' . '/myaccount2008/');

    $t = (!empty($routerCache->myAccountLoginMessage))? $routerCache->myAccountLoginMessage: null;
    define('MYACCOUNT_LOGIN_MESSAGE', $t);
    unset($t);

    if (strtolower($Arguments->get(0)) == 'logout')
    {
        $Administrator->reset();
        $routerCache->myAccountLoginMessage = 'You have been logged out.';
        redirect(URL_FRONTEND);
        die;
    }


    //Validated submitted login information
    if (!empty($_POST['mellonAMinnoEmail']) AND !empty($_POST['mellonAMinnoPass']))
    {
        //User has submitted a username and password
        $result = (Boolean)$Administrator->validate($_POST['mellonAMinnoEmail'], $_POST['mellonAMinnoPass']);
        if ($result)
        {
            $routerCache->myAccountLoginMessage = 'Login successful.';
        }
        else
        {
            $routerCache->myAccountLoginMessage = 'Incorrect email address and/or password!';
            $Administrator->reset();
            unset($_POST['mellonAMinnoEmail']);
            unset($_POST['mellonAMinnoPass']);
        }
    }


    if (!$Administrator->isSynced())
    {
        //Login Page
        require(PATH_THEME .'loginForm.phtml');
        die;
    }
    else
    {
        //MyAccount Dashboard
        $HTMaster = new Ashvastha\HTMLet\Master();
        $viewMatrix = array();

        $HTMaster->setTitle('My Account');
        $HTMaster->setMetaRobots('noindex, nofollow');

        require(PATH_THEME . 'header.php');

        $directory = ($Arguments->get(0))? '_' . $Arguments->get(0): '_dashboard';
        $file = ($Arguments->get(1))? $Arguments->get(1) . '.phtml': 'index.phtml';
        $file = str_replace('/', null, $file);
        $file = str_replace('\\', null, $file);

        $HTMaster->addHTML(require_catch(PATH_THEME . $directory . DIRECTORY_SEPARATOR . $file));

        require(PATH_THEME . 'footer.php');

        echo $HTMaster->getRenderedHTML();
    }