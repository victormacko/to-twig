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
class FilterConverter extends ConverterAbstract
{

    public function convert(\SplFileInfo $file, $content)
    {
    	$content = $this->replace($content);

        return $content;
    }

    public function getPriority()
    {
        return 20;
    }

    public function getName()
    {
        return 'filter';
    }

    public function getDescription()
    {
        return 'Convert smarty variable {{ $var.name|@count }} to twig {{ var.name|count }}';
    }

    private function replace($content)
    {
		$pattern = '/(\{[{%])\s?((if)? [$\w\.\-\>\[\]\(\)\$|:"]+[|@][\@\w|\"\:]+(\s?[!=<>]{1,2}\s?([0-9]+|true|false))?)+ ([}%]\})/';
    	$pattern = '/(\{[{%]) ([^\}]+) ([}%]\})/';

        $contentNew = preg_replace_callback(
            $pattern,
            function ($matches) {
                list($search, $openTag, $match, $closeTag) = $matches;
	
                // converts smarty filter names to twig names
				$replacements = [
					'|@count' => '|length',
					'|count' => '|length',
					'|trans_choice' => '|transchoice',
					'|date_format' => '|date',
					'|ucfirst' => '|title',
					'|isset' => ' is defined'
				];
				
				$match = str_replace(array_keys($replacements), array_values($replacements), $match);
				$match = str_replace($search, $openTag . ' ' . $match.' ' . $closeTag, $match);
	
				// change filters with params (smarty format) to twig format
				// eg. $myVar|escape:"js" ... or even just $myVar|escape
				$match = preg_replace_callback('/([\w.]+)?([|@]+)([\w]+)(:([\'"\w:\%\s,\/]+))?/', function($matches) {
					list($search, $varName, $sep, $fnName) = $matches;
					$params = isset($matches[5]) ? explode(':', $matches[5]) : [];
		
					// if we have the 'escape' function, and it uses a JS param, then update it
					// to the twig terminology
					if($fnName == 'escape' && isset($params[0]) && $params[0] == '"javascript"') {
						$params[0] = '"js"';
					}
					
					// update myloop@last (smarty) to loop.last (twig)
					if($sep == '@' && in_array($fnName, ['index', 'last'])) {
						$sep = '.';
						$varName = 'loop';
						
						if($fnName == 'index') {
							$fnName = 'index0';
						}
					}
					
					$replacement = $varName . $sep . $fnName . (count($params) > 0 ? '(' . join(', ', $params) . ')' : '');
					$search = str_replace($search, $replacement, $search);
		
					return $search;
				}, $match);
				
                return $openTag . ' ' . trim($match) . ' ' . $closeTag;
            },
            $content
        );
	
		return $contentNew;
    
    }

}
