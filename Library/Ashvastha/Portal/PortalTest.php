<?php
class Portal_Test
{
    public $testResults = array();
    
    public function __construct() 
    {
        //Load from ID
        $Case = new Portal(2);
        $this->testResults['Load From ID'] = $Case->is_synced();
        unset($Case);
        
        //Load from Null Path
        $Case = new Portal('', 'diagnostics.tdd');
        $this->testResults['Load From Null Path'] = $Case->is_synced();
        unset($Case);
        
        //Load from Path
        $Case = new Portal('/a-b/c+d/', 'diagnostics.tdd');
        $this->testResults['Load From Path'] = $Case->is_synced();
        unset($Case);
        
        //Create New Portal
        $Case = new Portal();
        $this->testResults['Create New'] = !$Case->is_synced();
        
        //Check and Validate Portal
        $Case   ->set_domain('http://www.diagnostics.tdd/')
                ->set_name('This portal is from a unit test.')
                ->set_path('/this/is/a/test/of/setting/path/')
                ->set_robots('index,nofollow')
                ->set_tagline(' - testing portals and such')
                ->set_theme('themeDoesNotCompute')
                ->set_title('This Is A Test Portal');
        $this->testResults['Check and Validate New Portal']['domain'] = ($Case('domain') == 'diagnostics.tdd')? true: false;
        $this->testResults['Check and Validate New Portal']['name'] = ($Case('name') == 'This portal is from a unit test.')? true: false;
        $this->testResults['Check and Validate New Portal']['path'] = ($Case('path') == 'this/is/a/test/of/setting/path')? true: false;
        $this->testResults['Check and Validate New Portal']['robots'] = ($Case('robots') == 'index,nofollow')? true: false;
        $this->testResults['Check and Validate New Portal']['tagline'] = ($Case('tagline') == ' - testing portals and such')? true: false;
        $this->testResults['Check and Validate New Portal']['theme'] = ($Case('theme') == 'themeDoesNotCompute')? true: false;
        $this->testResults['Check and Validate New Portal']['title'] = ($Case('title') == 'This Is A Test Portal')? true: false;
        $this->testResults['Sync and Save Portal to Database'] = $Case->sync();
        
        //Take the portal offline
        $Case->go_offline('closed', 'This test portal is closed to the public.');
        $this->testResults['Take the portal offline'] = ($Case->offline_flag() == 'closed' AND $Case->offline_message() == 'This test portal is closed to the public.')? true: false;
        
        //Sync Portal Change Updates to DB
        $this->testResults['Sync Modified Portal'] = $Case->sync();
        
        //Flush Portal Database Entry
        $this->testResults['Flush Portal'] = $Case->flush();
    }
}