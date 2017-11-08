<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
abstract class ConverterAbstract
{
	const ALL_LEVEL  = 15;

	/**
	 * Fixes a file.
	 *
	 * @param \SplFileInfo $file    A \SplFileInfo instance
	 * @param string       $content The file content
	 *
	 * @return string The fixed file content
	 */
	public function convert(\SplFileInfo $file, $content)
	{

	}

	/**
	 * Returns the priority of the converter.
	 *
	 * The default priority is 0 and higher priorities are executed first.
	 */
	public abstract function getPriority();

	/**
	 * Returns the name of the converter.
	 *
	 * The name must be all lowercase and without any spaces.
	 *
	 * @return string The name of the converter
	 */
	public abstract function getName();

	/**
	 * Returns the description of the converter.
	 *
	 * A short one-line description of what the converter does.
	 *
	 * @return string The description of the converter
	 */
	public abstract function getDescription();


    /**
     * Returns true if the file is supported by this converter.
     *
     * @return Boolean true if the file is supported by this converter, false otherwise
     */
    public function supports(\SplFileInfo $file)
    {
    	return true;
    }

    /**
     * Method to extract key/value pairs out of a string with xml style attributes
     *
     * @param   string  $string String containing xml style attributes
     * @return  array   Key/Value pairs for the attributes
     */
    protected function attributes( $string )
    {
        //Initialize variables
        $attr       = array();
        $result     = array();
        $pattern    = '/(?:["\']?([\w:\->]+)["\']?\s*=>?\s*)?(".*?"|\'.*?\'|(?:[$.\w:\-\>()\']+))/';
        // Lets grab all the key/value pairs using a regular expression
        preg_match_all( $pattern, $string, $attr );
        if (is_array($attr)) {
            $numPairs = count($attr[1]);
            for ($i = 0; $i < $numPairs; $i++) {
               
                $value = trim($attr[2][$i]);
                $key   = ($attr[1][$i]) ? trim($attr[1][$i]) : trim(trim($value,'"'),"'");

                $result[$key] = $value;
            }
        }
        return $result;
    }

	/**
	 * Sanitize value, remove $,' or " from string
	 * @param string $string
	 */
	protected function variable($string)
	{
		return str_replace(array('$','"',"'"),'',trim($string));
	}

	/**
	 * Sanitize variable, remove $,' or " from string
	 * @param string $string
	 */
	protected function value($string)
	{
		$string = trim(trim($string),"'");
		$string = (in_array($string{0}, ['$', '"', '\''])) ? ltrim(str_replace(['->', '$'], ['.', ''], $string),'$') : "'".str_replace("'", "\'", $string)."'";
		$string = str_replace(['"', "''"], "'", $string);
		if($string == "'") {
			$string = "''";
		}
		return $string;
	}

	/**
	 * Replace named args in string
	 * 
	 * @param  string $string
	 * @param  array $args 
	 * @return string         Formated string
	 */
	protected function vsprintf($string, $args)
	{
		$pattern = '/:([a-zA-Z0-9_-]+)/';
		return preg_replace_callback($pattern, function($matches) use ($args) {

		    return str_replace($matches[0],$args[$matches[1]],$matches[0]);
		}, $string);
	}
}
