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
 * @author sankara <sankar.suda@gmail.com>
 */
class AssignConverter extends ConverterAbstract
{

    public function convert(\SplFileInfo $file, $content)
    {
        $content = $this->replace($content);

        return $content;
    }

    /**
     * @codeCoverageIgnore
     * @inheritdoc
     */
    public function getPriority()
    {
        return 105;
    }

    /**
     * @codeCoverageIgnore
     * @inheritdoc
     */
    public function getName()
    {
        return 'assign';
    }

    /**
     * @codeCoverageIgnore
     * @inheritdoc
     */
    public function getDescription()
    {
        return "Convert smarty {assign} to twig {% set foo = 'foo' %}";
    }

    private function replace($content)
    {
        $string = '{% set :key = :value %}';

        $shortHandPattern = '/\{\$([\w]+)\s*\=([^\}]+)\}/';
        $content = preg_replace_callback($shortHandPattern, function($matches) use ($string){
            return $this->vsprintf($string, array(
                'key' => $this->variable($matches[1]),
                'value' => $this->value($matches[2]),
            ));
        }, $content);

        $pattern = '/\{assign\b\s*([^{}]+)?\}/';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {

                $match = $matches[1];
                $attr = $this->attributes($match);

                // Short-hand {assign "name" "Bob"}
                if (! isset($attr['var'])) {
                    reset($attr);
                    $key = key($attr);
                } else {
                    $key = $attr['var'];
                }

                if (! isset($attr['value'])) {
                    next($attr);
                    $value = key($attr);
                } else {
                    $value = $attr['value'];
                }

                $value = $this->value($value);
                $key = $this->variable($key);

                $string = $this->vsprintf($string, array('key' => $key, 'value' => $value));
                // Replace more than one space to single space
                $string = preg_replace('!\s+!', ' ', $string);

                return str_replace($matches[0], $string, $matches[0]);

            },
            $content
        );

    }

}
