<?php

class Formitive_Entry
{
    protected $dbRow = array();
    protected $isSynced = false;
    protected $pageset = array();
    
    
    public function __construct($locas = null)
    {
        if ($locas) $this->import_database($locas);
    }
    
    
    public function __invoke($fieldname, $value)
    {
        
    }
    
    
    public function export_database()
    {
        if ($this->is_synced()) return true;
        
        global $Database;
        
        $this->dbRow['pageset'] = json_encode($this->pageset);
        if (json_last_error() != JSON_ERROR_NONE)
        {
            $this->debugMessages[] = "sync() was unable to json_encode pageset array.\n";
            $this->dbRow['pageset'] = array();
            
            return false;
        }
        
        if (is_int($this->dbRow['id']) AND $this->dbRow['id'] > 0)
        {
            // Export to existing entry (update)
            
            $updateRows = $this->dbRow;
            unset($updateRows['creationIp']);
            unset($updateRows['creationTime']);
            unset($updateRows['id']);
            
            $id = $this->dbRow['id'];
            
            try
            {
                $result = (boolean)$Database->update('app_formitive_entries', $updateRows, "id = $id");
                if ($result) $this->is_synced = true;
                unset($updateRows);
            }
            catch (Exception $e)
            {
                $this->debugMessages[] = "sync_update() was unable to execute sql query.\n\n$e";
            }
        }
        else
        {
            //Export to new entry
            
            unset($this->dbRow['id']);
            
            if (!isset($this->dbRow['portal']) OR !is_int($this->dbRow['portal']))
            {
                $this->debugMessages[] = 'Cannot sync new entry when portal has not been defined.';
                return false;
            }
                    
            $this->dbRow['creationIp'] = (DEBUG_MODE === TRUE)? 'DEBUG: '. $_SERVER['REMOTE_ADDR'] : $_SERVER['REMOTE_ADDR'];
            $this->dbRow['creationTime'] = time();
            
            try
            {
                $result = $Database->insert('app_formitive_entries', $this->dbRow);
                $this->dbRow['id'] = $Database->lastInsertId();
                if ($result) $this->is_synced = true;
            }
            catch (Exception $e)
            {
                $this->debugMessages[] = "sync_insert() sql query failed.\n\n$e";
                return false;
            }
        }
        
        return $this->is_synced();
    }
    
    
    public function expunge_database()
    {
        global $Database;
        
        $rowsAffected = null;
        
        if (isset($this->dbRow['id']) AND is_int($this->dbRow['id']))
        {    
            $id = intval($this->dbRow['id']);
            try
            {
                $rowsAffected = $Database->delete('app_formitive_entries', "`id` = '$id'");
            }
            catch (Exception $e)
            {
                $this->debugMessages[] = "expunge_database() was unable to delete entry: '$id' from the database.\n\n$e";
            }   
        }
        
        $this->reset();
        
        if ($rowsAffected) return true;
        return false;
    }
    
    
    public function import_database($locas)
    {
        global $Database;
        
        $this->reset();
        
        if (is_numeric($locas))
        {
            try
            {
                $sql = "SELECT * FROM `app_formitive_entries` WHERE `id` = '". intval($locas) ."' LIMIT 1;";
                (Boolean)$result = $Database->fetchRow($sql);
            }
            catch (Exception $e)
            {
                $this->debugMessages[] = "import_database() was unable to execute query '$sql' for entry '$locas'.\n\n$e";
            }
            
            if ($result)
            {
                $this->dbRow = $result;
                $this->isSynced = true;
                
                $this->$pageset = json_decode($this->dbRow['pageset']);
                if (json_last_error() != JSON_ERROR_NONE)
                {
                    //verbose write error
                    return false;
                }
                
                return true;
            }
        }

        return false;
    }
    
    
    public function reset()
    {
        $this->dbRow = array();
        $this->isSynced = false;
        $this->pageset = array();
    }

    
    /**
     * Retrieves a field of specified name. Will not return pageset.
     *
     * @param  string $fieldName
     * @return varies
     */
    public function get($fieldName)
    {
        if ($fieldName == 'pageset') return $this->pageset;
        elseif (isset($this->dbRow[$fieldName])) return $this->dbRow[$fieldName];
        else return null;
    }
    

