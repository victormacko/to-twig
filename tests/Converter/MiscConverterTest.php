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

use toTwig\Converter\MiscConverter;
use toTwig\FrameworkTestCase;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class MiscConverterTest extends FrameworkTestCase
{
    /**
     * @var MiscConverter
     */
    protected $converter;

    public function setUp()
    {
        $this->converter = new MiscConverter();
    }
    /**
     * @covers \toTwig\Converter\MiscConverter::convert
     * @dataProvider provider
     */
    public function testThatMiscIsConverted($smarty,$twig)
    {
        $this->assertSame($twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );
       
    }

    public function provider()
    {
        return array(
                array( 
                    '{ldelim}','{'
                    ),
                array(
                    '{rdelim}','}'
                    ),
                array(
                    '{literal}','{% verbatim %}'
                    ),
                array(
                    '{/literal}','{% endverbatim %}'
                    )
            );
    }

}
