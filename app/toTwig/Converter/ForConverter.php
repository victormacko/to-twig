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
class ForConverter extends ConverterAbstract
{
	// Lookup tables for performing some token
	// replacements not addressed in the grammar.
	private $replacements = array(
		'smarty\.foreach.*\.index' => 'loop.index0',
		'smarty\.foreach.*\.iteration' => 'loop.index'
	);

	public function convert(\SplFileInfo $file, $content)
	{
		$content = $this->replaceFor($content);
		$content = $this->replaceEndForEach($content);
		$content = $this->replaceForEachElse($content);

		foreach ($this->replacements as $k=>$v) {
			$content = preg_replace('/'.$k.'/', $v, $content);
		}
//echo $content;
		return $content;
	}

	public function getPriority()
	{
		return 50;
	}

	public function getName()
	{
		return 'for';
	}

	public function getDescription()
	{
		return 'Convert foreach/foreachelse to twig';
	}

	private function replaceEndForEach($content)
	{
		$search = '#\{/foreach\s*\}#';
		$replace = '{% endfor %}';
		return preg_replace($search,$replace,$content);
	}

	private function replaceForEachElse($content)
	{
		$search = "#\{foreachelse\s*\}#";
		$replace = "{% else %}";
		return preg_replace($search,$replace,$content);
	}

	private function replaceFor($content){

		// $pattern = "#\{foreach\b\s*(?:(?!}).)+?\}#";
		$pattern = "#\{foreach\b\s*([^{}]+)?\}#i";
		$string  = '{% for :key :item in :from %}';

		return preg_replace_callback($pattern, function($matches) use( $string ) {

			$match   = $matches[1];
			$search  = $matches[0];
			$replace = array('key' => '');

			// {foreach $users as $user}
			if (preg_match("/(.*)(?:as)(.*)/i", $match,$mcs)) {

				// {foreach $users as $k => $val}
				if (preg_match("/(.*)\=\>(.*)/", $mcs[2],$match)) {
					$replace['key'] .= $this->variable($match[1]).',';
					$mcs[2] = $match[2];
				} 
				$replace['item'] = $this->variable($mcs[2]);
				$replace['from'] = $this->variable($mcs[1]);

			} else {

				$attr = $this->attributes($match);

				if (isset($attr['key'])) {
					$replace['key'] = $attr['key'].',';
				}

				$replace['item'] = $this->variable($attr['item']);
				$replace['from'] = $this->variable($attr['from']);
			}

			$string  = $this->vsprintf($string,$replace);
	        // Replace more than one space to single space
	        $string = preg_replace('!\s+!', ' ', $string);

	        return str_replace($search, $string, $search);

		}, $content);

	}

}
