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
    }
 
}

