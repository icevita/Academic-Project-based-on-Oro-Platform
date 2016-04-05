<?php

namespace ORO\Bundle\IssueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Oro\Bundle\IssueBundle\Entity\IssuePriority;
use Oro\Bundle\IssueBundle\Entity\IssueResolution;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route(name="issue_index")
     * @Acl(
     *      id="issue_view",
     *      type="entity",
     *      class="OROIssueBundle:Issue",
     *      permission="VIEW"
     * )
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'entity_class' => $this->container->getParameter('issue.entity.issue.class')
        );
    }

    /**
     * @Route("/create", name="issue_create")
     * @Acl(
     *      id="issue_create",
     *      type="entity",
     *      class="OROIssueBundle:Issue",
     *      permission="CREATE"
     * )
     * @Template("OROIssueBundle:Issue:update.html.twig")
     */
    public function createAction()
    {
        $issue = new Issue();
        $issue->setReporter($this->getUser());
        $assigneeId = $request->query->get('assigneeId');
        if ($assigneeId && $assignee = $this->getDoctrine()->getRepository('OroUserBundle:User')->find($assigneeId)) {
            $issue->setAssignee($assignee);
        }
        $result = $this->update($issue);
        if ($request->query->get('_wid')) {
            $result['formAction'] = $this->generateUrl('bug_tracker.issue_create');
        }
        return $result;


        $issue = new Issue();
        $parentId = $this->get('request_stack')->getCurrentRequest()->get('id', 0);
        if ((int)$parentId > 0) {
            $parent = $this
                ->getDoctrine()
                ->getRepository('OroAcademicIssueBundle:Issue')
                ->findOneBy(['id' => $parentId, 'type' => 'Story']);
            $issue
                ->setReporter($this->getUser())
                ->setAssignee($this->getUser())
                ->setParent($parent)
                ->setType('Subtask');
            $formAction = $this->get('oro_entity.routing_helper')
                ->generateUrlByRequest(
                    'oroacademic_subissue_create',
                    $this->get('request_stack')->getCurrentRequest(),
                    ['id' => $parentId]
                );
        } else {
            $formAction = $this->get('oro_entity.routing_helper')
                ->generateUrlByRequest(
                    'oroacademic_issue_create',
                    $this->get('request_stack')->getCurrentRequest()
                );
        }
        return $this->formRender($issue, $formAction);
    }
}
