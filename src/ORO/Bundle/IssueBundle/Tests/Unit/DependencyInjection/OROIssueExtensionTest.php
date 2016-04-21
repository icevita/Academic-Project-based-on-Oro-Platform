<?php

namespace ORO\Bundle\IssueBundle\Bundle\Tests\Unit\DependencyInjection;

use ORO\Bundle\IssueBundle\DependencyInjection\OROIssueExtension;

class OroAddressExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $extension = new OROIssueExtension();
        $configs = array();
        $isCalled = false;
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $container->expects($this->any())
            ->method('setParameter')
            ->will(
                $this->returnCallback(
                    function ($name, $value) use (&$isCalled) {
                        if ($name == 'issue.entity.class' && $value == 'ORO\Bundle\IssueBundle\Entity\Issue') {
                            $isCalled = true;
                        }
                    }
                )
            );

        $extension->load($configs, $container);

        $this->assertTrue($isCalled);
    }
}
