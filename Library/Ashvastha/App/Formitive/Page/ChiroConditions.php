<?php

class App_Formitive_Page_ChiroConditions extends App_Formitive_Page
{
    protected $pagename = 'ChiroConditions';
    protected $tablename = 'app_formitive_chiroconditions';
    
    protected function __define_fields()
    {
        $this->View->set_title('Current Conditions - New Patient Intake Form');
        
        $this->fields[] = 'Your Current Conditions';
        
        $this->fields['chart'] = new App_Formitive_Input_ChiroGuy('chart', $this->dbRow, 'Use the diagram to select your problem areas');
        
        $this->fields['description'] = new App_Formitive_Input_Textarea('description', $this->dbRow, 'Describe your current condition', 'The more information you provide the better we can help you...');
            $this->fields['description']->set_required(true);
            
        $this->fields['cause'] = new App_Formitive_Input_Combo('cause', $this->dbRow, 'What caused this condition?');
            $this->fields['cause']->set_required(true)
                                  ->add_option('Auto Related')
                                  ->add_option('Job Related')
                                  ->add_option('Home Injury')
                                  ->add_option('Slip or Fall')
                                  ->add_option('Lifting')
                                  ->add_option('Slept Wrong')
                                  ->add_option('Unknown')
                                  ->add_option('Other');
            
        $this->fields['cause_details'] = new App_Formitive_Input_Textbox('cause_details', $this->dbRow, 'Explain how this condition started', 'The more information you provide the better we can help you...');
            
        $this->fields['timespan'] = new App_Formitive_Input_Textbox('timespan', $this->dbRow, 'How long have you had this condition?', 'For about X months.');
            $this->fields['timespan']->set_required(true);
            
        $this->fields['other_conditions'] = new App_Formitive_Input_Textarea('other_conditions', $this->dbRow, 'Do you SUFFER with ANY OTHER conditions than which you are now consulting us?', 'Any other conditions could very well be related.');
    }
}

?>