<?php

class TextBoxLive_Textbox extends TextBoxLive
{
    protected $styleSize = 'medium';
    
    protected $validatorType = null;
    protected $validateMax = 0;
    protected $validateMaxLength = 0;
    protected $validateMin = 0;
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
        switch ($this->validatorType)
        {
            case 'alpha':           $styleValidator = 'validateAlpha ';     break;
            case 'alphanumeric':    $styleValidator = 'validateAlphaNum ';  break;
            case 'date':            $styleValidator = 'validateDate ';      break;
            case 'email':           $styleValidator = 'validateEmail ';     break;
            case 'integer':         $styleValidator = 'validateInteger ';   break;
            case 'number':          $styleValidator = 'validateNumber ';    break;
            case 'phone':           $styleValidator = 'validatePhone ';     break;
            case 'phrase':          $styleValidator = 'validatePhrase ';    break;
            case 'url':             $styleValidator = 'validateUrl ';       break;
            default:                $styleValidator = null;
        }
        
        $syleRequired = ($this->required)? 'required ': null;
        
        $styleSize = $this->styleSize. ' ';
        
        $styleLength = ($this->validateMinLength > 0)? 'validateMinLength val-'. $this->validateMinLength .' ': null;
        $styleLength .= ($this->validateMaxLength > 0)? 'validateMaxLength val-'. $this->validateMaxLength .' ': null;
        
        $styleValue = ($this->validateMin > 0)? 'validateMin val-'. $this->validateMin .' ': null;
        $styleValue .= ($this->validateMax > 0)? 'validateMax val-'. $this->validateMax .' ': null;
        
        $class = 'class="textInput '. $styleSize . $syleRequired . $styleLength . $styleValidator . $styleValue .'"';
        
        $r =    '<div class="ctrlHolder">';
        $r .=       '<label for="'. self::PREFIX . $this->fieldname .'">';
        $r .=           ($this->required)? '<em>*</em> ': null;
        $r .=           $this->label;
        $r .=       '</label>';
        $r .=       '<input ';
        $r .=       'name="'. self::PREFIX . $this->fieldname .'" ';
        $r .=       'id="'. self::PREFIX . $this->fieldname .'" ';
        $r .=       'value="'. $this->get('value') .'" ';
        $r .=       'data-default-value="'. $this->exampleValue .'" ';
        $r .=       'type = "text" ';
        $r .=       "$class />";
        $r .=       '<p class="formHint">'. $this->hint .'</p>';
        $r .=   '</div>'. "\n";
        
        return $r;
    }

    public function is_usable()
    {
        $r = true;
        if ($this->validateMax > 0 AND 0) $r = false;
        if ($this->validateMaxLength > 0 AND 0) $r = false;
        if ($this->validateMin > 0 AND 0) $r = false;
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
        
        if (in_array($type, $validators))
        {
            $this->styleSize = $size;
        }
        
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
    
    
    
     /**
     * Sets the validator type for _Input_Textbox
     *
     * @access public
     * @param  string $type Available types: Alpha, AlphaNumeric, Date, Email, Integer, Number, Phone, Phrase, Url
     * @return $this Instance of _Input_Textbox
     */
    public function set_validator($type)
    {
        $type = strtolower($type);
        $validators = array('alpha', 'alphanumeric', 'date', 'email', 'integer', 'number', 'phone', 'phrase', 'url');
        
        if (in_array($type, $validators))
        {
            $this->validatorType = $type;            
        }
        
        return $this;        
    }
}