<?php
namespace ORO\Bundle\IssueBundle\Tests\Unit\Form\Type;

use ORO\Bundle\IssueBundle\Form\Type\IssueApiType;

class IssueApiTypeTest extends IssueTypeTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->expectedName = 'api_issue';
        $this->type = new IssueApiType(['Bug','Story']);
    }

    /**
     * @dataProvider issueDataProvider
     * @param $formData
     */
    public function testSubmitValidData(
        $formData
    ) {
        parent::testSubmitValidData($formData);
    }

    /**
     * {@inheritdoc}
     */
    protected function assertFormOptions($form)
    {
        $formConfig = $form->getConfig();
        $this->assertEquals('ORO\Bundle\IssueBundle\Form\Type\IssueApiType', $formConfig->getOption('data_class'));
        $this->assertEquals(false, $formConfig->getOption('csrf_protection'));
    }
    
}
