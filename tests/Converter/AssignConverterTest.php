<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests\Converter;

use toTwig\Converter\AssignConverter;
use toTwig\FrameworkTestCase;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class AssignConverterTest extends FrameworkTestCase
{
    /**
     * @var AssignConverter
     */
    protected $converter;

    protected function setUp()
    {
        $this->converter = new AssignConverter();
    }

    /**
     * @covers       \toTwig\Converter\AssignConverter::convert
     * @dataProvider provider
     */
    public function testThatAssignIsConverted($smartyContent, $twigContent)
    {

        // Test the above cases
        $this->assertSame(
            $twigContent,
            $this->converter->convert($this->getFileMock(), $smartyContent)
        );

    }

    public function provider()
    {
        return array(
            array(
                '{assign var="name" value="Bob"}',
                '{% set name = \'Bob\' %}',
            ),
            array(
                '{assign var="name" value=$bob}',
                '{% set name = bob %}',
            ),
            array(
                '{assign "name" "Bob"}',
                '{% set name = \'Bob\' %}',
            ),
            array(
                '{assign var="foo" "bar" scope="global"}',
                '{% set foo = \'bar\' %}',
            ),
        );
    }

}
