<?php
namespace Ashvastha\PathWalker;

use \Countable;
use \Zend\Uri;


class PathWalker implements Countable, Iterator
{
    protected $directories = array();
    protected $directoriesOffset = 0;
    protected $directorySeparator = '/';

    
    public function __construct($pathString)
    {
        $isSession = false;
        $this->iteratorIndex = 0;
        $this->offset = 0;

        if ($pathString == null) return null;

        if (strtolower($pathString) == 'session')
        {
            $isSession = true;
            $pathString = $_SERVER['REQUEST_URI'];
        }
        elseif (DIRECTORY_SEPARATOR != '/' AND (bool)strpos($pathString, DIRECTORY_SEPARATOR))
        {
            $this->directorySeparator = DIRECTORY_SEPARATOR;
        }

        $ZendUri = \Zend\Uri\UriFactory::factory($pathString);
        $pathString = $ZendUri->getPath();
        $pathString = $this->sanitize_pathString($pathString);
        unset($ZendUri);
        
        //= Remove script path from uri path if navigating the session.
        if ($isSession)
        {
            $executionPath = $this->sanitize_pathString(dirname($_SERVER['SCRIPT_NAME']));
            
            if (stripos($pathString, $executionPath) == 0)
            {
                $pathString = $this->sanitize_pathString(substr($pathString, strlen($executionPath)));
            }
            else
            {
                trigger_error('An instance of ' . __CLASS__ ." was created using parameter \$uri = $pathString. Was "
                            . "unable to remove the execution path ($executionPath) from "
                            . 'the request path (' . $pathString . ').', E_USER_ERROR);
            }
        }
        
        $this->directories = explode($this->directorySeparator, $pathString);
        
        unset($pathString);
    }


    /**
     * Returns the number of directories
     *
     * @return Integer
     */
    public function count()
    {
        return count(array_slice($this->directories, $this->directoriesOffset));
    }
    
    
    /*
     * Returns the current path
     *
     * @return string
     */
    public function get_currentPath()
    {
        $t = implode($this->directorySeparator, $this->get_currentDirectories());

        return $t;
    }


    /*
     * Returns the current path
     *
     * @return string
     */
    public function get_currentDirectories()
    {
        return array_slice($this->directories, $this->directoriesOffset);
    }


    /**
     * Returns a specified argument
     *
     * @param  integer $depth of argument requested
     * @return string String The requested argument. Returns null if argument does not exist.
     */
    public function get_directory($depth)
    {
        $depth += $this->directoriesOffset;

        if (isset($this->directories[$depth]))
        {
            return $this->directories[$depth];
        }

        return null;
    }


    /**
     * Returns the full, original path that was used to create this object
     *
     * @return string
     */
    public function get_originalPath()
    {
        return implode($this->directorySeparator, $this->directories);
    }
    
    
    /**
     * Processes a path string and removes all matching arguments
     *
     * @param  string $pathToRemove 'path' string
     * @return boolean
     */
    public function remove_leadingPath($pathToRemove)
    {
        $this->rewind();

        $currentPath = $this->get_currentPath();
        $pathToRemove = $this->sanitize_pathString($pathToRemove);
        
        if (strpos($currentPath, $pathToRemove) == 0)
        {
            $this->directoriesOffset += substr_count($pathToRemove, $this->directorySeparator);

            return true;
        }
        
        return false;
    }


    protected function sanitize_pathString($pathString)
    {
        $pathString = filter_var($pathString, FILTER_SANITIZE_URL);
        if (strstr($pathString, '//'))          $pathString = str_replace('//', '/', $pathString);                  //Remove any duplicate separators
        if (substr($pathString, 0, 1) == '/')   $pathString = substr($pathString, 1);                               //Remove any leading '/'
        if (substr($pathString, -1, 1) == '/')  $pathString = substr($pathString, 0, -1);                           //Remove any trailing '/'

        return $pathString;
    }
}