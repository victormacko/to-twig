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
//echo $content;
        $contentNew = preg_replace_callback(
            $pattern,
            function ($matches) {
                list($search, $openTag, $match, , , , $closeTag) = $matches;
	
				$replacements = [
					'|@count' => '|length',
					'|count' => '|length',
					'|trans_choice' => '|transchoice',
					'|date_format' => '|date'
				];
	
				echo $match;
				
				$match = str_replace(array_keys($replacements), array_values($replacements), $match);
				$match = str_replace($search, $openTag . ' ' . $match.' ' . $closeTag, $match);
	
				$match = preg_replace_callback('/([\w.]+)?([|@]+)([\w]+)(:(["\w:]+))?/', function($matches) {
					list($search, $varName, $sep, $fnName) = $matches;
					$params = isset($matches[5]) ? explode(':', $matches[5]) : [];
		
					if($fnName == 'escape' && ($params[0] ?? null) == '"javascript"') {
						$params[0] = '"js"';
					}
					if($sep == '@' && in_array($fnName, ['last'])) {
						$sep = '.';
						$varName = 'loop';
					}
					
					$replacement = $varName . $sep . $fnName . (count($params) > 0 ? '(' . join(', ', $params) . ')' : '');
					$search = str_replace($search, $replacement, $search);
		
					echo 'found: ' . $search;
		
					return $search;
				}, $match);
				/*
				$filters = explode('|', $match);
				foreach($filters as $key => $filter) {
					echo 'look into: ' . $filter;
					
					$filters[$key] = preg_replace_callback('/([\w]+)?([|@]+)([\w]+)(:([\w:]+))?/', function($matches) {
						list($search, $varName, $sep, $fnName) = $matches;
						$params = isset($matches[5]) ? $matches[5] : [];
						
						$replacement = $varName . $sep . $fnName . (count($params) > 0 ? '(' . join(', ', $params) . ')' : '');
						$search = str_replace($search, $replacement, $search);
						
						echo 'found: ' . $search;
						
						return $search;
					}, $filter);
				}
	
				$match = join('|', $filters);
				*/
				echo ' => ' . $match . "\n\n";
                return $openTag . ' ' . trim($match) . ' ' . $closeTag;
            },
            $content
        );
	
		return $contentNew;
    
    }

}
