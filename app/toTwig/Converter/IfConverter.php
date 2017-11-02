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
 * @author sankar <sankar.suda@gmail.com>
 */
class IfConverter extends ConverterAbstract
{
    private $alt = array(
    	'.' => '->',
        //'gt' => '>',
        //'lt' => '<',
        //'eq' => '==',
        //'neq' => '!=',
        //'ne' => '!=',
        //'not ' => '!',
        //'mod' => '%',
        'or' => '||',
        'and' => '&&',
    );

    public function convert(\SplFileInfo $file, $content)
    {
        // Replace {if }
        $content = $this->replaceIf($content);
        // Replace {elseif }
        $content = $this->replaceElseIf($content);
        // Replace {else}
        $content = preg_replace('#\{/if\s*\}#', "{% endif %}", $content);
        // Replace {/if}
        $content = preg_replace('#\{else\s*\}#', "{% else %}", $content);

        return $content;
    }

    public function getPriority()
    {
        return 50;
    }

    public function getName()
    {
        return 'if';
    }

    public function getDescription()
    {
        return 'Convert smarty if/else/elseif to twig';
    }

    private function replaceIf($content)
    {

        $pattern = "#\{if\b\s*([^{}]+)?\}#i";
        $string = '{%% if %s %%}';

        return $this->replace($pattern, $content, $string);
    }

    private function replaceElseIf($content)
    {

        $pattern = "#\{elseif\b\s*([^{}]+)?\}#i";
        $string = '{%% elseif %s %%}';

        return $this->replace($pattern, $content, $string);

    }


    private function replace($pattern, $content, $string)
    {
        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {

                $match = $matches[1];
                $search = $matches[0];

                foreach ($this->alt as $key => $value) {
                    $match = str_replace($value, $key, $match);
                }

                // Replace $vars
                $match = $this->replaceVariable($match);

                $string = sprintf($string, $match);
                
                // convert in_array - eg. {% if in_array(abcd, myArray) %}
				// to {% if abcd in myArray %}
				$string = preg_replace('/in_array\(([\w.]+),\s+([\w.]+)+\)/', '\1 in \2', $string);
				
                return str_replace($search, $string, $search);

            },
            $content
        );
    }

    private function replaceVariable($string)
    {
    	$pattern = '/(!)?\$([\w\.\-\>\[\]]+(?:\(\))?)/';
	
        return preg_replace_callback(
            $pattern,
            function ($matches) {
                // Convert Object to dot
                $matches[2] = str_replace('->', '.', $matches[2]);
				
                // take into account ! in comparisons - eg. {if !myVar} (smarty)
				// gets changed to {if myVar == false}
				if($matches[1] == '!') {
					$matches[2] .= ' == false';
				}
				
                return str_replace($matches[0], $matches[2], $matches[0]);
            },
            $string
        );
    }
}
