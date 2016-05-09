<?php

namespace ORO\Bundle\IssueBundle\Bundle\Tests\Functional;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 * @dbReindex
 */
class DashboardControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());
    }

    public function testIssueByStatusWidget()
    {
        $this->client->request(
            'GET',
            $this->getUrl(
                'dashboard_issues_by_status_chart',
                ['widget' => 'issues_by_status']
            )
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $content = $result->getContent();
        $this->assertContains('Issues by Status', $content);
        $this->assertContains('Date range', $content);
    }

    public function testIssueWidget()
    {
        $this->client->request(
            'GET',
            $this->getUrl(
                'dashboard_issues_widget_grid',
                ['widget' => 'issues_widget']
            )
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $content = $result->getContent();
        $this->assertContains('Recent Issues', $content);
        $this->assertContains('View All', $content);
    }
 
}

