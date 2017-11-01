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
 * @author Victor Macko <victor_macko@hotmail.com>
 */
class PathConverter extends ConverterAbstract
{
	public function convert(\SplFileInfo $file, $content)
	{
		$pattern = '/\{path\b\s*route=[\'"]([\w]+)[\'"](\s+params=\[([^{}]+)\])?\}/';
		$string = '{{ path(:route:vars) }}';
		
		$ret = preg_replace_callback($pattern, function($matches) use ($string) {
			
			$route = $matches[1];
			$params = $matches[3];
			
			$attr = $this->attributes($params);
			
			$replace = [
				'route' => '"' . $route . '"',
				'vars' => $attr,
			];
			
			// If we have any other variables
			if (count($attr) > 0) {
				$vars = array();
				foreach ($attr as $key => $value) {
					$value  = $this->value($value);
					$vars[] = "'".$key."': ".$value;
				}
				
				$replace['vars'] = ', {'.implode(', ',$vars).'}';
			} else {
				unset($replace['vars']);
			}
			//print_r($replace);
			$string  = $this->vsprintf($string,$replace);
			
			// Replace more than one space to single space
			$string = preg_replace('!\s+!', ' ', $string);
		
			return $string;
		}, $content);
		
		return $ret;
	}

	public function getPriority()
	{
		return 100;
	}

	public function getName()
	{
		return 'path';
	}

	public function getDescription()
	{
		return 'Convert custom smarty path function {path route="abcd"}, {path route="abcd" params=[...]}, etc';
	}

}
