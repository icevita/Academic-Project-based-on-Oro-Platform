<?php

namespace ORO\Bundle\IssueBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class OROIssueBundle implements Migration
{
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
        /** Tables generation **/
        $this->createIssueTable($schema);
        $this->createIssueCollaboratorsTable($schema);
        $this->createIssueRelatedTable($schema);
        $this->createPriorityTable($schema);
        $this->createResolutionTable($schema);
        /** Foreign keys generation **/
        $this->addIssueForeignKeys($schema);
        $this->addIssueCollaboratorsForeignKeys($schema);
        $this->addIssueRelatedForeignKeys($schema);
    }

    /**
     * Create issue table
     *
     * @param Schema $schema
     */
    protected function createIssueTable(Schema $schema)
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
    }

    /**
     * Create issue_collaborators table
     *
     * @param Schema $schema
     */
    protected function createIssueCollaboratorsTable(Schema $schema)
    {
        $table = $schema->createTable('issue_collaborators');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_93B721895E7AA58C', []);
        $table->addIndex(['user_id'], 'IDX_93B72189A76ED395', []);
    }

    /**
     * Create issue_related table
     *
     * @param Schema $schema
     */
    protected function createIssueRelatedTable(Schema $schema)
    {
        $table = $schema->createTable('issue_related');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('related_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'related_id']);
        $table->addIndex(['issue_id'], 'IDX_C5AF35715E7AA58C', []);
        $table->addIndex(['related_id'], 'IDX_C5AF35714162C001', []);
    }

    /**
     * Create priority table
     *
     * @param Schema $schema
     */
    protected function createPriorityTable(Schema $schema)
    {
        $table = $schema->createTable('priority');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('priority', 'integer', []);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'UNIQ_62A6DC275E237E06');
    }

    /**
     * Create resolution table
     *
     * @param Schema $schema
     */
    protected function createResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('resolution');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'UNIQ_FDD30F8A5E237E06');
    }

    /**
     * Add issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addIssueForeignKeys(Schema $schema)
    {
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
    }

    /**
     * Add issue_collaborators foreign keys.
     *
     * @param Schema $schema
     */
    protected function addIssueCollaboratorsForeignKeys(Schema $schema)
    {
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
    }

    /**
     * Add issue_related foreign keys.
     *
     * @param Schema $schema
     */
    protected function addIssueRelatedForeignKeys(Schema $schema)
    {
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
}
