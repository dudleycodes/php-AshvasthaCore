<?php

namespace Ashvastha\HTMLet;


class Matrix
{
    private static $_Instance = null;
    protected $conditionalScripts = array();
    protected $externalDocuments = array();
    protected $htmlets = array();      //Array containing arrays of html scraps
    protected $inlineScripts = array();


    private function __construct()
    {

    }


    public function __clone()
    {
        trigger_error('Cannot clone instance of singleton class '. _CLASS__,  E_USER_ERROR );
    }


    public function __wakeup()
    {
        trigger_error('Cannot deserialize instance of singleton class '. _CLASS__, E_USER_ERROR );
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


    public function add_HTMLet($HTMLet, $zoneName)
    {
        if (!is_array($HTMLet)) $HTMLet = array($HTMLet);
        $zoneName = strtolower($zoneName);

        foreach ($HTMLet as $T)
        {
            if (get_class($T) == 'HTMLet')
            {
                $this->$htmlets[$zoneName][] = $T;

                if (count($T->get_conditionalScripts()))    $this->conditionalScripts[] =   array_merge($this->conditionalScripts, $T->get_conditionalScripts());
                if (count($T->get_externalDocuments()))     $this->add_externalDocument($T->get_externalDocuments());
                if (count($T->get_inlineScripts()))         $this->inlineScripts[] =        array_merge($this->inlineScripts, $T->get_inlineScripts());
            }
            else
            {
                throw new \InvalidArgumentException('Received an HTMLet that was not of class HTMLet', E_USER_ERROR);
            }
        }

        return $this;
    }


    public function get_htmlConditionalScripts()
    {
        return '';
    }


    public function get_htmlExternalDocuments()
    {
        $cssFiles = array();
        $icoFile = '';
        $jsFiles = array();
        $returnHTML = '';

        //= Function to sort by string length, then in alphabetical order
        $sortByLengthAlphabetical = function($a, $b)
        {
            if (strlen($a) == strlen($b))
            {
                return strcmp($a, $b);
            }
            else
            {
                return strlen($a) - strlen($b);
            }
        };

        //= Divide external documents up by type
        foreach ($this->externalDocuments as $externalDocument)
        {
            $fileExtension = mb_substr($externalDocument, strrpos($externalDocument, '.'));
            $fileExtension = strtolower($fileExtension);

            switch ($fileExtension)
            {
                case '.css':    $cssFiles[] = $externalDocument;        break;
                case '.ico':    $icoFile = $externalDocument;           break;
                case '.js':     $jsFiles[] = $externalDocument;         break;
            }
        }

        //= CSS
        foreach ($cssFiles as $cssFile)
        {
            $returnHTML .= "\t\t". '<link href="'. $cssFile .'" rel="stylesheet" type="text/css" />' . "\n";
        }

        //= ICO (shortcut icon)
        if (!empty($icoFile))
        {
            $returnHTML .= "\t\t". '<link href="'. $icoFile .'" rel="Shortcut Icon" type="image/x-icon" />' . "\n";
        }

        //= JAVASCRIPTS
        $jsFiles = usort($jsFiles, $sortByLengthAlphabetical);
        foreach ($jsFiles as $jsFile)
        {
            $returnHTML .= "\t\t". '<script src="'. $jsFile .'"></script>' . "\n";
        }

        return $returnHTML;
    }


    public function get_htmlInlineScripts()
    {
        return '';
    }


    public static function get_Instance()
    {
        if (empty(static::$_Instance))  static::$_Instance = new self();

        return self::$_Instance;
    }
}