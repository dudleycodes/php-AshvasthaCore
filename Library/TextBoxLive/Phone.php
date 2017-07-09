<?php

class TextBoxLive_Phone extends TextBoxLive
{
    protected $forcedType = null;
    protected $validTypes = array('cell', 'home', 'work', 'fax', 'internet', 'other');
    
    protected $valueNumber = null;
    protected $valueType = null;
    protected $valueExtension = null;
    
    protected function __inputconstruct()
    {   
        try
        {
            $t = Zend1_Json::decode($this->get('value'));
            
            $this->valueNumber = (isset($t['number']))? $this->number_readable($t['number']): null;
            
            if ($this->forcedType)
            {
                $this->valueType = $this->forcedType ;
            }
            elseif (isset($t['type']))
            {
                $this->valueType = $t['type'];
            }
            
            $this->valueExtension = (isset($t['extension']))? $t['extension']: null;
        }
        catch (Exception $e)
        {
            $this->debugMessages[] = 'Was unable to Zend1_Json::decode $this->value.'. $e;
        }
    }
    
    /**
     * Disables the user's ability to select their phone type. Use when requesting a specific device number (such as a fax)
     *
     * @param String $type A string containing a valid phone number type. Set to null to disable.
     * @return $this
     */
    public function force_type($type = null)
    {
        $type = strtolower($type);
        
        if (empty($type))
        {
            $this->forcedType = null;
        }
        elseif (in_array($type, $this->validTypes))
        {
            $this->forcedType = $type;
        }
        else
        {
            $this->debugMessages[] = "force_type() failed -- type '$type' is unknown.";
        }
        
        return $this;
    }
    
    public function get($var = null)
    {
        $var = strtolower($var);
        
        switch ($var)
        {
            case 'number':
                return $this->valueNumber;
                break;
            case 'type':
                return $this->valueType;
                break;
            case 'ext':
            case 'extension':
                return $this->valueExtension;
                break;            
            default:
                return parent::get($var);
        }
    }
    
    protected function grab_explicitValue()
    {
        $r = null;
        
        $varNameNumber = self::PREFIX . $this->fieldname .'_number';
        if (isset($_POST[$varNameNumber]) AND !empty($_POST[$varNameNumber]))
        {
            $r['number'] = $this->number_filter($_POST[$varNameNumber]);
        }
        
        $varNameType = self::PREFIX . $this->fieldname .'_type';
        if ($this->forcedType)
        {
            $r['type'] = $this->forcedType;
        }
        elseif (isset($_POST[$varNameType]) AND !empty($_POST[$varNameType]))
        {
            $type = trim(strtolower($_POST[$varNameType]));
            
            if (!in_array($type, $this->validTypes))
            {
                switch ($type)
                {
                    case 'car':
                    case 'carphone':
                    case 'cellular':
                    case 'disposable':
                    case 'mobile':
                    case 'portable':
                    case 'prepaid':
                    case 'wireless':
                        $type = 'cell';
                        break;
                    case 'landline':
                        $type = 'home';
                        break;
                    case 'inet':
                    case 'web':
                        $type = 'internet';
                        break;
                    default:
                        $type = 'unknown';
                }
            }
            $r['type'] = $type;
        }
        
        $varNameExtension = self::PREFIX . $this->fieldname .'_extension';
        if (isset($_POST[$varNameExtension]) AND !empty($_POST[$varNameExtension]))
        {
            $r['extension'] = $this->number_filter($_POST[$varNameExtension]);
        }
        
        if (empty($r)) return null;
        
        try
        {
            $r = Zend1_Json::encode($r);
        }
        catch (Exception $e)
        {
            $this->debugMessages[] = 'Input_Phone->grab_explicitValue() had a problem w/ Zned_Json::encode() on line '. __LINE__ .'. '.$e;
            return null;
        }
        
        return $r;
    }
    
