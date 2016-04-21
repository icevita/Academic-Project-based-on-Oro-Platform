<?php
namespace ORO\Bundle\IssueBundle\Tests\Unit\Entity;

use ORO\Bundle\IssueBundle\Entity\Priority;

class PriorityTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $obj = new Priority();
        $obj->setName('Low');
        $obj->setPriority(1);
        $this->assertEquals('Low', $obj->getName());
        $this->assertEquals(1, $obj->getPriority());
    }
    public function testToString()
    {
        $obj = new Priority();
        $this->assertEmpty((string)$obj);
        $obj->setName('Low');
        $this->assertEquals($obj->__toString(), 'Low');
    }

}
