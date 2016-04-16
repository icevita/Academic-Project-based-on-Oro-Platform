<?php
namespace ORO\Bundle\IssueBundle\Controller\Api\Soap;

use Symfony\Component\Form\FormInterface;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Controller\Api\Soap\SoapController;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;

class IssueController extends SoapController
{
    /**
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->container->get('issue.manager.api');
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->container->get('issue.form.api');
    }

    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        return $this->container->get('form.handler.api_issue');
    }

    /**
     * @Soap\Method("getIssues")
     * @Soap\Param("page", phpType="int")
     * @Soap\Param("limit", phpType="int")
     * @Soap\Result(phpType = "ORO\Bundle\IssueBundle\Entity\IssueSoap[]")
     * @AclAncestor("issue_view")
     * @param int $page
     * @param int $limit
     * @return mixed|\Oro\Bundle\SoapBundle\Entity\SoapEntityInterface|\Traversable
     */
    public function cgetAction($page = 1, $limit = 10)
    {
        return $this->handleGetListRequest($page, $limit);
    }

    /**
     * @Soap\Method("getIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Result(phpType = "ORO\Bundle\IssueBundle\Entity\IssueSoap")
     * @AclAncestor("issue_view")
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }

    /**
     * @Soap\Method("createIssue")
     * @Soap\Param("issue", phpType = "ORO\Bundle\IssueBundle\Entity\IssueSoap")
     * @Soap\Result(phpType = "int")
     * @AclAncestor("issue_create")
     */
    public function createAction($issue)
    {
        return $this->handleCreateRequest();
    }

    /**
     * @Soap\Method("updateIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Param("issue", phpType = "ORO\Bundle\IssueBundle\Entity\IssueSoap")
     * @Soap\Result(phpType = "boolean")
     * @AclAncestor("issue_update")
     */
    public function updateAction($id, $issue)
    {
        return $this->handleUpdateRequest($id);
    }

    /**
     * @Soap\Method("deleteIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Result(phpType = "boolean")
     * @AclAncestor("issue_delete")
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }
}
