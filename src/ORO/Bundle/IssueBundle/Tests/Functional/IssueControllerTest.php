<?php

namespace ORO\Bundle\IssueBundle\Bundle\Tests\Functional;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 * @dbReindex
 */
class IssueControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());
    }

    public function testCreate()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository('OroUserBundle:User')->findOneBy([]);
        $priority = $entityManager->getRepository('OROIssueBundle:Priority')->findOneBy([]);

        $crawler                                  = $this->client->request(
            'GET',
            $this->getUrl('issue_create')
        );
        $form                                     = $crawler->selectButton('Save')->form();
        $form['issue[code]']                      = 'SI-0001';
        $form['issue[summary]']                   = 'Test task';
        $form['issue[description]']               = 'Test task for tests';
        $form['issue[priority]']                  = $priority->getId();
        $form['issue[assignee]']                  = $user->getId();
        $form['issue[reporter]']                  = $user->getId();


        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Issue saved", $crawler->html());

    }

    public function testUpdate()
    {
        $response = $this->client->requestGrid(
            'issues-grid'
        );
        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $crawler = $this->client->request(
            'GET',
            $this->getUrl('issue_update', array('id' => $result['id']))
        );

        $form = $crawler->selectButton('Save and Close')->form();
        $form['issue[code]'] = 'SI-0002';
        $form['issue[summary]'] = 'Summary test';
        $form['issue[description]'] = 'Description test';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Issue saved", $crawler->html());
    }

    public function testView()
    {
        $response = $this->client->requestGrid(
            'issues-grid'
        );
        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $this->client->request(
            'GET',
            $this->getUrl('issue_view', array('id' => $result['id']))
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $content = $result->getContent();
        $this->assertContains('Code', $content);
        $this->assertContains('Assigned To', $content);
        $this->assertContains('Related Issues', $content);
    }

    public function testIndex()
    {
        $this->client->request('GET', $this->getUrl('issue_index'));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $content = $result->getContent();
        $this->assertContains('Issues', $content);
        $this->assertContains('Clear All Filters', $content);
    }
}
