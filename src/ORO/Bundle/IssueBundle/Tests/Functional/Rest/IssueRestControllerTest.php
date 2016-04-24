<?php

namespace ORO\Bundle\IssueBundle\Bundle\Tests\Functional;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 * @dbReindex
 */
class IssueRestControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());
    }

    /**
     * Test REST Create
     *
     */
    public function testCreate()
    {
        $priority = $this->client->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('OROIssueBundle:Priority')
            ->findOneBy([]);
        $user = $this->client->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('OroUserBundle:User')
            ->findOneBy([]);

        $this->client->request(
            'POST',
            $this->getUrl('api_post_issue'),
            ['issue' => [
                'code' => 'TI-0010',
                'summary' => 'Test Issue',
                'description' => 'Test Issue',
                'type' => 'Bug',
                'priority' => $priority->getId(),
                'reporter' => $user,
                'assignee' => $user
                ]
            ]
        );
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 201);
        return $issue['id'];
    }

    /**
     * Test REST Get
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testGet($id)
    {
        $this->client->request('GET', $this->getUrl('api_get_issue', ['id' => $id]));
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);
        $this->assertEquals('TI-0010', $issue['code']);
    }

    /**
     * Test REST Update
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testUpdate($id)
    {
        $this
            ->client
            ->request(
                'PUT',
                $this->getUrl('api_put_issue', ['id' => $id]),
                ['issue' =>['summary' => 'Test summary 2']]
            );
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);
        $this->client->request('GET', $this->getUrl('api_issue_view', ['id' => $id]));
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);
        $this->assertEquals($issue['summary'], 'Test summary 2');
    }

    /**
     * Test REST Delete
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testDelete($id)
    {
        $this->client->request('DELETE', $this->getUrl('api_delete_issue', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);
        $this->client->request('GET', $this->getUrl('api_get_issue', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 404);
    }
}
