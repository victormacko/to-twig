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

use toTwig\Converter\CommentConverter;
use toTwig\FrameworkTestCase;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class CommentConverterTest extends FrameworkTestCase
{
    protected $converter;

    public function setUp()
    {
        $this->converter = new CommentConverter();
    }
    /**
     * @covers \toTwig\Converter\CommentConverter::convert
     * @dataProvider provider
     */
    public function testThatIfIsConverted($smarty,$twig)
    {

        // Test the above cases
        $this->assertSame($twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );
       
    }

    public function provider()
    {
        return array(
                array( 
                        '{* foo *}',
                        '{# foo #}'
                    )
            );
    }

}
