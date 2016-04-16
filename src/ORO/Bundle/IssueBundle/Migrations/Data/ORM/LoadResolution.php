<?php
namespace ORO\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use ORO\Bundle\IssueBundle\Entity\Resolution;

class LoadResolution extends AbstractFixture
{
    /**
     * @var array
     */
    protected $resolutions = ['Cannot Reproduce', 'Duplicate', 'Obsolete', 'Fixed'];


    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->resolutions as $resolutionName) {

            $resolutions = $manager->getRepository('OROIssueBundle:Resolution')
                ->findBy([
                    'name' => $resolutionName
                ]);

            if (!count($resolutions)) {
                $entity = new Resolution();
                $entity->setName($resolutionName);
                $manager->persist($entity);
            }
        }
        $manager->flush();
    }
}
