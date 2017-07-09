<?php
namespace Ashvastha\Yggdrasil;

class LeafWriter extends \Ashvastha\Yggdrasil\Leaf
{
	public function __construct($locas = null, $portal = null, $Admin)
	{
		
	}

    /**
     * Update the entry to the database.
     *
     * @return Boolean
     */
    public function update()
    {
        if ($this->isSynced()) return true;

        global $Database;

        $quoteIdentifier = function($name) use ($Database)
        {
            return $Database->platform->quoteIdentifier($name);
        };

        $formatParameter = function($name) use ($Database)
        {
            return $Database->driver->formatParameterName($name);
        };

        $sql = 'UPDATE `yggdrasil` '
            . ' SET ' . $quoteIdentifier('appCache') . ' = ' . $formatParameter('appCache')
            . ' SET ' . $quoteIdentifier('appCacheExpires') . ' = ' . $formatParameter('appCacheExpires')
            . ' SET ' . $quoteIdentifier('appName') . ' = ' . $formatParameter('appName')
            . ' SET ' . $quoteIdentifier('appSettings') . ' = ' . $formatParameter('appSettings')
            . ' SET ' . $quoteIdentifier('metaDescription') . ' = ' . $formatParameter('metaDescription')
            . ' SET ' . $quoteIdentifier('metaRobots') . ' = ' . $formatParameter('metaRobots')
            . ' SET ' . $quoteIdentifier('templateName') . ' = ' . $formatParameter('templateName')
            . ' SET ' . $quoteIdentifier('title') . ' = ' . $formatParameter('title')
            . ' WHERE ' . $quoteIdentifier('id') . ' = ' . $formatParameter('id');

        $statement = $Database->query($sql);
        $result = $statement->execute($this->dbRow);

        return (Boolean)$result;
    }


    /**
     * Validates the schema of an array containing the pageData
     *
     * @param  array $dataArray Array containing
     * @return Boolean/String
     */
    public function validate_pageDataSchema($dataArray =  null)
    {
        $errorMessage = false;
        if (is_null($dataArray)) $dataArray = $this->getPageData();

        $areInt = function($values)
        {
            foreach ($values as $value)
            {
                if (!is_int($value)) return false;
            }

            return true;
        };

        if (!is_array($dataArray))
        {
            $errorMessage = 'The data was not contained in an array.';
        }
        elseif (!$areInt(array_keys($dataArray)))
        {
            $errorMessage = 'A first-dimension index was not an integer';
        }
        else
        {
            $dataArray = array_values($dataArray);

            if (!is_array($dataArray))
            {
                $errorMessage = 'An  element was only 1-dimensional';
            }
            elseif (!$areInt(array_keys($dataArray)))
            {
                $errorMessage = 'A second-dimension index was not an integer';
            }
            else
            {
                $dataArray = array_values($dataArray);

                if (!is_array($dataArray))
                {
                    $errorMessage = 'An element was only 2-dimensional';
                }
            }
        }

        return ($errorMessage)? $errorMessage: true;
    }


}