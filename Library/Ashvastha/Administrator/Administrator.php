<?php

namespace Ashvastha\Administrator;

use \Zend\Session;

class Administrator
{
    protected $dataContainer = null;
    protected $locus = 0;
    protected $sessionActive = false;


    public function __construct($locus = 0)
    {
        $this->locus = $locus;

        if (strtolower($locus) == 'session')
        {
            $this->sessionActive = true;
            $this->dataContainer = new \Zend\Session\Container('administrator_session');
            if (!isset($this->dataContainer->synced)) $this->dataContainer->synced = false;
            if (!isset($this->dataContainer->dbRow)) $this->dataContainer->dbRow = array();
        }
        else
        {
            $this->dataContainer = new \stdClass();
            $this->dataContainer = array();
            $this->dataContainer->synced = false;

            if (is_numeric($locus) AND $locus > 0)
            {
                $this->fetch(intval($locus));
            }
            else
            {
                trigger_error(__CLASS__ . '->' . __METHOD__ . "() received an invalid \$locus of $locus", E_USER_WARNING);
            }
        }
    }


    protected function fetch($locus, $password = null)
    {
        global $Database;

        if (empty($password) AND is_numeric($locus))
        {
            $locus = intval($locus);
            $result = $Database->query("SELECT * FROM `administrators` WHERE `id` = '?' LIMIT 1;", array($locus));
            $result = $result->ToArray();

            if (!empty($result))
            {
                $this->dataContainer->dbRow = $result;
                $this->dataContainer->synced = true;

                return true;
            }

            return false;
        }
        elseif (!empty($password) and strstr($locus, '@'))
        {
            $result = $Database->query('SELECT * FROM `administrators` WHERE `email` = ? LIMIT 1;', array($locus));
            $result = $result->toArray();

            if (count($result) == 1)
            {
                $result = array_shift($result);

                $hash = $this->hashPassword($password, $result['passwordTimestamp'], $result['passwordSalt']);

                if (!empty($hash) AND $hash == $result['passwordHash'])
                {
                    $this->dataContainer->dbRow = $result;
                    $this->dataContainer->synced = true;

                    return true;
                }
                else
                {
                    $this->dataContainer->dbRow = array();
                    $this->dataContainer->synced = false;
                }
            }

            return false;
        }
        else
        {
            trigger_error(__CLASS__ . '->' . __METHOD__ . "() received an invalid \$locus of $locus", E_USER_WARNING);

            return false;
        }
    }


    public function hashPassword($password, $timestamp, $salt)
    {
        if ($timestamp == 47)
        {
            //plaintext :(

            return $password;
        }
        else
        {
            trigger_error(__CLASS__ . '->' . __METHOD__ . "() received an out of range timestamp of $timestamp", E_USER_WARNING);
            return null;
        }
    }


    public function isSynced()
    {
        return (Boolean)$this->dataContainer->synced;
    }


    public function reset()
    {
        $this->dataContainer->synced = false;
        $this->dataContainer->dbRow = array();
    }


    public function validate($email, $password)
    {
        global $Database;

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = trim(strtolower($email));
        $result = $this->fetch($email, $password);

        return $result;
    }
}
