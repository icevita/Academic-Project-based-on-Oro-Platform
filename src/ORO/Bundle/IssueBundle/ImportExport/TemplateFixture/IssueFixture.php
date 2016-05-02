<?php
namespace ORO\Bundle\IssueBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use ORO\Bundle\IssueBundle\Entity\Issue;
use ORO\Bundle\IssueBundle\Entity\Priority;

class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'ORO\Bundle\IssueBundle\Entity\Issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('TI-0000');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Issue();
    }

    /**
     * @param string $key
     * @param Issue $entity
     */
    public function fillEntityData($key, $entity)
    {
        if ($key == 'TI-0000') {
            $userRepository = $this->templateManager->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');
            $user = $userRepository->getEntity('John Doo'); //why not John Doe ...
            $organizationRepository = $this->templateManager
                ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization');
            $priorityRepository     = $this->templateManager
                ->getEntityRepository('ORO\Bundle\IssueBundle\Entity\Priority');

            $entity->setCode('TI-0000');
            $entity->setSummary('Task');
            $entity->setDescription('Description');
            $entity->setType('Task');
            $entity->setPriority($priorityRepository->getEntity('High'));
            $entity->setReporter($user);
            $entity->setAssignee($user);
            $entity->setOrganization($organizationRepository->getEntity('default'));
            $entity->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
            $entity->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));

            return;
        }
        parent::fillEntityData($key, $entity);
    }
}
