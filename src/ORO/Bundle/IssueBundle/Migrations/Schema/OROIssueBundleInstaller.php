<?php

namespace OroB2B\Bundle\FallbackBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;

class OROIssueBundleInstaller implements
    Installation,
    NoteExtensionAwareInterface
{

    /** @var NoteExtension */
    protected $noteExtension;

    /** @var ActivityExtension */
    protected $activityExtension;

    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->addIssueBundleData($schema);

        $this->addIssueNoteData($schema);

        $this->addIssueEmailActivityData($schema);
    }

    /**
     * Create all tables for IssueBundle
     *
     * @param Schema $schema
     */
    protected function addIssueBundleData(Schema $schema)
    {
        $table = $schema->createTable('issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('workflow_item_id', 'integer', ['notnull' => false]);
        $table->addColumn('resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_step_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('code', 'string', ['length' => 25]);
        $table->addColumn('description', 'text', ['notnull' => false,'length' => 255]);
        $table->addColumn('issue_type', 'string', ['length' => 25]);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updatedAt', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['code'], 'UNIQ_35B1ECC777153098');
        $table->addUniqueIndex(['workflow_item_id'], 'UNIQ_12AD233E1023C4EE');
        $table->addIndex(['priority_id'], 'IDX_12AD233E497B19F9', []);
        $table->addIndex(['resolution_id'], 'IDX_12AD233E12A1C43A', []);
        $table->addIndex(['reporter_id'], 'IDX_12AD233EE1CFE6F5', []);
        $table->addIndex(['organization_id'], 'IDX_12AD233E32C8A3DE', []);
        $table->addIndex(['assignee_id'], 'IDX_12AD233E59EC7D60', []);
        $table->addIndex(['parent_id'], 'IDX_12AD233E727ACA70', []);
        $table->addIndex(['workflow_step_id'], 'IDX_12AD233E71FE882C', []);


        $table = $schema->createTable('issue_collaborators');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_93B721895E7AA58C', []);
        $table->addIndex(['user_id'], 'IDX_93B72189A76ED395', []);


        $table = $schema->createTable('issue_related');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('related_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'related_id']);
        $table->addIndex(['issue_id'], 'IDX_C5AF35715E7AA58C', []);
        $table->addIndex(['related_id'], 'IDX_C5AF35714162C001', []);

        $table = $schema->createTable('priority');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('priority', 'integer', []);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'UNIQ_62A6DC275E237E06');

        $table = $schema->createTable('resolution');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'UNIQ_FDD30F8A5E237E06');

        $table = $schema->getTable('issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_item'),
            ['workflow_item_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('resolution'),
            ['resolution_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('priority'),
            ['priority_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['assignee_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_step'),
            ['workflow_step_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('issue'),
            ['parent_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['reporter_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );

        $table = $schema->getTable('issue_collaborators');
        $table->addForeignKeyConstraint(
            $schema->getTable('issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );

        $table = $schema->getTable('issue_related');
        $table->addForeignKeyConstraint(
            $schema->getTable('issue'),
            ['related_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function setNoteExtension(NoteExtension $noteExtension)
    {
        $this->noteExtension = $noteExtension;
    }

    /**
     * Add issue note data
     *
     * @param Schema $schema
     */
    protected function addIssueNoteData(Schema $schema)
    {
        $this->noteExtension->addNoteAssociation($schema, 'issue');
    }


    /**
     * Sets the ActivityExtension
     *
     * @param ActivityExtension $activityExtension
     */
    public function setActivityExtension(ActivityExtension $activityExtension)
    {
        $this->activityExtension = $activityExtension;
    }


    /**
     * Add issue email activity data
     *
     * @param Schema $schema
     */
    protected function addIssueEmailActivityData(Schema $schema)
    {
        $this->activityExtension->addActivityAssociation($schema, 'oro_email', 'issue', true);
    }
}
