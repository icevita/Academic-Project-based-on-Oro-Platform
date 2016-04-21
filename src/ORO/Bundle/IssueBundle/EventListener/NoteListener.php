<?php

namespace ORO\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\NoteBundle\Entity\Note;
use Oro\Bundle\IssueBundle\Entity\Issue;

class NoteListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Note) {
            /** @var Issue $issue */
            $issue = $entity->getTarget();
            $issue->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        }
    }
}
