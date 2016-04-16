<?php
namespace ORO\Bundle\IssueBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueApiType extends IssueType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'      => 'ORO\Bundle\IssueBundle\Entity\Issue',
                'csrf_protection' => false
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'api_issue';
    }
}
