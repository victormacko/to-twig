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

use toTwig\Converter\VariableConverter;
use toTwig\FrameworkTestCase;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class VariableConverterTest extends FrameworkTestCase
{
    /**
     * @var VariableConverter
     */
    protected $converter;

    public function setUp()
    {
        $this->converter = new VariableConverter();
    }

    /**
     * @covers       \toTwig\Converter\VariableConverter::convert
     * @dataProvider provider
     */
    public function testThatVariableIsConverted($smarty, $twig)
    {
        $this->assertSame(
            $twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );

    }

    public function provider()
    {
        return array(
            array(
                '{$var}',
                '{{ var }}',
            ),
            array(
                '{$contacts.fax}',
                '{{ contacts.fax }}',
            ),
            array(
                '{$contacts[0]}',
                '{{ contacts[0] }}',
            ),
            array(
                '{$contacts[2][0]}',
                '{{ contacts[2][0] }}',
            ),
            array(
                '{$person->name}',
                '{{ person.name }}',
            ),
            array(
                '{$person->getName()}',
                '{{ person.getName }}',
            ),
            array(
                '{$person->setName($person->getName())}',
                '{{ person.setName(person.getName) }}'
            ),
        );
    }

}
