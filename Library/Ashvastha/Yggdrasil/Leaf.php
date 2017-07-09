<?php
namespace Ashvastha\Yggdrasil;


use Ashvastha\PathWalker\PathWalker;


class Leaf
{
    protected $dbRow = array();
    protected $isSynced = false;


    public function __construct($locas = null, $portalId = null)
    {
        $this->isSynced = $this->fetch($locas, $portalId);
    }


    /**
     * Loads Yggdrasil entry from database
     *
     * @param  integer|string|PathWalker $locas
     * @param  integer $portal
     * @return Boolean
     */
    private function fetch($locas, $portalId = null)
    {
        global $Database;

        if (empty($portalId))
        {
            if (is_numeric($locas))
            {
                $result = $Database->query("SELECT * FROM `yggdrasil` WHERE `id` = '?' LIMIT 1;", array(intval($locas)));
                $result = $result->ToArray();

                if (count($result))
                {
                    $this->dbRow = $result;
                    return true;
                }

                return false;
            }
            else
            {
                throw new \InvalidArgumentException(__METHOD__ . "() \$locas must be an Integer when no \$portal is provided.  Input was: $locas");

                return false;
            }
        }
        else
        {
            $Path = (get_class($locas) == 'Ashvastha\PathWalker\PathWalker' )? $locas: new \Ashvastha\PathWalker\PathWalker($locas);
            $portalId = intval($portalId);
            $settingMaxDepth = 3;

            $paths = array_slice($Path->getPathTrace(), 1, $settingMaxDepth++);
            $where = "`path` IS NULL";
            foreach ($paths as $t)
            {
                $where .= ' OR `path` = ?';
            }

            $sql = "SELECT * FROM `yggdrasil` WHERE `portal` = ? AND ($where) ORDER BY CHAR_LENGTH(path) ASC LIMIT $settingMaxDepth;";
            $result = $Database->query($sql, array_merge(array($portalId), $paths));
            $result = $result->ToArray();

            if (count($result) < 1) return false;

            $this->dbRow = array_pop($result);
            $Path->removePath($this->dbRow['path']);

            return true;
        }

        return false;
    }


    /**
     * Get the value of appCache
     *
     * @return string
     */
    public function get_appCache()
    {
        return (isset($this->dbRow['appCache']))? (String)$this->dbRow['appCache']: null;
    }


    /**
     * Get the value of appCacheExpires
     *
     * @return string (timestamp)
     */
    public function get_appCacheExpires()
    {
        return (isset($this->dbRow['appCacheExpires']))? (String)$this->dbRow['appCacheExpires']: null;
    }


    /**
     * Get the value of appName
     *
     * @return String
     */
    public function get_appName()
    {
        return (isset($this->dbRow['appName']))? (String)$this->dbRow['appName']: null;
    }


    /**
     * Get the values contained in appSettings (variableName => value)
     *
     * @return array
     */
    public function get_appSettings()
    {
        if (isset($this->dbRow['appSettings']) and !is_null($this->dbRow['appSettings']))
        {
            $r = json_decode($this->dbRow['appSettings']);

            if (json_last_error() == JSON_ERROR_NONE)
            {
                return $r;
            }

            trigger_error('An instance of ' . __CLASS__ . ' attempted to json_decode appSettings but failed.  Thrown '
                        . "error was " . $this->getJsonErrorDescription(json_last_error()), E_USER_WARNING);
        }

        return array();
    }


    /**
     * Get the value of id
     *
     * @return Integer, Null
     */
    public function get_id()
    {
        return (isset($this->dbRow['id']))? (String)$this->dbRow['id']: null;
    }


    /**
     * Get the description of a json error
     *
     * @param Integer Native json error constant
     * @return String
     */
    protected function get_jsonErrorDescription($jsonError)
    {
        switch ($jsonError)
        {
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                return 'Unknown error';
        }
    }


    /**
     * Get the value of metaDescription
     *
     * @return String
     */
    public function get_metaDescription()
    {
        return (isset($this->dbRow['metaDescription']))? (String)$this->dbRow['metaDescription']: null;
    }


    /**
     * Get the value of metaDescription
     *
     * @return String
     */
    public function get_metaRobots()
    {
        return (isset($this->dbRow['metaRobots']))? (String)$this->dbRow['metaRobots']: null;
    }


    /**
     * Get the value of pageData
     *
     * @return String
     */
    public function get_pageData()
    {
        if (isset($this->dbRow['pageData']) AND !empty($this->dbRow['pageData']))
        {
            $r = json_decode($this->dbRow['pageData']);

            if (json_last_error() === JSON_ERROR_NONE)
            {
                return $r;
            }
            else
            {
                trigger_error('An instance of ' . __CLASS__ . ' attempted to json_decode pageData but failed.  Thrown '
                            . "error was " . $this->getJsonErrorDescription(json_last_error()), E_USER_ERROR);
            }
        }

        return array();
    }


    /**
     * Get the name of the entry's template
     *
     * @return String
     */
    public function get_template()
    {
        return (isset($this->dbRow['template']))? (String)$this->dbRow['template']: null;
    }


    /**
     * Get the name of the entry's theme
     *
     * @return String
     */
    public function get_theme()
    {
        return (isset($this->dbRow['theme']))? (String)$this->dbRow['theme']: null;
    }


    /**
     * Get the title of the entry
     *
     * @return String
     */
    public function get_title()
    {
        return (isset($this->dbRow['title']))? (String)$this->dbRow['title']: null;
    }


    /**
     * Determine if the entry is synced with the database
     *
     * @return Boolean
     */
    public function is_synced()
    {
        return (Bool)$this->isSynced;
    }


}