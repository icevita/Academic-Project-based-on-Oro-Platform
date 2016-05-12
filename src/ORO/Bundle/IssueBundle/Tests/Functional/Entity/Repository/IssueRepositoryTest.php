<?php
namespace ORO\Bundle\IssueBundle\Tests\Functional\Entity\Repository;


use Doctrine\ORM\EntityManager;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 */
class IssueRepositoryTest extends WebTestCase
{
    public function testGetIssuesByStatus()
    {
        $this->initClient([], $this->generateBasicAuthHeader());
        $this->loadFixtures(
            [
                'ORO\Bundle\IssueBundle\Migrations\Data\Demo\ORM\LoadIssueDemoData',
            ]
        );
        /** @var EntityManager $em */
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        $statuses = $em
            ->getRepository('OROIssueBundle:Issue')->getIssuesByStatus();

        foreach ($statuses as $status) {
            $this->assertArrayHasKey('label', $status);
            if ($status['label'] == 'Open') {
                $cnt = 8;
            } else {
                $cnt = 0;
            }
            $this->assertEquals($cnt, $status['issues_count']);
        }
    }
}
