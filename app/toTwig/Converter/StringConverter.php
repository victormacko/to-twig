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
class StringConverter extends ConverterAbstract
{

    public function convert(\SplFileInfo $file, $content)
    {
        $content = $this->replace($content);

        return $content;
    }

    public function getPriority()
    {
        return 100;
    }

    public function getName()
    {
        return 'variable';
    }

    public function getDescription()
    {
        return 'Convert smarty variable {"abcd"|trans} to twig {{ var.name }}';
    }

    private function replace($content)
    {
        $pattern = '/\{([\'"][\w\.\-\>\[\]\(\)\$|:"]+)+\}/';

        return preg_replace_callback(
            $pattern,
            function ($matches) {

                list($search, $match) = $matches;

                $search = str_replace($search, '{{ '.$match.' }}', $search);

                return $search;

            },
            $content
        );

    }

}
