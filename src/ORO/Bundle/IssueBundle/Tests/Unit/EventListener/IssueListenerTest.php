<?php

namespace ORO\Bundle\IssueBundle\Tests\Unit\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ORO\Bundle\IssueBundle\Entity\Issue;
use ORO\Bundle\IssueBundle\EventListener\IssueListener;
use Oro\Bundle\NavigationBundle\Content\TagGeneratorChain;
use Oro\Bundle\NavigationBundle\Content\TopicSender;

class IssueListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueListener
     */
    protected $listener;

    /**
     * @var Issue|\PHPUnit_Framework_MockObject_MockObject
     */
    private $issue;

    /**
     * @var LifecycleEventArgs|\PHPUnit_Framework_MockObject_MockObject
     */
    private $lifecycleEventArgs;

    /**
     * @var TopicSender|\PHPUnit_Framework_MockObject_MockObject
     */
    private $sender;

    /**
     * @var TagGeneratorChain|\PHPUnit_Framework_MockObject_MockObject
     */
    private $generator;

    protected function setUp()
    {
        $this->sender = $this
            ->getMockBuilder('Oro\Bundle\NavigationBundle\Content\TopicSender')
            ->disableOriginalConstructor()
            ->getMock();

        $this->listener = new IssueListener(
            $this->sender
        );

        $this->issue = $this->getMockBuilder('ORO\Bundle\IssueBundle\Entity\Issue')
            ->disableOriginalConstructor()
            ->getMock();

        $this->lifecycleEventArgs = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $this->generator = $this
            ->getMockBuilder('Oro\Bundle\NavigationBundle\Content\TagGeneratorChain')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        unset($this->listener, $this->lifecycleEventArgs, $this->issue, $this->sender);
    }
    
    /**
     * Test postUpdate
     */
    public function testPostUpdateValid()
    {
        $this->sender->expects($this->once())
            ->method('getGenerator')
            ->willReturn($this->generator);
        $this->generator->expects($this->once())
            ->method('generate')
            ->willReturn([]);
        $this->sender->expects($this->once())
            ->method('send');

        $this->listener->postUpdate($this->issue, $this->lifecycleEventArgs);
    }

}
