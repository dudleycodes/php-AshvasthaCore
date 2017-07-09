<?php

//=
//= Set up Router Environmental Variables
//=
    session_name('JSESSIONID');


//=
//= Process Portal
//=
    $Portal = new \Ashvastha\Portal\Portal($Arguments, DOMAIN);

    if (!$Portal->is_synced()) trigger_error(__FILE__ . '  Unable to load portal.', E_USER_ERROR);

    if ($Portal->get_theme())
    {
        define('PATH_THEME', PATH_FRONTEND . 'themes' . DIRECTORY_SEPARATOR . $Portal->get_theme() . DIRECTORY_SEPARATOR);
        define('URL_THEME', URL_FRONTEND . 'themes' . '/' . $Portal->get_theme() .'/');
    }
    else
    {
        define('PATH_THEME', False);
        define('URL_THEME', False);
        trigger_error(__FILE__ . '  Portal #' . $Portal->get_id() .' does not have an assigned theme', E_USER_ERROR);
    }


//=
//= Process Yggdrasil Page
//=

    $Leaf = new \Ashvastha\Yggdrasil\Leaf($Arguments, $Portal->get_id());
    
    if (!$Leaf->is_synced())
    {
        \set_httpStatus(404);
        echo '<h1>404 - Page Not Found!</h1>';
        echo '<h3>Sorry, but the requested page could not be found.</h3>';
        die;
    }
    
    if (!$Leaf->get_templateName())
    {
        \set_httpStatus(404);
        echo '<h1>500 - Internal Server Error!</h1>';
        echo '<h3>Sorry, but the requested page could not be loaded</h3>';
        trigger_error('Router/Yggdrasil - YggdrasilPage #' . $YggdrasilPage->getId() .' does not have an assigned theme', E_USER_ERROR);
        die;
    }
    
    define('FILE_THEME', PATH_THEME . $YggdrasilPage->getTemplateName() .'.render.php');
    
    //primary
    $HTMaster->setTitle($Leaf->get_title());
    
    //metas
    $HTMaster->setMetaDescription($Leaf->getMetaDescription());
    $HTMaster->setMetaRobots($Leaf->getMetaRobots());
    
    //Process application and data
    $appName = '\\Ashvastha\\App\\' . $YggdrasilPage->getAppName() .'\\App';
        $App = new $appName($Arguments, $YggdrasilPage->getAppCache(), $YggdrasilPage->getAppSettings());

        if (!$App) trigger_error('Router Yggdrasil entry had no specified app', E_USER_ERROR);

        $viewMatrix['app'] = $App->getHTMLet();
        if ($viewMatrix['app']->getTitle()) $HTMaster->setTitle($viewMatrix['app']->getTitle());

        //Process page data into viewMatrix
        $pageData = $YggdrasilPage->getPageData();
        if ($pageData)
        {
            //Temporary - for use during major development
            $t = $YggdrasilPage->validatePageDataSchema($pageData);
            if ($t !== true) trigger_error("Router Yggdrasil - $t", E_USER_ERROR);

            foreach ($pageData as $major => $positionData)
            {
                foreach ($positionData as $minor => $viewData)
                {
                    $t = new \Module\Module();
                    $viewMatrix[intval($major)][intval($minor)] = $t->getHTMLet();
                    unset($t);
                }
            }
        }

    //Pass control to theme
    if (!file_exists(FILE_THEME))
    {
        trigger_error('Router/Yggdrasil - YggdrasilPage id(' . $YggdrasilPage->getId() .') could not pass control to '
                      .'load FILE_THEME: ' . FILE_THEME, E_USER_ERROR);
    }
    
    if (file_exists(PATH_THEME . 'header.php')) require(PATH_THEME . 'header.php');
    require FILE_THEME;
    if (file_exists(PATH_THEME . 'footer.php')) require(PATH_THEME . 'footer.php');
    
    echo $HTMaster->getRenderedHTML();