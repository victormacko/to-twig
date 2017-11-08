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
class BlockConverter extends ConverterAbstract
{

	// Lookup tables for performing some token
	// replacements not addressed in the grammar.
	private $replacements = array(
		'\{block name="([a-zA-Z0-9_]+)"\}' => '{% block \1 %}',	// {block name="myname"}
		'\{block name=([a-zA-Z0-9_]+)\}' => '{% block \1 %}',		// {block name=myname}
		'\{block ([a-zA-Z0-9_]+)\}' => '{% block \1 %}',			// {block myname}
		'\{block "([a-zA-Z0-9_]+)"\}' => '{% block \1 %}',			// {block "myname"}
		'\{block name="([a-zA-Z0-9_]+)" append\}' => '{% block \1 %}{{ parent() }}',	// {block name="myname"}
		'\{block name=([a-zA-Z0-9_]+) append\}' => '{% block \1 %}{{ parent() }}',		// {block name=myname}
		'\{block ([a-zA-Z0-9_]+) append\}' => '{% block \1 %}{{ parent() }}',			// {block myname}
		'\{block "([a-zA-Z0-9_]+)" append\}' => '{% block \1 %}{{ parent() }}',			// {block "myname"}
		'\{\/block\}' => '{% endblock %}'
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
		return 'block';
	}

	public function getDescription()
	{
		return 'Convert smarty block tags like {block name="myname"}, {block myname}, etc';
	}

}
