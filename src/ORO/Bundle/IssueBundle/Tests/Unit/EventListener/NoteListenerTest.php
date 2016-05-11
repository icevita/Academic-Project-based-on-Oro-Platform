<?php

namespace ORO\Bundle\IssueBundle\Tests\Unit\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ORO\Bundle\IssueBundle\Entity\Issue;
use ORO\Bundle\IssueBundle\EventListener\NoteListener;
use Oro\Bundle\NoteBundle\Entity\Note;

class NoteListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NoteListener
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
     * @var Note|\PHPUnit_Framework_MockObject_MockObject
     */
    private $note;

    protected function setUp()
    {

        $this->listener = new NoteListener();

        $this->issue = $this->getMockBuilder('ORO\Bundle\IssueBundle\Entity\Issue')
            ->disableOriginalConstructor()
            ->getMock();

        $this->lifecycleEventArgs = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $this->note =  $this->getMockBuilder('Oro\Bundle\NoteBundle\Entity\Note')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        unset($this->listener, $this->lifecycleEventArgs, $this->issue);
    }

    /**
     * Test postPresist
     */
    public function testPostPersistValid()
    {
        $this->lifecycleEventArgs->expects($this->once())
            ->method('getEntity')
            ->willReturn($this->note);
        $this->note->expects($this->once())
            ->method('getTarget')
            ->willReturn($this->issue);
        $this->issue->expects($this->once())
            ->method('setUpdatedAt');

        $this->listener->postPersist($this->lifecycleEventArgs);
    }

}
