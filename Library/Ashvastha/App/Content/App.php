<?php

namespace Ashvastha\App\Content;

use App\AppBase;

class App extends \Ashvastha\App\AppBase
{    
    public function __appConstruct()
    {
        $this->HTMLet->setHTML($this->cache);
    }
}