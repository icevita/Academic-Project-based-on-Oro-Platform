<?php

namespace Oro\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\NoteBundle\Entity\Note;
use Oro\Bundle\IssueBundle\Entity\Issue;

class NoteListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (is_a($entity, Note::class)) {
            /** @var Issue $issue */
            $issue = $entity->getTarget();
            if (is_a($issue, Issue::class)) {
                $issue->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
            }
        }
    }
}
