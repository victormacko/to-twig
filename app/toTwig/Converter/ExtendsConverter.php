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
class ExtendsConverter extends ConverterAbstract
{

	// Lookup tables for performing some token
	// replacements not addressed in the grammar.
	private $replacements = array(
		'\{extends file=["\']([^\"^\']+)["\']\}' => '{% extends "\1" %}',	// {extends name="myname"}
		'\{extends file=([^\}]+)\}' => '{% extends "\1" %}',				// {extends name=myname}
		'\{extends ["\']([^\"^\']+)["\']\}' => '{% extends "\1" %}',		// {extends "myname"}
		'\{extends ([^}]+)\}' => '{% extends "\1" %}'						// {extends abcd}
	);

	public function convert(\SplFileInfo $file, $content)
	{
		foreach ($this->replacements as $k=>$v) {
			$content = preg_replace('/'.$k.'/', $v, $content);
		}

		return $content;
	}

	public function getPriority()
	{
		return 100;
	}

	public function getName()
	{
		return 'extends';
	}

	public function getDescription()
	{
		return 'Convert smarty extends tags {extends name="myname"}, {extends "abcd"}, etc';
	}

}