    /**
     * Adds a page to the entry's pageset. Will not add if it already exists in the pageset.
     *
     * @param  string $pageName The name of the page to be added to the pageset (case sensitive)
     * @param string $insertAfterPage optional
     * 
     * @return object self
     */
    public function pageset_add_page($pagename, $insertAfterPage = null)
    {
        $pagename = (string)$pagename;
        
        if (!$this->pageset_get($pagename))
        {
            $this->pageset[] = $pagename;
        }

        return $this;
    }


    /**
     * Defines the entry's pageset. Will completely replace the entry's current pageset.
     *
     * @param  array $pageset Array of strings containing the pagenames (case sensitive).
     *
     * @return object Self
     */
    public function pageset_set($pages)
    {
        $this->pageset = array();

        if (is_array($pages))
        {
            $this->pageset = $pages;
        }
        elseif (strstr($pages, ','))
        {
            $this->pageset = explode(',', $pages);
        }
        else
        {
            $this->pageset[] = $pages;
        }
        return $this;
    }
    

    /**
     * Returns information on the pageset.
     *      If no index is provided will return an array containing the complete pageset.
     *      If index is provided as an integer will return the associated pageName.
     *      If index is provided as a string will return the page's page number.
     *
     * @param  $index (optional) String or integer.
     *
     * @return various
     */
    public function pageset_get($index = null)
    {
        $t = array_merge(array('Agreement'), $this->pageset, array('Finished'));

        if (is_null($index))
        {
            return $t;
        }
        elseif(is_numeric($index))
        {
            $index = intval($index);
            return (isset($t[$index]))? $t[$index]: false;
        }
        else
        {
            $t = array_map('strtolower', $t);   //Convert all array values to lowercase
            $index = strtolower($index);

            return array_search($index, $t, false);
        }
    }
    

    /**
     * Given the current page determines the next page in the set. Returns null if there are no later pages and false if given a pageName that is not in the pageset
     *
     * @param  various $currentPage The current page - name (string) or index (integer)
     *
     * @return various
     */
    public function pageset_get_next($currentPage)
    {
        if (!is_numeric($currentPage))
        {
            $i = $this->pageset_get($currentPage);
        }
        else
        {
            $i = intval($currentPage);
        }

        $i++;

        if ($i > $this->pageset_count()) return null;
        if (!$this->pageset_contains($i)) return false;
        if (is_numeric($currentPage)) return $i;
        return $this->pageset_get($i);
    }
    

    /**
     * Given the current page determines the previous page in the set.
     *
     * @param  various $currentPage The current page - name (string) or index (integer)
     *
     * @return various
     */
    public function pageset_get_previous($currentPage)
    {
        if (!is_numeric($currentPage))
        {
            $i = $this->pageset_get($currentPage);
        }
        else
        {
            $i = intval($currentPage);
        }

        $i--;

        if ($i < 0) return null;
        if (!$this->pageset_contains($i)) return false;
        if (is_numeric($currentPage)) return $i;
        return $this->pageset_get($i);
    }

    
    public function pageset_reset()
    {
        $this->$pageset = json_decode($this->dbRow['pageset']);
        if (json_last_error() != JSON_ERROR_NONE)
        {
            $this->$pageset = array();
            //verbose write error
            return false;
        }
        
        return true;
    }

    /**
     * Removes a page from the entry's pageset.
     *
     * @param  various $page The page to be removed. Pagename as string or index as integer.
     *
     * @return object Self
     */
    public function pageset_remove_page($page)
    {
        if (!is_numeric($page))
        {
            $i = $this->pageset_get($page);
        }
        else
        {
            $i = intval($page);
        }

        if (isset($this->pageset[$i - 1]))
        {
            unset($this->pageset[$i - 1]);
        }
        unset($i);
        return $this;
    }
    
    
    /**
     * Determines if the entry is in-sync with the database
     *
     * @return Boolean
     */
    public function is_synced()
    {
        return $this->isSynced;
    }

    /**
     * Sets the entry's portal
     *
     * @param integer $portalId
     *
     * @return object Self
     */
    public function set_portal($portalId)
    {
        $this->isSynced = false;
        $this->dbRow['portal'] = $portalId;

        return $this;
    }

