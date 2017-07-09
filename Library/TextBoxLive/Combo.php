<?php

namespace TextBoxLive;


class Combo extends TextBoxLive
{
    /**
     * Determines whether the options in the drop-down combo input will be automatically sorted in alphabetical order.
     * @var boolean
     */
    protected $isAutoSorted = true;


    /**
     * Determines the options that will be available in the drop-down combo input
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
        if (!is_null($description))
        {
            $value = null;

            for($t = 0; $t < strlen($description); $t++)
            {
                $c = mb_substr($description, $t, 1);
                $value = $value . (ord($c) < 32 OR ord($c) == 122)? '': $c;
            }

            if (!in_array($value, $this->options)) $this->options[] = $value;
        }

        return $this;
    }


    /**
     * Remove an option
     *
     * @param   string  $description
     * @return  $this
     */
    protected function remove_option($description)
    {
        $i = array_search($description, $this->options, false);

        if ($i) unset($this->options[$i]);

        unset($i);

        return $this;
    }


}