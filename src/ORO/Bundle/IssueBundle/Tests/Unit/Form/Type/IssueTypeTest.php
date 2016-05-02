<?php
namespace ORO\Bundle\IssueBundle\Tests\Unit\Form\Type;

use ORO\Bundle\IssueBundle\Form\Type\IssueType;

class IssueTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueType
     */
    protected $type;

    /**
     * Setup test env
     */
    protected function setUp()
    {
        $this->type = new IssueType(['bug','story']);
    }

    public function testBuildForm()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $builder->expects($this->exactly(8))
            ->method('add')
            ->will($this->returnSelf());

        $builder->expects($this->at(0))
            ->method('add')
            ->with('code', 'text');

        $builder->expects($this->at(1))
            ->method('add')
            ->with('summary', 'text');

        $builder->expects($this->at(2))
            ->method('add')
            ->with('type', 'choice');

        $builder->expects($this->at(3))
            ->method('add')
            ->with('description', 'textarea');

        $builder->expects($this->at(4))
            ->method('add')
            ->with('priority', 'translatable_entity');

        $builder->expects($this->at(5))
            ->method('add')
            ->with('assignee', 'oro_user_select');

        $builder->expects($this->at(6))
            ->method('add')
            ->with('relatedIssues', 'translatable_entity');

        $builder->expects($this->at(7))
            ->method('add')
            ->with('resolution', 'translatable_entity');


        $this->type->buildForm($builder, []);
    }

    public function testGetName()
    {
        $this->assertEquals('issue', $this->type->getName());
    }
}
