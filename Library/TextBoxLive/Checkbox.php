<?php
namespace TextBoxLive;


class Checkbox extends TextBoxLive
{
    /**
     * Determines whether the checkboxes will be automatically sorted in alphabetical order before being displayed to the end user.
     * @var boolean
     */
    protected $isAutoSorted = true;


    /**
     * Determines whether the checkboxes will be automatically sorted in alphabetical order before being displayed to the end user.
     * @var array
     */
    protected $options = array();


    /**
     * Automatically called by parent's __construct()
     */
    protected function __subconstruct()
    {
        //Nothing to do here
    }


    /**
     * Add an option
     *
     * @param   string  $description
     * @return  $this
     */
    protected function add_option($description)
    {
        if (!is_array($description)) $description = array($description);

        foreach ($description as $t)
        {
            $key = $this->encode_keyFromDescription($t);
            $this->options[$key] = $t;
        }

        return $this;
    }


    /**
     * Encodes an option description into a key
     *
     * @param   string  $description
     * @return  string  $key
     */
    protected function encode_keyFromDescription($description)
    {
        if (is_null($description))
        {
            //todo throw exception
            return null;
        }

        $key = null;

        for($t = 0; $t < strlen($description); $t++)
        {
            $c = mb_substr($description, $t, 1);
            $key = $key . (ord($c) < 48 OR ord($c) > 122)? '=': $c;
        }

        return strtolower($key);
    }


    /**
     * Add an option
     *
     * @param   string  $description
     * @return $this
     */
    protected function remove_option($description)
    {
        if (!is_array($description)) $description = array($description);

        foreach ($description as $t)
        {
            $key = $this->encode_keyFromDescription($t);

            if (isset($this->options[$key]))
            {
                unset($this->options[$key]);
            }
            else
            {
                //todo throw exception
            }
        }

        return $this;
    }
}