<?php
namespace ORO\Bundle\IssueBundle\Tests\Unit\Form\Handler;

use ORO\Bundle\IssueBundle\Form\Handler\IssueApiHandler;

class IssueApiHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->handler = new IssueApiHandler($this->form, $this->request, $this->manager);
    }
}
