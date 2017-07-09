<?php

class TextBoxLive_Textarea extends TextBoxLive
{
    protected $styleSize = 'medium';
    
    protected $textareaCols = 3;
    protected $textareaRows = 30;
    
    protected $validateMaxLength = 0;
    protected $validateMinLength = 0;
    
    
    /**
     * Fetches the value of the object as set by the end-user.
     *
     * @return String
     */
    protected function grab_explicitValue()
    {
        $varName = self::PREFIX . $this->fieldname;
        return (isset($_POST[$varName]))? $_POST[$varName]: null;
    }
    
    
    public function snippet_div()
    {   
        $syleRequired = ($this->required)? 'required ': null;
        
        $styleSize = $this->styleSize. ' ';
        
        $styleLength = ($this->validateMinLength > 0)? 'validateMinLength val-'. $this->validateMinLength .' ': null;
        $styleLength .= ($this->validateMaxLength > 0)? 'validateMaxLength val-'. $this->validateMaxLength .' ': null;
        
        $class = 'class="textInput '. $styleSize . $syleRequired . $styleLength . '"';
        
        $r =    '<div class="ctrlHolder">';
        $r .=       '<label for="'. self::PREFIX . $this->fieldname .'">';
        $r .=           ($this->required)? '<em>*</em> ': null;
        $r .=           $this->label;
        $r .=       '</label>';
        $r .=       '<textarea ';
        $r .=       'name="'. self::PREFIX . $this->fieldname .'" ';
        $r .=       'id="'. self::PREFIX . $this->fieldname .'" ';
        $r .=       'data-default-value="'. $this->exampleValue .'" ';
        $r .=       'rows="'. $this->textareaRows .'" cols="'. $this->textareaCols .'" ';
        $r .=       "$class />";
        $r .=       $this->get('value');
        $r .=       '</textarea>';
        $r .=       '<p class="formHint">'. $this->hint .'</p>';
        $r .=   '</div>'. "\n";
        
        return $r;
    }

    public function is_usable()
    {
        $r = true;
        if ($this->validateMaxLength > 0 AND 0) $r = false;
        if ($this->validateMinLength > 0 AND 0) $r = false;
        if ($this->required and 0) $r = false;
        return $r;
    }
    
    /**
     * Sets the size of the textbox
     *
     * @access public
     * @param  string $size Available types: small, medium, large
     * @return $this Instance of _Input_Textbox
     */
    public function set_display_size($size)
    {
        $size = strtolower($size);
        $validators = array('small', 'medium', 'large');
        
        if (in_array($size, $validators))
        {
            $this->styleSize = $size;
        }
        
        return $this;        
    }
    
    
    public function set_cols($int)
    {
        $this->textareaCols = intval($int);
        return $this;
    }
    
    public function set_rows($int)
    {
        $this->textareaRows = intval($int);
        return $this;
    }
        
    
    public function set_length_maximum($value)
    {
        $this->validateMaxLength = $value;
        return $this;
    }
    
    
    public function set_length_minimum($value)
    {
        $this->validateMinLength = $value;
        return $this;
    }
}