    protected function number_filter($number)
    {
        $number = strtolower($number);
        $number = str_replace('a', '2', $number);
        $number = str_replace('b', '2', $number);
        $number = str_replace('c', '2', $number);
        $number = str_replace('d', '3', $number);
        $number = str_replace('e', '3', $number);
        $number = str_replace('f', '3', $number);
        $number = str_replace('g', '4', $number);
        $number = str_replace('h', '4', $number);
        $number = str_replace('i', '4', $number);
        $number = str_replace('j', '5', $number);
        $number = str_replace('k', '5', $number);
        $number = str_replace('l', '5', $number);
        $number = str_replace('m', '6', $number);
        $number = str_replace('n', '6', $number);
        $number = str_replace('o', '6', $number);
        $number = str_replace('p', '7', $number);
        $number = str_replace('q', '7', $number);
        $number = str_replace('r', '7', $number);
        $number = str_replace('s', '7', $number);
        $number = str_replace('t', '8', $number);
        $number = str_replace('u', '8', $number);
        $number = str_replace('v', '8', $number);
        $number = str_replace('w', '9', $number);
        $number = str_replace('x', '9', $number);
        $number = str_replace('y', '9', $number);
        $number = str_replace('z', '9', $number);
        $number = preg_replace('/\D/', '', $number);
        return $number;
    }
    
    
    /**
     * Makes a phone number human-readable
     *
     * @param integer The number to be made presentable
     * @return string The number in a human-readable format
     */
    protected function number_readable($number)
    {
        $number = (string)$number;
        $l = strlen($number);
        
        if ($l == 7)        //012-3456
        {
            $r = substr($number, 0, 3) .'-'. substr($number, 3, 4);
        }
        elseif ($l == 10)   //(012) 345-6789
        {
            $r = '('. substr($number, 0, 3) .') '. substr($number, 3, 3) .'-'. substr($number, 6, 4);
        }
        elseif ($l == 11)    // 0 (123) 456-7890
        {
            $r = substr($number, 0, 1) .' ('. substr($number, 1, 3) .') '. substr($number, 4, 3) .'-'. substr($number, 7, 4);
        }
        else
        {
            $r = $number;
        }
        
        unset($l);
        return $r;
    }
    
    
    public function snippet_div()
    {
        $syleRequired = ($this->required)? ' required': null;
        $r =    '<div class="ctrlHolder">';
        $r .=       '<label for="'. self::PREFIX. $this->fieldname .'">';
        $r .=           ($this->required)? '<em>*</em> ': null;
        $r .=           $this->label;
        $r .=       '</label>';
        
        
        $inputName = self::PREFIX . $this->fieldname .'_number';
        $r .=       '<input name="'. $inputName .'" ';
        $r .=       'id="'. $inputName .'" ';
        $r .=       'value="'. $this->valueNumber .'" ';
        $r .=       'data-default-value="'. $this->exampleValue .'" ';
        $r .=       'type = "text" class="textInput validatePhone tiny '. $syleRequired .'"/>';
        
        $inputName = self::PREFIX . $this->fieldname .'_type';
        $disabled = (!is_null($this->forcedType))? ' disabled="disabled"' : null;
        $r .=       '<select name="'. $inputName .'" id="'. $inputName .'" class="selectInput auto"'. $disabled .'>';
        foreach ($this->validTypes as $type)
        {
            if ($this->forcedType == $type OR $this->valueType == $type)
            {
                $r .= "<option selected>$type</option>";
            }
            else
            {
                $r .= "<option>$type</option>";
            }
        }
        $r .=       '</select>';
        
        $inputName = self::PREFIX . $this->fieldname .'_extension';
        $r .=       '<input name="'. $inputName .'" ';
        $r .=       'id="'. $inputName .'" ';
        $r .=       'value="'. $this->valueExtension .'" ';
        $r .=       'data-default-value="Extension" ';
        $r .=       'type = "text" class="textInput tiny"/>';
        
        $r .=       '<p class="formHint">'. $this->hint .'</p>';
        $r .=   '</div>'. "\n";
        
        return $r;
    }
}