<?php
namespace Ashvastha\Portal;

use Ashvastha\PathWalker;

class Portal
{
    protected $dbParents = array();
    protected $dbRow = array();
    protected $synced = false;
    
    
    public function __construct($locas = null, $domain = null)
    {
        if (is_numeric($locas) and $domain === null)
        {
            $result = $this->fetch_byId($locas);
        }
        else
        {
            $result = $this->fetch_byPath($locas, $domain);
        }

        $this->synced = (bool)$result;
    }


    protected function fetch_byId($id)
    {
        global $Database;

        $result = array();

        $result = $Database->query("SELECT * FROM `portals` WHERE `id` = '?' LIMIT 1;", array($id));
        $result = $result->ToArray();

        if (!empty($result))
        {
            $this->dbRow = $result;

            return true;
        }

        return false;
    }


    protected function fetch_byPath($path, $domain)
    {
        global $Database;

        $settingMaxDepth = 4;

        //Process $path
        if (get_class($path) == '\Ashvastha\PathWalker\PathWalker')
        {
            $Path = $path;
        }
        else
        {
            $path = strval($path);
            $Path = new \Ashvastha\PathWalker\PathWalker($path);
        }
        unset($path);

        //Process $domain
        if ($domain == null)
        {
            $domain  = preg_replace("/[^a-z0-9].:-+@/", "", strtolower($_SERVER['SERVER_NAME']));
        }

        if (!strstr($domain, 'www.'))   $domain = "www.$domain";
        $domain = strtolower(filter_var(trim($domain), FILTER_SANITIZE_URL));

        //Generate SQL query
        $directories = array_slice($Path->get_currentDirectories(), $settingMaxDepth);

        $whereClause = "`path` IS NULL";

        foreach ($directories as $t)
        {
            $whereClause .= ' OR `path` = ?';
        }

        $sql = "SELECT * FROM `portals` WHERE `domain` = ? AND ($whereClause) ORDER BY CHAR_LENGTH(path) ASC LIMIT $settingMaxDepth;";
        $result = $Database->query($sql, array_merge(array($domain), $directories));
        $result = $result->ToArray();
        unset($sql);

        if (count($result) > 0)
        {
            $this->dbRow = array_pop($result);
            if (count($result) > 0) $this->dbParents = $result;

            return true;
        }

        return false;
    }


    public function get_id()
    {
        return (isset($this->dbRow['id']))? $this->dbRow['id']: null;
    }


    public function get_name()
    {
        return (isset($this->dbRow['name']))? $this->dbRow['name']: null;
    }


    public function get_parentId()
    {
        return (isset($this->dbRow['parentId']))? $this->dbRow['parentId']: null;
    }


    public function get_path()
    {
        return (isset($this->dbRow['path']))? $this->dbRow['path']: null;
    }
    
    
    public function get_theme()
    {
        if (isset($this->dbRow['theme']))
        {
            return $this->dbRow['theme'];
        }

        return null;
    }


    public function is_enabled()
    {
        if ($this->is_synced())
        {
            return (bool)$this->dbRow['isEnabled'];
        }
        else
        {
            return null;
        }
    }


    public function is_synced()
    {
        return (bool)$this->synced;
    }
}