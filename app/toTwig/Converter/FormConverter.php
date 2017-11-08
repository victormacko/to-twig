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
		'\{form_(row|widget|end|start|label|errors|rest) form=\$([a-zA-Z0-9.]+)\}' => '{{ form_\1(\2) }}',	// {form_start form="..."}
	);

	public function convert(\SplFileInfo $file, $content)
	{
		return preg_replace_callback('/\{(form_(?:row|widget|end|start|label|errors|rest))\b\s*([^{}]+)?\}/', function($matches) {
			
			$form_type = $matches[1];
			$attrStr = $matches[2];
			$attr    = $this->attributes($attrStr);
			
			$form = $attr['form'];
			if(strpos($form, '$') === 0) {
				$form = substr($form, 1);
			}
			
			$replace = array(
				'type' => $form_type,
				'form' => $form,
				'vars' => null,
			);
			unset($attr['form']);
			
			// If we have any other variables
			if (count($attr) > 0) {
				$vars = array();
				foreach ($attr as $key => $value) {
					$value  = $this->value($value);
					$vars[] = "'".$key."': ".$value;
				}
				
				$replace['vars'] = ', {'.implode(', ',$vars).'}';
			}
			
			$template = '{{ :type(:form:vars) }}';
			$string  = $this->vsprintf($template, $replace);
			
			return $string;
		}, $content);
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
