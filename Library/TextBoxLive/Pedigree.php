<?php
/*
 * Input_Checkboxes
 * 
 * Creates a diagram of the human body for user to select and specify their
 * chiropractic concerns and issues.
 * 
 * Required database field length: 
 * 
 */    

class TextBoxLive_Pedigree extends TextBoxLive
{
    
    protected function __inputconstruct()
    {
        
    }
    
    public function snippet_div()
    {
        
        $r = "\n";
        $r .= '<div class="container_12">';
        $r .= '     <div class="grid_3">'. $this->label .'</div>';
        
        $r .= '     <div class="grid_2">Combo</div>';
        $r .= '     <div class="grid_2">Developed</div>';
        $r .= '     <div class="grid_2">Disease</div>';
        $r .= '     <div class="grid_3">'. $this->label .'</div>';
        
        $r .= '</div><hr>'; // end of container_12
        
        return $r;
    }
    
    
}