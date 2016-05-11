<?php

namespace ORO\Bundle\IssueBundle\Tests\Unit\Handler;

use ORO\Bundle\IssueBundle\Form\Handler\IssueHandler;
use ORO\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\UserBundle\Entity\User;

class IssueHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $form;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $om;

    /**
     * @var User|\PHPUnit_Framework_MockObject_MockObject
     */
    private $user;

    /**
     * @var Issue|\PHPUnit_Framework_MockObject_MockObject
     */
    private $issue;

    /**
     * @var IssueHandler
     */
    private $handler;

    protected function setUp()
    {
        $this->form = $this->getMock('Symfony\Component\Form\Test\FormInterface');
        $this->request = $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->issue = $this->getMockBuilder('ORO\Bundle\IssueBundle\Entity\Issue')
            ->disableOriginalConstructor()
            ->getMock();
        $this->user = $this->getMock('Oro\Bundle\UserBundle\Entity\User');

        $this->handler = new IssueHandler($this->form, $this->request, $this->om);
    }

    public function testGoodRequest()
    {
        $this->form->expects($this->once())
            ->method('setData');

        $this->request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('POST'));

        $this->form->expects($this->once())
            ->method('submit');

        $this->form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue('true'));

        $this->om->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($this->issue));

        $this->om->expects($this->once())
            ->method('flush');

        $this->assertTrue($this->handler->process($this->issue));
    }

    public function testBadRequest()
    {
        $this->form->expects($this->once())
            ->method('setData');

        $this->request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('GET'));

        $this->form->expects($this->never())
            ->method('submit');
        $this->form->expects($this->never())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->om->expects($this->never())
            ->method('persist');
        $this->om->expects($this->never())
            ->method('flush');

        $this->assertFalse($this->handler->process($this->issue));
    }

    public function testNotValidForm()
    {
        $this->form->expects($this->once())
            ->method('setData');

        $this->request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('POST'));

        $this->form->expects($this->once())
            ->method('submit');
        $this->form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->om->expects($this->never())
            ->method('persist');
        $this->om->expects($this->never())
            ->method('flush');


        $this->assertFalse($this->handler->process($this->issue));
    }
}
