<?php
namespace Ashvastha\Module;

use \Ashvastha\HTMLet;

class ModuleBase
{
    protected $arguments = array();
    protected $cache = null, $settings = array();
    protected $HTMLet;
    

    public function  __construct($arguments = array(), $cache = null, $settings = array())
    {
        $this->arguments = $arguments;
        $this->cache = $cache;
        $this->settings = $settings;

        $this->HTMLet = new \Ashvastha\HTMLet\HTMLet();

        $this->__appConstruct();
    }


    /**
     * Placehold method to be overridden by child that will be called by the parent's __construct(). Cannot have any parameters.
     **/
    public function __moduleConstruct()
    {
        /*!!! NO CODE HERE !!!*/
    }
    
    
    public function getHTMLet()
    {
        return $this->HTMLet;
    }
}