<?php

namespace ORO\Bundle\IssueBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteResource("Issue")
 * @NamePrefix("api_")
 */
class IssueController extends RestController implements ClassResourceInterface
{
    /**
     * Get issue Manager
     *
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->get('issue.manager.api');
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->get('issue.form.api');
    }

    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        return $this->get('form.handler.api_issue');
    }

    /**
     * REST Get list of issues
     *
     * @QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Page number, starting from 1. Defaults to 1."
     * )
     * @QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Number of items per page. defaults to 10."
     * )
     * @ApiDoc(
     *      description="Get all issue items",
     *      resource=true,
     *       filters={
     *          {"name"="page", "dataType"="integer"},
     *          {"name"="limit", "dataType"="integer"}
     *      }
     * )
     * @AclAncestor("issue_view")
     * @param Request $request
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $page = (int)$request->get('page', 1);
        $limit = (int)$request->get('limit', self::ITEMS_PER_PAGE);
        $criteria = $this->getFilterCriteria($this->getSupportedQueryParameters(__FUNCTION__));

        return $this->handleGetListRequest($page, $limit, $criteria);
    }

    /**
     * REST get issue
     *
     * @param string $id
     *
     * @ApiDoc(
     *      description="Get issue item",
     *      resource=true
     * )
     * @AclAncestor("issue_view")
     * @return Response
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }

    /**
     * REST PUT
     *
     * @param int $id Issue item id
     *
     * @ApiDoc(
     *      description="Update issue",
     *      resource=true
     * )
     * @AclAncestor("issue_update")
     * @return Response
     */
    public function putAction($id)
    {
        return $this->handleUpdateRequest($id);
    }

    /**
     * Create new issue
     *
     * @ApiDoc(
     *      description="Create new issue",
     *      resource=true
     * )
     * @AclAncestor("issue_create")
     * @return Response
     */
    public function postAction()
    {
        return $this->handleCreateRequest();
    }

    /**
     * REST DELETE
     *
     * @param int $id
     * @return Response
     *
     * @ApiDoc(
     *      description="Delete Issue",
     *      resource=true
     * )
     * @Acl(
     *      id="delete_issue",
     *      type="entity",
     *      permission="DELETE",
     *      class="OROIssueBundle:Issue"
     * )
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function transformEntityField($field, &$value)
    {
        switch ($field) {
            case 'priority':
                if ($value) {
                    $value = $value->getName();
                }
                break;
            case 'parent':
            case 'reporter':
            case 'assignee':
            case 'workflowItem':
            case 'workflowStep':
                if ($value) {
                    $value = $value->getId();
                }
                break;
            case 'children':
            case 'relatedIssues':
                if ($value && is_object($value)) {
                    $arr = [];
                    foreach ($value as $v) {
                        $arr[] = $v->getId();
                    }
                    $value = $arr;
                } else {
                    $value = null;
                }
                break;
            default:
                parent::transformEntityField($field, $value);
        }
    }
}
