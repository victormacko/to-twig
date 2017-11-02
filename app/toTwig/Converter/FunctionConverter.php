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
		$pattern = '/{(?:if\s)?(([a-zA-Z_]+)\((\$[\w\.\-\>\[\]\(\"\)\$|:\/]+)?(\([\w"\/\s:,]+\))?\))(?:.*)?\}/';

        return preg_replace_callback(
            $pattern,
            function ($matches) {

                list($search, $match, $fnName, $varName) = $matches;

                // Convert Object to dot
                //$varName = str_replace(['->', '()', '$'], ['.', '', ''], $varName);

                $fnName = str_replace(['count'], ['length'], $fnName);
	
                // this only does the conversion from function to filter, and returns it as
				// smarty code (for another converter to then change to twig)
				$search = str_replace($match, $varName . '|' . $fnName, $search);

                return $search;

            },
            $content
        );

    }

}
