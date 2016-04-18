<?php

namespace Oro\Bundle\IssueBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class IssueRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getIssuesByStatus()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select(['COUNT(issue.id) as count', 'workflowStep.label'])
            ->from('OroWorkflowBundle:WorkflowStep', 'workflowStep')
            ->leftJoin('workflowStep.definition', 'definition')
            ->leftJoin(
                'OROIssueBundle:Issue',
                'issue',
                'WITH',
                'issue.workflowStep = workflowStep AND definition.name = :def_name'
            )
            ->groupBy('workflowStep.label')
            ->orderBy('count')
            ->setParameter('def_name', 'issue_flow');
        $arrayResult = $qb->getQuery()->getArrayResult();
        $result = [];
        foreach ($arrayResult as $row) {
            $item = [
                'name' => $row['label'],
                'label' => $row['label'],
                'count' => $row['count'],
            ];
            $result[$row['label']] = $item;
        }
        return $result;
    }

}
