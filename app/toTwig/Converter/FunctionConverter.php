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
class FunctionConverter extends ConverterAbstract
{

    public function convert(\SplFileInfo $file, $content)
    {
        $content = $this->replace($content);

        return $content;
    }

    public function getPriority()
    {
        return 150;
    }

    public function getName()
    {
        return 'function';
    }

    public function getDescription()
    {
        return 'Convert functions {json_encode($var.name)} to twig {{ var.name|json_encode }}';
    }

    private function replace($content)
    {
		$pattern = '/{(?:if\s)?(?:.*\s)?(([a-zA-Z_]+)\((\$[\w\.\-\>\[\]\(\"\)\$|:\/]+)?(\([\w"\/\s:,]+\))?\))(?:\s.*)?\}/';
		$pattern = '/{(?:if\s)?(?:.*\s)?(([a-zA-Z_]+)\((\s?\$[\w\.\-\>\[\]\(\"\)\$|:\/]+)?(\([\w"\/\s:,]+\))?(?:,\s([\w\.\-\>\[\]\(\"\)\$|:\/,\s]+)?)?\))(?:\s.*)?\}/';
        return preg_replace_callback(
            $pattern,
            function ($matches) {

                list($search, $match, $fnName, $varName) = $matches;
                
                if($fnName == 'in_array') {
                	return $search;
				}
                
                $otherVars = isset($matches[5]) ? trim($matches[5]) : '';

                // Convert Object to dot
                //$varName = str_replace(['->', '()', '$'], ['.', '', ''], $varName);

                $fnName = str_replace(['count'], ['length'], $fnName);
	
                // this only does the conversion from function to filter, and returns it as
				// smarty code (for another converter to then change to twig)
				$search = str_replace($match, $varName . '|' . $fnName . ($otherVars ? '(' . $otherVars . ')' : ''), $search);

                return $search;

            },
            $content
        );

    }

}
