<?php
/**
 * Created by PhpStorm.
 * User: ice_vita
 * Date: 3/23/16
 * Time: 00:14
 */

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
        $qb->select(['COUNT(issue.id) as cnt', 'workflowStep.label'])
            ->from('OroWorkflowBundle:WorkflowStep', 'workflowStep')
            ->leftJoin('workflowStep.definition', 'definition')
            ->leftJoin(
                'AcademicBtsBundle:Issue',
                'issue',
                'WITH',
                'issue.workflowStep = workflowStep AND definition.name = :d_name'
            )
            ->groupBy('workflowStep.label')
            ->orderBy('cnt')
            ->setParameter('d_name', 'issue_flow');
        $arrayResult = $qb->getQuery()->getArrayResult();
        $result = [];
        foreach ($arrayResult as $row) {
            $item = [
                'name' => $row['label'],
                'label' => $row['label'],
                'count' => $row['cnt'],
            ];
            $result[$row['label']] = $item;
        }
        return $result;
    }

}
