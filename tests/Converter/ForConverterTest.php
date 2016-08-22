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

use toTwig\Converter\ForConverter;
use toTwig\FrameworkTestCase;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class ForConverterTest extends FrameworkTestCase
{
    /**
     * @var ForConverter
     */
    protected $converter;

    public function setUp()
    {
        $this->converter = new ForConverter();
    }

    /**
     * @covers       \toTwig\Converter\ForConverter::convert
     * @dataProvider provider
     */
    public function testThatForIsConverted($smarty, $twig)
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
                "{foreach \$myColors as \$color}\nfoo\n{/foreach}",
                "{% for color in myColors %}\nfoo\n{% endfor %}",
            ),
            array(
                "{foreach \$contact as \$key => \$value}\nfoo{/foreach}",
                "{% for key, value in contact %}\nfoo{% endfor %}",
            ),
            array(
                "{foreach name=outer item=contact from=\$contacts}\nfoo{/foreach}",
                "{% for contact in contacts %}\nfoo{% endfor %}",
            ),
            array(
                "{foreach key=key item=item from=\$contact}\nfoo\n{foreachelse}bar{/foreach}",
                "{% for key, item in contact %}\nfoo\n{% else %}bar{% endfor %}",
            ),
        );
    }

}
