<?php

namespace ORO\IssueBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\NoteBundle\Entity\Note;
use Oro\Bundle\UserBundle\Entity\User;
use ORO\Bundle\IssueBundle\Entity\Issue;

class LoadIssueData extends AbstractFixture
{
    /**
     * @var array
     */
    protected $data = [
        [
            'code'     => 'TT-0001',
            'summary'  => 'Story 1',
            'type'     => 'Story',
            'priority' => 'Low'
        ],
        [
            'code'     => 'TT-0002',
            'summary'  => 'Bug 1',
            'type'     => 'Bug',
            'priority' => 'High',
            'parent'   => 'TT-0001'
        ],
        [
            'code'     => 'TT-0003',
            'summary'  => 'Bug 2',
            'priority' => 'Low',
            'type'     => 'Bug',
            'related'  => ['TT-0001', 'TT-0002']
        ],
        [
            'code'     => 'TT-0004',
            'summary'  => 'Task 1',
            'priority' => 'Medium',
            'type'     => 'Task',
        ],
        [
            'code'     => 'TT-0005',
            'summary'  => 'Task 2',
            'priority' => 'High',
            'type'     => 'Task'
        ],
        [
            'code'     => 'TT-0006',
            'summary'  => 'Task 3',
            'priority' => 'High',
            'type'     => 'Task'
        ],
        [
            'code'     => 'TT-0007',
            'summary'  => 'Sub-task(TT-0001)',
            'type'     => 'Subtask',
            'priority' => 'High',
            'parent'   => 'TT-0001',
            'related'  => ['TT-0005']
        ],
        [
            'code'     => 'TT-0008',
            'summary'  => 'Sub-task(TT-0001)',
            'type'     => 'Subtask',
            'priority' => 'Low',
            'parent'   => 'TT-0001'
        ],
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $issueRepo = $manager->getRepository('ORO\Bundle\IssueBundle\Entity\Issue');
        $priorityRepo = $manager->getRepository('ORO\Bundle\IssueBundle\Entity\Priority');
        $users = $manager->getRepository('OroUserBundle:User')->findBy(['enabled' => 1]);
        $users_count = count($users);
        $organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();

        foreach ($this->data as $data) {
            $user = $users[rand(0, $users_count - 1)];
            $asignee = $users[rand(0, $users_count - 1)];
            $reporter = $users[rand(0, $users_count - 1)];
            $issue = new Issue();
            $issue->setCode($data['code'])
                ->setSummary($data['summary'])
                ->setDescription($data['summary'])
                ->setPriority($priorityRepo->findOneBy(['name' => $data['priority']]))
                ->setReporter($reporter)
                ->setAssignee($asignee)
                ->setOrganization($organization)
                ->setType($data['type'])
                ->addCollaborator($user);

            if ($data['parent']) {
                $parent = $issueRepo->findOneBy(['code' => $data['parent']]);
                $issue->setParent($parent);
            }

            foreach ($data['related'] as $related) {
                $relatedIssue = $issueRepo->findOneBy(['code' => $related]);
                $issue->addRelatedIssue($relatedIssue);
            }

            $manager->persist($issue);
            foreach ($data['notes'] as $noteMessage) {
                $note = new Note();
                $note->setMessage($noteMessage)
                    ->setOrganization($organization)
                    ->setOwner($user)
                    ->setTarget($issue);
                $manager->persist($note);
            }

            $manager->flush();
        }
    }

}
