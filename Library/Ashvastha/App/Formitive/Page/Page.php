<?php

Class App_Formitive_Page
{
    //= Variables that *must* be set in child instance
    protected $pagename = null;
    protected $tablename = null;
    
    //= Variables for use by the class
    protected $dbRow = array();
    protected $debugMessages = array();
    protected $Entry = null;
    protected $fields = array();
    protected $View;
    
    public function __construct($Entry)
    {
        $this->Entry = $Entry; 
        unset($Entry);
        $this->View = new View();
        
        $this->fetch();
        $this->__define_fields();
        
        if (empty($this->pagename)) $this->debugMessages[] = '$pagename was not properly defiend and filled in child instance.';
        if (empty($this->tablename)) $this->debugMessages[] = '$tablename was not properly defiend and filled in child instance.';
        if (!($this->Entry instanceof App_Formitive_Entry)) $this->debugMessages[] = 'Provided $entry was not an instant of App_Formitive_Entry.';
    }
    
    protected function __define_fields()
    {
        $this->debugMessages[] = 'Child instance did not override method __define_fields()!';
    }
    
    
    /**
     * Retrieves all thrown error messages. If there are none it'll return a null value. If messages exist will return an array.
     *
     * @return Null or Array
     */
    public function debug_messages()
    {
        $t = (empty($this->debugMessages))? null: $this->debugMessages;
        return $t;
    }
    
    
    /**
     * Fetches and loads the page's database row for the entry
     *
     * @return Null or Array
     */
    protected function fetch()
    {
        global $Database;
        
        if ($this->Entry->get('id') == null) return false;
        
        try
        {
            $Database->setFetchMode(Zend1_Db::FETCH_ASSOC);
            $this->dbRow = $Database->fetchRow('SELECT * FROM '. $this->tablename .' WHERE `entry` = '. $this->Entry->get('id') .';');
            unset($this->dbRow['id']);
            unset($this->dbRow['entry']);
        }
        catch (Exception $e)
        {
            $this->debugMessages[] = "parent::fetch() was unable to retrieve dbRow.\n\n$e";
            return false;
        }
        
        return true;
    }
    
    /**
     * Returns a count of fields that have a value
     *
     * @return integer
     */
    protected function fields_count()
    {        
        $fieldCount = 0;
        
        foreach($this->fields as $Input)
        {
            if ($Input instanceof App_Formitive_Input)
            {
                $fieldCount++;
            }
        }
        
        return $fieldCount;
    }
    
    /**
     * Retrieves an array of all appropriate field names=>values.
     *
     * @return array
     */
    protected function fields_fetch()
    {
        $r = array();
        
        foreach($this->fields as $Input)
        {
            if ($Input instanceof App_Formitive_Input)
            {
                $r[$Input->get('fieldname')] = $Input->get('value');
            }
        }
        
        $r['entry'] = $this->Entry->get('id');
        
        return $r;
    }

    public function sync()
    {        
        if (empty($this->dbRow))
        {
            return $this->sync_insert();
        }
        else
        {
            return $this->sync_update();
        }
    }
    
    protected function sync_insert()
    {
        global $Database;
        
        $t = $this->fields_fetch();
        
        try
        {
            $result = $Database->insert($this->tablename, $t);
            $this->dbRow = $t;
            unset($t);
        }
        catch (Exception $e)
        {
            $this->debugMessages[] = "parent::sync_insert() was unable to execute update query.\n\n$e";
            return false;
        }
        
        return (Boolean)$result;
    }
    
    protected function sync_update()
    {
        global $Database;
        
        $t = $this->fields_fetch();
        
        try
        {
            $result = $Database->update($this->tablename, $t, "`entry` = ". $this->Entry->get('id'));
            $this->dbRow = $t;
            unset($t);
        }
        catch (Exception $e)
        {
            $this->debugMessages[] = "parent::sync_update() was unable to execute update query.\n\n$e";
            return false;
        }
        
        return (Boolean)$result;
    }
    
    public function view()
    {
        $formAction = '/intake/'. $this->Entry->pageset_next($this->pagename);
        $formName= 'SHAF::'. substr(md5(rand()), 12, 36);
        
        $this->View->absorb('<form name="'. $formName .'" action="'. $formAction .'" method="POST" class="uniForm">');
        $this->View->absorb('<fieldset class="ghost"><input type="hidden" name="SHAF-PAGENAME" value="'. $this->pagename .'">');
        
        foreach($this->fields as $fieldname => $Input)
        {
            if (!is_null($Input) AND is_string($Input))
            {
                $this->View->absorb('</fieldset><p><br></p><fieldset class="inlineLabels">'. "<h3>$Input</h3>");
            }
            elseif (get_class($Input) AND is_subclass_of($Input, 'App_Formitive_Input'))   //todo add third parameter of 'false' when php is version 5.3.9
            {
                $this->View->absorb($Input->snippet_div() ."\n");
            }
            else
            {
                $this->debugMessages[] = 'Parent view() has parsed a defined input ($this->fields) of an unknown type. '
                                        ."<key = $fieldname>";
            }
        }
        
        $this->View->absorb('</fieldset><div class="buttonHolder"><p align="center"><button name="button_submit" type="submit" class="primaryAction">Continue &gt;&gt;</button></p></div>');
        $this->View->absorb('</form>');
        
        return $this->View;
    }
}