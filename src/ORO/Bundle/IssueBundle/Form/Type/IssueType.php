<?php


namespace ORO\Bundle\IssueBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use ORO\Bundle\IssueBundle\Entity\Issue;

class IssueType extends AbstractType
{
    private $types;

    public function __construct(array $types)
    {
        unset($types['Subtask']);
        $this->types = $types;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'ORO\Bundle\IssueBundle\Entity\Issue',
            ]
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'issue';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'code',
                'text',
                [
                    'required' => true,
                    'label' => 'oro.issue.code.label'
                ]
            )
            ->add(
                'summary',
                'text',
                [
                    'required' => true,
                    'label' => 'oro.issue.summary.label'
                ]
            )
            ->add(
                'type',
                'choice',
                [
                    'multiple' => false,
                    'label' => 'oro.issue.type.label',
                    'choices' => $this->types
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => false,
                    'label' => 'oro.issue.description.label'
                ]
            )
            ->add(
                'priority',
                'translatable_entity',
                [
                    'required' => true,
                    'label' => 'oro.issue.priority.label',
                    'class' => 'ORO\Bundle\IssueBundle\Entity\Priority'
                ]
            )
            ->add(
                'assignee',
                'oro_user_select',
                [
                    'required' => true,
                    'label' => 'oro.issue.assignee.label'
                ]
            )
            ->add(
                'relatedIssues',
                'translatable_entity',
                [
                    'required' => false,
                    'multiple' => true,
                    'label' => 'oro.issue.related.label',
                    'class' => 'ORO\Bundle\IssueBundle\Entity\Issue'
                ]
            )
            ->add(
                'resolution',
                'translatable_entity',
                [
                    'required' => false,
                    'label' => 'oro.issue.resolution.label',
                    'class' => 'ORO\Bundle\IssueBundle\Entity\Resolution'
                ]
            );
    }
}
