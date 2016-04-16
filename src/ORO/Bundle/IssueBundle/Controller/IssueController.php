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
        $assigneeId = $request->query->get('assigneeId');
        if ($assigneeId && $assignee = $this->getDoctrine()->getRepository('OroUserBundle:User')->find($assigneeId)) {
            $issue->setAssignee($assignee);
        }

        return $this->update($issue, $request);
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
        return $this->update($issue, $request);
    }

    private function update(Issue $issue, Request $request)
    {
        $issueHandler = $this->get('form.handler.issue');
        if ($issueHandler->process($issue, $this->getUser())) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($issue);
            $entityManager->flush();

            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'issue_update',
                    'parameters' => array('id' => $issue->getId()),
                ),
                array('route' => 'issue_index'),
                $issue
            );
        }

        $form = $issueHandler->getForm();

        return array(
            'entity' => $issue,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/", name="issue_index")
     * @Template
     * @Acl(
     *      id="issue_index",
     *      type="entity",
     *      class="OROIssueBundle:Issue",
     *      permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('issue.entity.class'),
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
        return array('issue' => $issue);
    }

    /**
     * @Route("/user/{userId}", name="user_issues", requirements={"userId"="\d+"})
     * @AclAncestor("issue_view")
     * @Template
     * @param int $userId
     * @return array
     */
    public function issuesAction($userId)
    {
        return ['userId' => $userId];
    }
}
