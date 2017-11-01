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
class FormConverter extends ConverterAbstract
{

	// Lookup tables for performing some token
	// replacements not addressed in the grammar.
	private $replacements = array(
		'\{form_(row|widget|end|start|label|errors) form=\$([a-zA-Z0-9.]+)\}' => '{{ form_\1(\2) }}',	// {form_start form="..."}
		
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
		return 'form';
	}

	public function getDescription()
	{
		return 'Convert custom smarty form tags {form_start form=$form.age}, {form_errors form=$form.age}, etc';
	}

}
