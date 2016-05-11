<?php

namespace ORO\Bundle\IssueBundle\Controller;

use ORO\Bundle\IssueBundle\Entity\Issue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Oro\Bundle\NavigationBundle\Annotation\TitleTemplate;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/create", name="issue_create")
     * @Template("OROIssueBundle:Issue:update.html.twig")
     * @TitleTemplate("oro.issue.menu.issue_create")
     * @Acl(
     *     id="issue_create",
     *     type="entity",
     *     class="OROIssueBundle:Issue",
     *     permission="CREATE"
     * )
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        /** @var Issue $issue */
        $issue = new Issue();
        $issue->setReporter($this->getUser());
        $assigneeId = $request->query->get('assignee');
        if ($assigneeId && $assignee = $this->getDoctrine()->getRepository('OroUserBundle:User')->find($assigneeId)) {
            $issue->setAssignee($assignee);
        }

        if ($parentId = $request->query->getInt('parent')) {
            $issueTypes = $this->container->getParameter('issue.types');
            $parent = $this->getDoctrine()->getRepository('OROIssueBundle:Issue')
                ->findOneBy([
                    'id'   => $parentId,
                    'type' => $issueTypes['Story']
                ]);
            if ($parent) {
                $issue->setParent($parent)->setType($issueTypes['Subtask']);
            }
        }
        $formAction = $this->get('oro_entity.routing_helper')->generateUrlByRequest('issue_create', $request);


        return $this->update($issue, $formAction);
    }

    /**
     * @Route("/update/{id}", name="issue_update", requirements={"id":"\d+"}, defaults={"id":0})
     * @Template()
     * @TitleTemplate("oro.issue.menu.issue_update")
     * @Acl(
     *     id="issue_update",
     *     type="entity",
     *     class="OROIssueBundle:Issue",
     *     permission="EDIT"
     * )
     * @param Issue $issue
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Issue $issue, Request $request)
    {
        $formAction = $this->get('oro_entity.routing_helper')->generateUrlByRequest('issue_update', $request);

        return $this->update($issue, $formAction);
    }

    private function update(Issue $issue, $formAction)
    {
        $issueHandler = $this->get('form.handler.issue');
        if ($issueHandler->process($issue)) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('oro.issue.saved_message')
            );

            return $this->get('oro_ui.router')->redirectAfterSave(
                [
                    'route'      => 'issue_update',
                    'parameters' => ['id' => $issue->getId()],
                ],
                ['route'      => 'issue_view',
                 'parameters' => ['id' => $issue->getId()]
                ],
                $issue
            );
        }

        $form = $issueHandler->getForm();

        return [
            'entity'     => $issue,
            'form'       => $form->createView(),
            'formAction' => $formAction
        ];
    }

    /**
     * @Route("/", name="issue_index")
     * @Template
     * @Acl(
     *      id="issue_view",
     *      type="entity",
     *      class="OROIssueBundle:Issue",
     *      permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('issue.entity.class'),
            'gridName'     => 'issues-grid'
        ];
    }

    /**
     * @Route("/{id}", name="issue_view", requirements={"id"="\d+"})
     * @Template
     * @TitleTemplate("oro.issue.menu.issue_view")
     * @AclAncestor("issue_view")
     * @param Issue $issue
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        $issueTypes = $this->container->getParameter('issue.types');
        return [
            'entity' => $issue,
            'user' => $this->getUser(),
            'issue_types' => $issueTypes
        ];
    }

}
