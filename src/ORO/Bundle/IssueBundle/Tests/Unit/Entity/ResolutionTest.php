<?php
namespace ORO\Bundle\IssueBundle\Tests\Unit\Entity;

use ORO\Bundle\IssueBundle\Entity\Resolution;

class ResolutionTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $obj = new Resolution();
        $obj->setName('Obsolete');
        $this->assertEquals('Obsolete', $obj->getName());
    }

    public function testToString()
    {
        $obj = new Resolution();
        $this->assertEmpty((string)$obj);
        $obj->setName('Obsolete');
        $this->assertEquals($obj->__toString(), 'Obsolete');
    }

}
