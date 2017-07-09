<?php

class App_Formitive_Page_General extends App_Formitive_Page
{
    protected $pagename = 'General';
    protected $tablename = 'app_formitive_general';
    
    protected function __define_fields()
    {
        $this->View->set_title('General Information - New Patient Intake Form');
        
        $this->fields[] = 'Personal Information';
        $this->fields['gen_title'] = new App_Formitive_Input_Combo('gen_title', $this->dbRow, 'Title');
        $this->fields['gen_title']->add_option('Mr.')
                                  ->add_option('Mrs.')
                                  ->add_option('Ms.')
                                  ->set_hint('This will be used to determine your gender.')
                                  ->set_required(true);
        
        $this->fields['gen_first'] = new App_Formitive_Input_Textbox('gen_first', $this->dbRow, 'First Name', 'First Name');
            $this->fields['gen_first']->set_required(true)
                                      ->set_validator('alpha');
            
        $this->fields['gen_middle'] = new App_Formitive_Input_Textbox('gen_middle', $this->dbRow, 'Middle Name', 'Middle Name');
            $this->fields['gen_middle']->set_validator('alpha');
        
        $this->fields['gen_last'] = new App_Formitive_Input_Textbox('gen_last', $this->dbRow, 'Last Name', 'Last Name');
            $this->fields['gen_last']->set_required(true)
                                     ->set_validator('alpha');
        
        $this->fields['gen_birthday'] = new App_Formitive_Input_Textbox('gen_birthday', $this->dbRow, 'Birthday', 'mm/dd/yyyy');
            $this->fields['gen_birthday']->set_required(true)
                                         ->set_validator('date');
        
        $this->fields['gen_marital'] = new App_Formitive_Input_Combo('gen_marital', $this->dbRow, 'Marital Status');
            $this->fields['gen_marital']->set_required(true)
                                        ->add_option('Single')
                                        ->add_option('Married')
                                        ->add_option('Seperated');
        
        $this->fields['gen_address'] = new App_Formitive_Input_Textbox('gen_address', $this->dbRow, 'Address', '123 Your Street');
            $this->fields['gen_address']->set_required(true);
        
        $this->fields['gen_city'] = new App_Formitive_Input_Textbox('gen_city', $this->dbRow, 'City', 'City');
            $this->fields['gen_city']->set_required(true);
        
        $this->fields['gen_state'] = new App_Formitive_Input_Textbox('gen_state', $this->dbRow, 'State', 'State');
            $this->fields['gen_state']->set_required(true)
                                      ->set_length_minimum(2);
        
        $this->fields['gen_zip'] = new App_Formitive_Input_Textbox('gen_zip', $this->dbRow, 'Zipcode', '');
            $this->fields['gen_zip']->set_required(true)
                                    ->set_validator('integer')
                                    ->set_length_minimum(5);
            
        $this->fields['gen_phone1'] = new App_Formitive_Input_Phone('gen_phone1', $this->dbRow, 'Primary Phone Number', '555-555-7890');
            $this->fields['gen_phone1']->set_required(true);
        
        $this->fields['gen_phone2'] = new App_Formitive_Input_Phone('gen_phone2', $this->dbRow, 'Alternate Phone Number', '555-555-7890');  
        
        $this->fields['gen_email'] = new App_Formitive_Input_Textbox('gen_email', $this->dbRow, 'Email Address', 'name@example.com');
            $this->fields['gen_email']->set_required(true)
                                      ->set_validator('email');
        
        $this->fields['gen_spouse'] = new App_Formitive_Input_Textbox('gen_spouse', $this->dbRow, 'Spouse\'s Name');
            $this->fields['gen_spouse']->set_validator('alpha');
        
        $this->fields[] = 'Emergency Contact';
        $this->fields['kin_relationship'] = new App_Formitive_Input_Combo('kin_relationship', $this->dbRow, 'Relationship', null);
            $this->fields['kin_relationship']->set_required(true)
                                             ->add_option('Spouse')
                                             ->add_option('Relative')
                                             ->add_option('Friend')
                                             ->add_option('Work');
        
        $this->fields['kin_first'] = new App_Formitive_Input_Textbox('kin_first', $this->dbRow, 'First Name', 'First Name');
            $this->fields['kin_first']->set_required(true)
                                      ->set_validator('alpha');
        
        $this->fields['kin_middle'] = new App_Formitive_Input_Textbox('kin_middle', $this->dbRow, 'Middle Name', 'Middle Name');
            $this->fields['kin_middle']->set_validator('alpha');
        
        $this->fields['kin_last'] = new App_Formitive_Input_Textbox('kin_last', $this->dbRow, 'Last Name', 'Last Name');
            $this->fields['kin_last']->set_required(true)
                                     ->set_validator('alpha');
        
        $this->fields['kin_phone1'] = new App_Formitive_Input_Phone('kin_phone1', $this->dbRow, 'Primary Phone Number', '555-555-7890');
            $this->fields['kin_phone1']->set_required(true);            
        
        $this->fields['kin_phone2'] = new App_Formitive_Input_Phone('kin_phone2', $this->dbRow, 'Alternate Phone Number', '555-555-7890');
        
        $this->fields[] = 'Employment Information';
        $this->fields['employer_name'] = new App_Formitive_Input_Textbox('employer_name', $this->dbRow, 'Business Name', 'ABC Corp');
            $this->fields['employer_name']->set_length_minimum(2);
        
        $this->fields['employer_phone'] = new App_Formitive_Input_Phone('employer_phone', $this->dbRow, 'Primary Phone Number', '555-555-7890');
        
        $this->fields['employer_fax'] = new App_Formitive_Input_Phone('employer_fax', $this->dbRow, 'Fax Number', '555-555-7890');
            $this->fields['employer_fax']->force_type('fax');
    }
    
    public function sync()
    {
        $resultPage = parent::sync();
        
        $gender = (trim(strtolower($this->fields['gen_title']->get('value'))) == 'mr.' )? 'M': 'F';
        $this->Entry->set_gender($gender);    unset($gender);
        $this->Entry->set_firstName($this->fields['gen_first']->get('value'));
        $this->Entry->set_lastName($this->fields['gen_last']->get('value'));
        $this->Entry->set_status('pending');
        //$this->Entry->set_email($email);
        $this->Entry->set_birthday($this->fields['gen_birthday']->get('value'));
        $resultEntry = $this->Entry->sync();
        
        return ($resultPage AND $resultEntry)? TRUE: FALSE;
    }
}