    /**
     * Sets the entry's status
     *
     * @param string $status
     *
     * @return object Self
     */
    public function set_status($status)
    {
        $this->isSynced = false;
        $this->dbRow['status'] = strtoupper($status);
        return $this;
    }

    /**
     * Sets the last time the entry was retrieved by ad administrator
     *
     * @param timestamp $timestamp
     *
     * @return object Self
     */
    public function set_lastRetrieved($timestamp)
    {
        $this->isSynced = false;
        $this->dbRow['lastRetrieved'] = $timestamp;

        return $this;
    }


    /**
     * Sets the entry's birthday
     *
     * @param string $birthday
     *
     * @return object Self
     */
    public function set_birthday($birthday)
    {
        $this->isSynced = false;
        $this->dbRow['birthday'] = (string)$birthday;

        return $this;
    }


    /**
     * Sets the entry's email address
     *
     * @param string $email
     *
     * @return object Self
     */
    public function set_email($email)
    {
        $this->isSynced = false;
        $this->dbRow['email'] = (string)$email;

        return $this;
    }


    /**
     * Sets the entry's first name
     *
     * @param string $firstName
     *
     * @return object Self
     */
    public function set_firstName($firstName)
    {
        $this->isSynced = false;
        $this->dbRow['firstName'] = (string)$firstName;

        return $this;
    }


    /**
     * Sets the entry's gender
     *
     * @param string $gender (MALE / FEMALE / UNKNOWN)
     *
     * @return object Self
     */
    public function set_gender($gender)
    {
        $this->isSynced = false;
        $gender = str_replace('.', null, trim(strtoupper($gender)));

        $femaleTitles = array('AUNT', 'F', 'FEMALE', 'GF', 'GIRL', 'GIRLFRIEND', 'MADAM', 'MISS', 'MIZ', 'MIZZ', 'MOM', 'MOTHER', 'MRS', 'MS', 'MSS', 'MZ', 'MZZ', 'SISTER', 'WIFE', 'WOMAN');
        $maleTitles = array('BF', 'BOY', 'BOYFRIEND', 'BROTHER', 'HUSBAND', 'FATHER', 'M', 'MALE', 'MAN', 'MR', 'SIR', 'UNCLE');

        if (in_array($gender, $maleTitles))
        {
            $gender = 'MALE';
        }
        elseif (in_array($gender, $femaleTitles))
        {
            $gender = 'FEMALE';
        }
        else
        {
            $gender = 'UNKNOWN';
        }

        $this->dbRow['gender'] = $gender;
        unset($femaleTitles);
        unset($maleTitles);

        return $this;
    }


    /**
     * Sets the entry's last name
     *
     * @param string $lastName
     *
     * @return object Self
     */
    public function set_lastName($lastName)
    {
        $this->isSynced = false;
        $this->dbRow['lastName'] = (string)$lastName;

        return $this;
    }


    /**
     * Sets the entry's primary phone number (and phone type).
     *
     * @param string $phoneNumber The phone number.
     * @param string $phoneType Sets the phone type (CELL, WORK, HOME, FAX, INTERNET, OTHER, UNKNOWN)
     *
     * @return object Self
     */
    public function set_primaryPhone($phoneNumber, $phoneType)
    {
        $this->isSynced = false;
        $phoneType = trim(strtoupper($phoneType));

        switch ($phoneType)
        {
            case 'CELL':
            case 'WORK':
            case 'HOME':
            case 'FAX':
            case 'OTHER':
            case 'INTERNET':
                //Already valid, do nothing
                break;
            case 'MOBILE':
            case 'CELLULAR':
                $phoneType = 'CELL';
                break;
            default:
                $phoneType = 'UNKNOWN';
        }

        $this->dbRow['primaryPhone'] = $phoneNumber;
        $this->dbRow['primaryPhoneType'] = $phoneType;

        return $this;
    }

    /**
     * Sets the entry's last activity and on what specific page
     *
     * @param string $pageName Name of the page that entry was last updating
     * @param string optional 
     *
     * @return object Self
     */
    public function set_lastActivity($lastPage, $time = null)
    {
        if (!$time) $time = time();
        
        $this->isSynced = false;
        $this->dbRow['lastUpdated'] = $time;
        $this->dbRow['lastUpdatedPage'] = $lastPage;

        return $this;
    }
}