<?php

namespace ORO\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\NavigationBundle\Content\TopicSender;
use ORO\Bundle\IssueBundle\Entity\Issue;

class IssueListener
{
    private $sender;

    /**
     * @param TopicSender $sender
     */
    public function __construct(TopicSender $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Send Issue collection tag to publisher
     *
     * @param Issue $issue
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(Issue $issue, LifecycleEventArgs $event)
    {
        $data = ['name' => 'entity_issue_changes'];
        $this->sender->send($this->sender->getGenerator()->generate($data));
    }
}
