<?php

class App_Formitive_Page_Agreement extends App_Formitive_Page
{
    protected $pagename = 'Agreement';
    protected $tablename = 'app_formitive_blackhole';
    
    public function View()
    {
        $this->View->set_title('Agreement - New Patient Intake Form');
        
        $formAction = '/intake/'. $this->Entry->pageset_next($this->pagename);
        $formName= 'SHAF::'. substr(md5(rand()), 12, 36);
        
        $this->View->absorb('<form name="'. $formName .'" action="'. $formAction .'" method="POST" class="uniForm">');
        $this->View->absorb('<fieldset class="ghost"><input type="hidden" name="SHAF-PAGENAME" value="'. $this->pagename .'">');
        $this->View->absorb('<fieldset class="inlineLabels"><h2>Interactive New Patient Intake Form</h2>');
        
        $c = '<p>Welcome to our interactive intake form for new patients. Please complete this form in its entirety.  The process will take a few moments. </p>

        <p>Once you have completed this form it will be available at our offices for your visit.</p>';
        
        $this->View->absorb($c);
        
        $this->View->absorb('</fieldset><div class="buttonHolder"><p align="center"><button name="button_submit" type="submit" class="primaryAction">Continue &gt;&gt;</button></p></div>');
        $this->View->absorb('</form>');
        
        return $this->View;
    }
}