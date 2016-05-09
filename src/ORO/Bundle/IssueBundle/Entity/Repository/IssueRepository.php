<?php

namespace ORO\Bundle\IssueBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class IssueRepository extends EntityRepository
{
    public function getIssuesByStatus()
    {
        return $this->createQueryBuilder('issue')
            ->select('count(issue.id) as issues_count, status.label')
            ->leftJoin('issue.workflowStep', 'status')
            ->groupBy('status.label')
            ->orderBy('status.stepOrder')
            ->getQuery()
            ->getArrayResult();
    }
}
