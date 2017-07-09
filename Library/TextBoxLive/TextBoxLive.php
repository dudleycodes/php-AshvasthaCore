<?php

namespace TextBoxLive;

use Zend\InputFilter\Exception\InvalidArgumentException;
use Zend\Session\Container;


class TextBoxLive
{
    /**
     * Prefix to ensure separation of form fields.
     * @var string
     */
    const PREFIX = 'txtbxlv_';
    
    /**
     * Example value to be displayed to the user
     * @var string
     */ 
    protected $exampleValue = null;
    
     /**
     * The name of the column in the database table
     * @var string
     */   
    protected $fieldName = null;
    
     /**
     * The auto-generated name of the input as used by html forms (Prefix + fieldName)
     * @var string
     */
    protected $htmlName = null;
    
    /**
     * The label describing the input object to the end-user
     * @var null|string
     */
    protected $label = null;
    
    /**
     * A hint to be shown to the end-user to aid in using the input object
     * @var null|string
     */    
    protected $hint = null;


    /**
     * Determines whether the input object is required to pass is_usable() when being used by the formitive_page object
     * @var boolean
     */
    protected $isRequired = false;


    /**
     * Determines if instance receives user input from $_SESSION rather than $_POST
     * @var boolean
     */
    protected $isSessionEnabled = False;


    /**
     * Container for input_object's seeded value
     * @var various
     */
    protected $seededValue = null;

    
    /**
     * Container for input_object's session (if any)
     * @var null|object
     */
    protected $Session = null;


    /**
     * Holds the instance's current value
     * @var various
     */
    protected $value = null;

    
    /**
     * Creates input object
     * 
     * @param String    $fieldName
     * @param String    $seededValue
     * @param String    $label
     * @param String    exampleValue
     * @final
     */
    final public function __construct($fieldName, $seededValue = null, $label = null, $exampleValue = null)
    {
        // Clean up and process parameters
        $this->$fieldName = (string)$fieldName;
        $this->seededValue = $this->decode_value($seededValue);
        $this->label = (string)$label;
        $this->exampleValue = $this->decode_value($exampleValue);

        //Setup instance
        $this->htmlName = $this::PREFIX . $this->fieldname;

        if ($this->isSessionEnabled)
        {
            $this->Session = new Zend\Session\Container($this->htmlName);

            if (!isset($this->Session->value)) $this->Session->value = $this->seededValue;
            $this->value = $this->Session->value;
        }
        elseif (isset($_POST[$this->htmlName]))
        {
            $this->value = $_POST[$this->htmlName];
        }
        else
        {
            $this->value = $this->seededValue;
        }
        
        $this->__subconstruct();
    }
    
    
    /**
     * To function as a __construct for child instance - automatically called by parent's __construct()
     */
    protected function __subconstruct()
    {
        trigger_error(__CLASS__ . ' does not have a __subconstruct() method defined', E_USER_NOTICE);
    }


    /**
     * Decode a value from a database friendly string to value(s) usable by the TextBoxLive instance
     *
     * @param
     * @return various
     */
    protected function decode_value($encodedValue)
    {
        if (is_null($encodedValue)) return null;

        $t = json_decode($encodedValue);

        return ($t)? $t: $encodedValue;
    }


    /**
     * Encodes a value from a database friendly string to value(s) usable by the TextBoxLive instance
     *
     * @param $value
     * @return variant test
     *
     * @throws Exception Upon an encoding error
     * @throws InvalidArgumentException Upon receiving an invalid argument

     */
    protected function encode_value($value)
    {
        if (is_object($value))
        {
            throw new InvalidArgumentException(__CLASS__ . '->' . __METHOD__ . '() received an object.  Only arrays and single-values are accepted.');
            return null;
        }
        elseif (is_array($value))
        {
            $jsonString = json_encode(value);
            if (json_last_error() == JSON_ERROR_NONE)
            {
                return $jsonString;
            }
            else
            {
                throw new Exception(json_last_error_msg());
                return null;
            }
        }
        else
        {
            return $value;
        }
    }


    /**
     *
     *
     * @return String
     */
    public function get_htmlFormSnippet()
    {
        trigger_error(__CLASS__ . ' does not have a get_htmlFormSnippet() method defined', E_USER_ERROR);

        return null;
    }


    /**
     *
     *
     * @return Boolean
     */
    public function get_isRequired()
    {
        return (Bool)$this->isRequired;
    }


    /**
     *
     *
     * @return String
     */
    public function get_pdfSnippet()
    {
        trigger_error(__CLASS__ . ' does not have a get_pdfSnippet() method defined', E_USER_ERROR);

        return null;
    }


    /**
     * Returns that instance's value in string format
     *
     * @return  String
     */
    public function get_value()
    {
        return (string)$this->encode_value($this->value);
    }


    /**
     * 
     *
     * @param String   $value   The value for hint
     * @return Bool
     */
    public function set_hint($value)
    {
        $this->hint = (string)$value;

        return $this;
    }


    /**
     * Sets isRequired
     *
     * @param Boolean   $Boolean    The value for isRequired
     * @return Bool
     */
    public function set_isRequired($Boolean = true)
    {
        $this->isRequired = (Bool)$Boolean;

        return $this;
    }


    /**
     * Stream HTML to the web browser.  Functionality is for $sessionEnabled instances
     *
     * @return String
     */
    public function stream_html()
    {
        trigger_error(__CLASS__ . ' get_htmlStream() has been called but the instance does not have the method defined but was called.', E_USER_WARNING);

        set_http_status(404);
        echo "<html>\n";
        echo "<head>";
        echo "<title>404 - Page Not Found!</title>";
        echo "</head>\n<body>";
        echo "<h2>Error: 404</h2>\n";
        echo "<h4>Page not initialized.</h4></body></html>";
        die();
    }
}
