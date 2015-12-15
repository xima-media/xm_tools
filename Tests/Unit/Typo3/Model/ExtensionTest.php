<?php

namespace Xima\XmTools\Tests\Unit\TYPO3\Extension;

use Xima\XmTools\Classes\Typo3\Model\Extension;

/**
 * The Api facade test.
 *
 * @author Wolfram Eberius <woe@xima.de>
 *
 */
class ExtensionManagerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * Fixture.
     *
     * @var \Xima\XmTools\Classes\Typo3\Model\Extension
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = new Extension();
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function testExtensionManager()
    {
        $this->assertSame(
            $this->subject->getJsRelPath(),
            'Resources/Public/js/'
        );
    }
}
