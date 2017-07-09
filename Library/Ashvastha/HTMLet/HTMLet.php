<?php
namespace Ashvastha\HTMLet;


class HTMLet
{
    protected $conditionalScripts = array();
    protected $externalDocuments = array();
    protected $html = '';
    protected $inlineScripts = array();
    protected $statusCode = 200;
    protected $title = '';
    

    public function add_conditionalScript($condition, $value)
    {
        if ($condition AND $value)
        {
            $condition = strtoupper($condition);

            //Avoid duplicates
            foreach($this->conditionalScripts as $t)
            {
                if (array_key_exists($condition, $t) AND $t[$value] == $value) return $this;
            }

            $this->conditionalScripts[] = array('condition' => $condition, 'value' => $value);
        }

        if (empty($condition))  throw new InvalidArgumentException('Received an empty $condition', E__USER_WARNING);
        if (empty($value))      throw new InvalidArgumentException('Received an empty $value', E_USER_WARNING);

        return $this;
    }


    public function add_externalDocument($href)
    {
        if (!is_array($href)) $href = array(strval($href));

        foreach ($href as $t)
        {
            if (!in_array($t, $this->externalDocuments)) $this->externalDocuments[] = $t;
        }
        
        return $this;
    }
    
    
    public function add_html($html)
    {
        $this->html .= strval($html);
        
        return $this;
    }
    
    
    public function add_inlineScript($scriptBlock)
    {
        if (!is_array($scriptBlock)) $scriptBlock = array(trim($scriptBlock));

        foreach ($scriptBlock as $t)
        {
            if (!in_array($scriptBlock, $this->inlineScripts)) $this->inlineScripts[] = $t;
        }
        
        return $this;
    }


    public function get_code()
    {
        return (int)$this->statusCode;
    }


    public function get_conditionalScripts()
    {
        return $this->conditionalScripts;
    }
    
    
    public function get_externalDocuments()
    {
        return $this->externalDocuments;
    }
    
    
    public function get_html()
    {
        return (string)$this->html;
    }
    
    
    public function get_inlineScripts()
    {
        return $this->inlineScripts;
    }
    
    
    public function get_title()
    {
        return (string)$this->title;
    }


    public function get_statusCode()
    {
        return $this->statusCode;
    }
    
    
    public function set_statusCode($code)
    {
        $code = intval($code);

        $whitelist = array( 100, 101, 102, 122, 200, 201, 202, 203, 204, 205, 206, 207, 226, 
                            300, 301, 302, 303, 304, 305, 306, 307, 
                            400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417,
                            418, 422, 423, 424, 425, 426, 444, 449, 450, 451, 499, 
                            500, 501, 502, 503, 504, 505, 506, 507, 509, 510);
        
        if (!in_array($code, $whitelist))
        {
            throw new \InvalidArgumentException('Received an unknown status code - status code not set.', E_USER_WARNING);

            return $this;
        }

        $this->statusCode = $code;

        return $this;
    }
    
    
    public function set_title($title)
    {
        $this->title = strval($title);
        
        return $this;
    }
}