<?php
/**
 * @author Gamesh
 */

namespace toTwig;


class FrameworkTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\SplFileInfo
     */
    protected function getFileMock()
    {
        return new \SplFileInfo(__FILE__);
    }
}