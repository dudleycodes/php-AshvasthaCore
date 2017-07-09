<?php

class Formitive_Test
{
    public $testResults = array();
    
    public function __construct()
    {
        $Unit = new App_Formitive_Entry(2);
        $this->testResults['Entry']['Load From Id'] = (Boolean)$Unit->is_synced();
        unset($Unit);
        
        $Unit = new App_Formitive_Entry;
        $this->testResults['Entry']['Create Empty Entry'] = is_object($Unit);
        
        //= Alter variables
        $Unit->set_birthday('1/1/1970')
             ->set_email('blackhole@example.com')
             ->set_firstName('jane')
             ->set_lastName('doe')
             ->set_gender('Ms.')
             ->set_primaryPhone('1-800-456-4875', 'OTHEr')
             ->set_portal(2)
             ->set_status('aRchIVed');

        $this->testResults['Entry']['Alter Birthday'] = ($Unit->get('birthday') == '1/11/2008') ? TRUE: FALSE;
        $this->testResults['Entry']['Alter Email'] = ($Unit->get('email') == 'blackhole@example.netm') ? TRUE: FALSE;
        $this->testResults['Entry']['Alter First Name'] = ($Unit->get('firstName') == 'john') ? TRUE: FALSE;
        $this->testResults['Entry']['Alter Last Name'] = ($Unit->get('lastName') == 'doe') ? TRUE: FALSE;
        $this->testResults['Entry']['Alter Gender'] = ($Unit->get('gender') == 'MALE') ? TRUE: FALSE;
        $this->testResults['Entry']['Alter Phone Number'] = ($Unit->get('primaryPhone') == '1-800-456-4875' AND $Unit->get('primaryPhoneType') == 'OTHER') ? TRUE: FALSE;
        $this->testResults['Entry']['Alter Status'] = ($Unit->get('status') == 'ARCHIVED') ? TRUE: FALSE;
        $this->testResults['Entry']['Alter Portal'] = ($Unit->get('portal') == 2) ? TRUE: FALSE;
        
        $t = array('IDoNotExist', 'INoExistEither', 'IAmMadeUp', 'TuxedoFarm');
        $Unit->pageset_define($t);
        $this->testResults['Entry']['Pageset Define'] = ($Unit->pageset_contains('IDoNotExist') AND $Unit->pageset_contains('IAmMadeUp') AND $Unit->pageset_contains('INoExistEither'))? true: false;
        unset($t);
        
        $Unit->pageset_add('IExist');
        $this->testResults['Entry']['Pageset Add'] = ($Unit->pageset_contains('IExist'))? true: false;
                
        $Unit->pageset_remove('IExist');
        $this->testResults['Entry']['Pageset Remove'] = (!$Unit->pageset_contains('IExist'))? true: false;
        $this->testResults['Entry']['pageset_get()'] = ($Unit->pageset_get('IDoNotExist') == 1 AND $Unit->pageset_get(1) == 'IDoNotExist')? true: false;
        unset($Unit);
        
        //=============================================================
        //= Create and insert new entry
        //=============================================================
        $Unit = new App_Formitive_Entry();
        $Unit->set_birthday('1/2/1970')
             ->set_email('blackhole@example.com')
             ->set_firstName('Test-Of-Insert-Query')
             ->set_lastName('INSERTTEST')
             ->set_gender('Mr.')
             ->set_primaryPhone('1-800-456-4875', 'OTHEr')
             ->set_portal(2)
             ->set_status('aRchIVed');
        $Unit->pageset_add('They')
             ->pageset_add('Saved')
             ->pageset_add('Shark\'s')
             ->pageset_add('Brain!');
        
        $syncResult = $Unit->sync();
        $id = $Unit->get('id');
        
        unset($Unit);
        
        $Unit = new App_Formitive_Entry($id);
        $checkResult = ($Unit->get('lastName') == 'INSERTTEST') ? TRUE :FALSE ;
        $this->testResults['Entry']['Sync new entry (insert)'] = ($checkResult AND $syncResult)? TRUE: FALSE;
        
        unset($Unit);
        unset($checkResult);
        unset($syncResult);
        
        //=============================================================
        //= Fetch, Alter, Sync entry
        //=============================================================
        // Uses variable $id from test "Create and insert new entry"
        
        $Unit = new App_Formitive_Entry($id);
        
        $Unit->set_birthday('4/19/1980')
             ->set_email('blackhole@example.com')
             ->set_firstName('Test')
             ->set_lastName('Alter')
             ->set_gender('Mrs.')
             ->set_primaryPhone('1-866-123-5678', 'CeLl')
             ->set_portal(3)
             ->set_status('pENDiNg');
        $syncResult = $Unit->sync();
        
        unset($Unit);
        
        $Unit = new App_Formitive_Entry($id);
        
        $valueMatched = array();
        $valueMatched[] = ($Unit->get('birthday') == '4/19/1980')? true :false;
        $valueMatched[] = ($Unit->get('email') == 'blackhole@example.com')? true :false;
        $valueMatched[] = ($Unit->get('firstName') == 'Test')? true :false;
        $valueMatched[] = ($Unit->get('lastName') == 'Alter')? true :false;
        $valueMatched[] = ($Unit->get('gender') == 'FEMALE')? true :false;
        $valueMatched[] = ($Unit->get('primaryPhone') == '1-866-123-5678')? true :false;
        $valueMatched[] = ($Unit->get('primaryPhoneType') == 'CELL')? true :false;
        $valueMatched[] = ($Unit->get('portal') == 3)? true :false;
        $valueMatched[] = ($Unit->get('status') == 'PENDING')? true :false;
        
        $allGood = true;
        foreach ($valueMatched as $value)
        {
            if ($value == false) $allGood = false;
        }
        
        $this->testResults['Entry']['Alter an already synced entry'] = ($syncResult AND $allGood)? true: false;
        
        unset($allGood);
        unset($syncResult);
        unset($valueMatched);
        unset($Unit);
        
        //=============================================================
        //= Destroy a synced entry
        //=============================================================
        // Uses variable $id from test "Create and insert new entry"
        
        $Unit = new App_Formitive_Entry($id);
        $this->testResults['Entry']['Destroy (remove entry from database)'] = $Unit->destroy(true);
        
    }
}