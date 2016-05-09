<?php
namespace ORO\Bundle\IssueBundle\Tests\Unit\Form\Type;

use ORO\Bundle\IssueBundle\Form\Type\IssueType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\FormIntegrationTestCase;
use Symfony\Component\Form\Test\FormInterface;

class IssueTypeTest extends FormIntegrationTestCase
{
    /**
     * @var IssueType
     */
    protected $type;

    /**
     * @var string
     */
    protected $expectedName;

    /**
     * Setup test env
     */
    protected function setUp()
    {
        parent::setUp();
        $this->type = new IssueType(['Bug', 'Story', 'Subtask']);
        $this->expectedName = 'issue';
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
        $this->assertEquals($this->expectedName, $this->type->getName());
    }

    /**
     * @dataProvider issueDataProvider
     * @param $formData
     */
    public function testSubmitValidData(
        $formData
    ) {
        $form = $this->factory->create('form', $this->type);

        $this->assertFormOptions($form);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isValid());
        $this->assertTrue($form->isSynchronized());
    }

    /**
     * @return array
     */
    public function issueDataProvider()
    {

        return [
            [
                'formData' => [
                    'code'          => 'CODE-01',
                    'summary'       => 'SUMMARY',
                    'description'   => 'DESCRIPTION',
                    'type'          => 'Story',
                    'priority'      => 'Medium',
                    'assignee'      => 1,
                    'reporter'      => 1,
                    'relatedIssues' => [1, 2],
                ]
            ]
        ];
    }

    public function testGetParent()
    {
        $this->assertEquals('form', $this->type->getParent());
    }

    /**
     * @param $form FormInterface
     */
    protected function assertFormOptions($form)
    {
        $formConfig = $form->getConfig();
        $this->assertEquals('ORO\Bundle\IssueBundle\Form\Type\IssueType', $formConfig->getOption('data_class'));
    }

    protected function getExtensions()
    {
        return array(
            new PreloadedExtension(
                [
                    'translatable_entity' => $this->getMock('Symfony\Component\Translation\TranslatorInterface')
                ],
                []
            ),
        );
    }
}
