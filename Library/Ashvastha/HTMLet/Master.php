<?php
namespace Ashvastha\HTMLet;


class Master extends HTMLet
{
    protected $doctype = '<!DOCTYPE html>';
    protected $metaAuthor = null;
    protected $metaCharset = 'UTF-8';
    protected $metaDescription = null;
    protected $metaRobots = null;
    protected $metaViewport = 'width=device-width, initial-scale=1';

    //Attributes for document root (<html> tag)
    protected $docAttributeClass = null;
    protected $docAttributeLang = 'en';

    
    public function addHTMLet($arrayOfHTMLets)
    {
        if (is_null($arrayOfHTMLets)) return false;
        if (!is_array($arrayOfHTMLets)) $arrayOfHTMLets = array($arrayOfHTMLets);
        
        foreach ($arrayOfHTMLets as $HTMLet)
        {
            if (get_class($HTMLet) == 'HTMLet')
            {
                $this->addHTML($HTMLet->getHTML());
                $this->addExternalDocument($HTMLet->getExternalDocuments());
                $this->addInlineScript($HTMLet->getInlineScripts());
            }
            else 
            {
                trigger_error(__CLASS__ .'->'. __METHOD__ .'() received a value that was not an instance of HTMLet ',
                              E_USER_WARNING);
            }
        }
    }
    
    
    public function getRenderedHTML()
    {
        $r  = (string)$this->doctype . "\n";

        //html document tag
        $t = (!empty($this->docAttributeClass))? 'class="' . $this->docAttributeClass . '" ' : null;
        if (!empty($this->docAttributeLang)) $t .= 'lang="' . $this->docAttributeLang . '"';

        $r .= "<html $t>\n";
        unset($t);
        //html document tag end

        //head block
        $r .= "<head>\n";
        if ($this->metaAuthor) $r .= '<meta name="author" content="'. $this->metaAuthor .'" />' ."\n";
        $r .= '<meta charset="'. $this->metaCharset .'" />' . "\n";
        if ($this->metaDescription) $r .= '<meta name="description" content="'. $this->metaDescription .'" />' ."\n";
        $r .= '<meta name="viewport" content="'. $this->metaViewport .'" />' ."\n";
        if ($this->title) $r .= '<title>'. $this->title ."</title>\n";
        
        $cssDocuments = array();
        $iconDocument = null;
        $jsDocuments = array();
        
        foreach ($this->externalDocuments as $document)
        {
            $extension = strtolower(strrchr($document, '.'));
            
            switch ($extension)
            {
                case '.css':
                    $s = '<link rel="stylesheet" type="text/css" href="'. $document .'" />' . "\n";
                    if (!in_array($s, $cssDocuments)) $cssDocuments[] = $s;
                    break;
                case '.js':
                    $s = '<script language="javascript" type="text/javascript" src="' . $document . '"></script>' . "\n";
                    if (!in_array($s, $jsDocuments)) $jsDocuments[] = $s;
                    break;
                case '.ico':
                case '.png':
                    $iconDocument = '<link rel="shortcut icon" href="'. $document .'" type="image/x-icon" />' ."\n";
                    break;
                default:
                    trigger_error(__CLASS__ . '.  ' . __METHOD__ . '() does not know how to handle external document '
                                  . $document, E_USER_WARNING);
            }
        }

        $r .= $iconDocument;
        
        foreach ($cssDocuments as $doc)
        {
            $r .= $doc;
        }
        
        foreach ($jsDocuments as $doc)
        {
            $r .= $doc;
        }

        if (!empty($this->conditionalComments))
        {
            foreach ($this->conditionalComments as $t)
            {
                if (!empty($t['developerNote'])) $r .= '<!-- ' . $t['developerNote'] . ' -->' ."\n";
                $r .= '<!--[' . $t['condition'] . ']-->' . "\n";
                $r .= $t['value'] ."\n";
                $r .= "<![endif]-->\n";
            }
        }

        $r .= "</head>\n";

        //head block end

        
        $r .= $this->html;

        $r .= "\n</html>";
        
        
        return $r;
    }
    
    
    protected function minifyHTML($html)
    {
        $search = array(
                        '/\>[^\S ]+/s',     //strip whitespaces after tags, except space
                        '/[^\S ]+\</s',     //strip whitespaces before tags, except space
                        '/(\s)+/s' );       //shorten multiple whitespace sequences
        $replace = array(
                        '>',
                        '<',
                        '\\1');
        $html = preg_replace($search, $replace, $html);
        
        return $html;
    }


    public function setDoctype($doctype)
    {
        $doctype = strtolower($doctype);
        $validDoctypes = array( 'html4-strict', 'html4-transitional', 'html4-frameset', 'xhtml-strict', 'xhtml-transitional', 'xhtml-frameset', 'html5' );
        if (in_array($doctype, $validDoctypes))
        {
            switch ($doctype)
            {
                case 'html4-strict':
                    $this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">' . "\n";
                    break;
                case 'html4-transitional':
                    $this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">' . "\n";
                    break;
                case 'html4-frameset':
                    $this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">' . "\n";
                    break;
                case 'xhtml-strict':
                    $this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
                    break;
                case 'xhtml-transitional':
                    $this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
                    break;
                case 'xhtml-frameset':
                    $this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">' . "\n";
                    break;
                default:
                    $this->doctype = '<!DOCTYPE html>' . "\n";
            }
        }

        return $this;
    }


    public function setMetaAuthor($value)
    {
        $this->metaAuthor = (string)(strlen($value) > 48)? substr($value, 48): $value;
    }
    
    
    public function setMetaCharset($newCharset)
    {
        $validCharsets = array('UTF-8');
        
        if (in_array($newCharset, $validCharsets))
        {
            $this->metaCharset = (string)$newCharset;
        }
        else
        {
            $this->metaCharset = 'UTF-8';
            
            trigger_error(__CLASS__ . ": setMetaCharset recieved an invalid value of '$newCharset'", E_USER_WARNING);
        }
        
        return $this;
    }
    
    
    public function setMetaDescription($newDescription)
    {
        $this->metaDescription = is_null($newDescription)? null: (string)$newDescription;
        
        return $this;
    }
    
    
    public function setMetaRobots($value)
    {
        if (!$value)
        {
            $this->metaRobots = null;
        }
        else
        {
            $value = strtolower(trim(strval($value)));

            $tags = array();
            $tags[] = (strstr($value, 'noindex'))? 'NOINDEX': 'INDEX';
            $tags[] = (strstr($value, 'nofollow'))? 'NOFOLLOW': 'FOLLOW';
            if (strstr($value, 'nosnippet')) $tags[] = 'NOSNIPPET';
            if (strstr($value, 'noarchive')) $tags[] = 'NOARCHIVE';
            if (strstr($value, 'noodp')) $tags[] = 'NOODP';
            
            $this->metaRobots = implode(', ', $tags);
        }
        
        return $this;
    }

    public function setMetaViewport($value)
    {
        $this->setMetaViewport = (string)$value;
    }

    /**
     * Sets the class for the document's <html> tag (document root)
     *
     * @param $value
     * @return this
     */
    public function setAttributeClass($value)
    {
        $this->docAttributeClass = (string)$value;

        return $this;
    }

    public function setAttributeLang($value)
    {
        $this->docAttributeLang = (string)$value;

        return $this;
    }
}