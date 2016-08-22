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

use toTwig\Converter\IncludeConverter;
use toTwig\FrameworkTestCase;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class IncludeConverterTest extends FrameworkTestCase
{
    /**
     * @var IncludeConverter
     */
    protected $converter;

    public function setUp()
    {
        $this->converter = new IncludeConverter();
    }

    /**
     * @covers       \toTwig\Converter\IncludeConverter::convert
     * @dataProvider provider
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {

        // Test the above cases
        $this->assertSame(
            $twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );

    }

    public function provider()
    {
        return array(
            array(
                "{include file='page_header.tpl'}",
                "{% include 'page_header.tpl' %}",
            ),
            array(
                '{include file=\'footer.tpl\' foo=\'bar\' links=$links}',
                "{% include 'footer.tpl' with {'foo' : 'bar', 'links' : links} %}",
            ),
        );
    }

}
