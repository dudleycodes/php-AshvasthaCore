<?php

class App_Formitive_Maintenance
{
    
    
    /**
     * A collection of thrown exceptions and notices to aid in diagnosing problems
     * @var array
     */
    protected $debugMessages = array();
    
    
    /**
     * A collection of all tables in the formitive suite
     * @var array
     */
    protected $tables = array('app_formitive_chiroconditions', 'app_formitive_entries', 'app_formitive_general', 'app_formitive_healthhistory', 
                              'app_formitive_insurance', 'app_formitive_systemreview');
    
    
    /**
     * Returns a html string dump of the object
     *
     * @return String
     */
    public function debug_dump()
    {
        
    }
    
    protected function entry_change_id($currentId, $newId)
    {
        global $Database;
        
        if ($this->entry_exists($newId))
        {
            return false;
        }
        else
        {
            try
            {
                return (boolean)$sql;       //todo
            }
            catch (Exception $e)
            {
                $this->debugMessages[] = '';
                return false;
            }
        }
    }
    
    protected function entry_exists($id)
    {
        
    }
    
    protected function id_swap($id1, $id2)
    {
        
    }
    
    protected function entires_drop_pending($id)
    {
        
    }
    

    protected function tables_optimize()
    {
        global $Database;
        
        $success = true;
        
        foreach ($this->tables as $table)
        {
            try
            {
                $sql = "OPTIMIZE TABLE `$table`;";
                $result = Database::execute($sql);
                if (!$result) $success = false;
            }
            catch (Exception $e)
            {
                $this->debugMessages[] = "Failed to execute query $sql";
                $success = false;
            }
        }
        
        return $success;
    }
    
    protected function tables_auto_increment()
    {
        $sql = "SELECT `id` FROM `apps_formitive_index` ORDER BY `id` DESC LIMIT 1;";
        $highid = Database::getValue($sql) + 1;
        $sql = "ALTER TABLE `apps_formitive_index` AUTO_INCREMENT = $highid";
    }
    
    
    public function run_all()
    {
        // DROP ALL PENDING ENTRIES OLDER THAN 1.5 DAYS
            $age = time() - 60*60*36; // 1.5 days
            try
            {
                $sql = "SELECT `id` FROM `apps_formitive_index` WHERE (`status` = 'pending' AND `activity` < '$age' );";
                $rows = Database::getResult($sql);
                unset ($sql);
            }
            catch (Exception $e)
            {
                //TODO - Log the failure
            }

            foreach ($rows as $row)
            {
                $this->entry_delete($row['id']);
            }

        $this->maintenance_reindex();
        $this->maintenance_reorder();
        $this->maintenance_auto_increment();
        $this->maintenance_optimize_tables();
    }
}