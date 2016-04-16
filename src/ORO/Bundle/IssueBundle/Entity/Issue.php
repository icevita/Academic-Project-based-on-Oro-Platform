<?php

namespace ORO\Bundle\IssueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use ORO\Bundle\IssueBundle\Model\ExtendIssue;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

/**
 * Issue
 *
 * @ORM\Table(name="issue")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="ORO\Bundle\IssueBundle\Entity\Repository\IssueRepository")
 * @Config(
 *      routeName="issue_index",
 *      routeView="issue_view",
 *      routeCreate="issue_create",
 *      defaultValues={
 *          "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="reporter",
 *              "owner_column_name"="reporter_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "workflow"={
 *              "active_workflow"="issue_workflow"
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "tag"={
 *              "enabled"=true
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "permissions"="All",
 *              "group_name"=""
 *          },
 *          "entity"={
 *              "icon"="icon-file"
 *          },
 *      }
 * )
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Issue extends ExtendIssue implements DatesAwareInterface
{
    const TYPE_BUG = 'Bug';
    const TYPE_SUBTASK = 'Subtask';
    const TYPE_TASK = 'Task';
    const TYPE_STORY = 'Story';

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column type="string", length=255)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=50
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $summary;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=25)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=40
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=60
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="issue_type", type="string", length=25)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=70
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $type;

    /**
     * @var Priority
     *
     * @ORM\ManyToOne(targetEntity="Priority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=90,
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $priority;

    /**
     * @var Resolution
     *
     * @ORM\ManyToOne(targetEntity="Resolution")
     * @ORM\JoinColumn(name="resolution_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=80,
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $resolution;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $reporter;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=30
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $organization;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $assignee;

    /**
     * @var ArrayCollection Issue[]
     *
     * @ORM\ManyToMany(targetEntity="ORO\Bundle\IssueBundle\Entity\Issue")
     * @ORM\JoinTable(name="issue_related",
     *      joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="related_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $relatedIssues;

    /**
     * @var ArrayCollection User[]
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="issue_collaborators",
     *      joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $collaborators;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="childrens")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $parent;

    /**
     * @var ArrayCollection Issue[]
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent")
     */
    protected $childrens;


    /**
     * @ORM\Column(type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="oro.ui.created_at"
     *          }
     *      }
     * )
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="oro.ui.updated_at"
     *          }
     *      }
     * )
     */
    protected $updatedAt;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $workflowStep;


    public function __construct()
    {
        $this->collaborators = new ArrayCollection();
        $this->childrens = new ArrayCollection();
        $this->relatedIssues = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Issue
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Issue
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUpdatedAtSet()
    {
        return $this->updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * @param User $reporter
     * @return Issue
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * @return User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @param User $assignee
     * @return Issue
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * @return Priority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param Priority $priority
     * @return Issue $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return Resolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param Resolution $resolution
     * @return Issue $this
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Issue $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * @param User $user
     *
     * @return Issue
     */
    public function addCollaborator(User $user)
    {
        if (!$this->getCollaborators()->contains($user)) {
            $this->getCollaborators()->add($user);
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return Issue
     */
    public function removeCollaborator(User $user)
    {
        if (!$this->getCollaborators()->contains($user)) {
            $this->getCollaborators()->removeElement($user);
        }

        return $this;
    }

    /**
     * Get children
     *
     * @return ArrayCollection Issue[]
     */
    public function getChildrens()
    {
        return $this->childrens;
    }

    /**
     * Add children
     *
     * @param Issue $children
     * @return Issue
     */
    public function addChild(Issue $children)
    {
        $this->childrens[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Issue $children
     */
    public function removeChild(Issue $children)
    {
        $this->childrens->removeElement($children);
    }


    /**
     * @return Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Issue $parent
     *
     * @return Issue $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }


    /**
     * @param WorkflowItem $workflowItem
     * @return Opportunity
     */
    public function setWorkflowItem($workflowItem)
    {
        $this->workflowItem = $workflowItem;

        return $this;
    }

    /**
     * @return WorkflowItem
     */
    public function getWorkflowItem()
    {
        return $this->workflowItem;
    }

    /**
     * @param WorkflowItem $workflowStep
     * @return Opportunity
     */
    public function setWorkflowStep($workflowStep)
    {
        $this->workflowStep = $workflowStep;

        return $this;
    }

    /**
     * @return WorkflowStep
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
    }

    /**
     * Add relatedIssues
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $relatedIssues
     *
     * @return Issue
     */
    public function setRelatedIssues(ArrayCollection $relatedIssues)
    {
        $this->relatedIssues = $relatedIssues;

        return $this;
    }

    /**
     * Get relatedIssues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelatedIssues()
    {
        return $this->relatedIssues;
    }

    /**
     * Remove relatedIssue
     *
     * @param Issue $relatedIssue
     */
    public function removeRelatedIssue(Issue $relatedIssue)
    {
        $this->relatedIssues->removeElement($relatedIssue);
    }

    /**
     * Add Related Issue
     *
     * @param Issue $issue
     */
    public function addRelatedIssue(Issue $issue)
    {
        $relatedIssues = $this->getRelatedIssues();
        if (!$relatedIssues->contains($issue)) {
            $this->relatedIssues->add($issue);
        }
    }

    /**
     * @param Organization|null $organization
     * @return $this
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

}
