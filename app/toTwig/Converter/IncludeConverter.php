<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class IncludeConverter extends ConverterAbstract
{

	public function convert(\SplFileInfo $file, $content)
	{
		return $this->replace($content);
	}

	public function getPriority()
	{
		return 100;
	}

	public function getName()
	{
		return 'include';
	}

	public function getDescription()
	{
		return 'Convert smarty include to twig include';
	}

	private function replace($content)
	{
		$pattern = '/\{include\b\s*([^{}]+)?\}/';
		$string = '{% include :template :with :vars %}';

		return preg_replace_callback($pattern, function($matches) use ($string) {

	        $match   = $matches[1];
	        $attr    = $this->attributes($match);

	        $replace = array(
	            'template' => $attr['file'],
                'with' => null,
                'vars' => null,
            );

	        // If we have any other variables
	        if (count($attr) > 1) {
	            $replace['with'] = 'with';
	            unset($attr['file']); // We won't need in vars

	             $vars = array();
	            foreach ($attr as $key => $value) {
	            	$value  = $this->value($value);
	                $vars[] = "'".$key."' : ".$value;
	            }

	            $replace['vars'] = '{'.implode(', ',$vars).'}';
	        }

	        $string  = $this->vsprintf($string,$replace);

	        // Replace more than one space to single space
	        $string = preg_replace('!\s+!', ' ', $string);

	        return str_replace($matches[0], $string, $matches[0]);

	      },$content);

	}

}
