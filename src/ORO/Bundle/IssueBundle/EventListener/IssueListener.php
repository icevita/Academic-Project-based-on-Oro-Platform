<?php

namespace ORO\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\NavigationBundle\Content\TopicSender;
use ORO\Bundle\IssueBundle\Entity\Issue;

class IssueListener
{
    /**
     * Send Issue collection tag to publisher
     *
     * @param Issue $issue
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(Issue $issue, LifecycleEventArgs $event)
    {
        $data = ['name' => 'entity_issue_changes'];
        /** @var TopicSender $sender */
        $sender = $this->get('oro_navigation.content.topic_sender');
        $sender->send($sender->getGenerator()->generate($data));
    }
}
