<?php

namespace ORO\Bundle\IssueBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
{
    /**
     * @Route(
     *      "/issues_by_status/chart/{widget}",
     *      name="dashboard_issues_by_status_chart",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("OROIssueBundle:Dashboard:issuesByStatus.html.twig")
     */
    public function issuesStatusAction($widget)
    {
        $items = $this->getDoctrine()
            ->getRepository('OROIssueBundle:Issue')
            ->getIssuesByStatus();
        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
            ->setArrayData($items)
            ->setOptions(
                [
                    'name'        => 'bar_chart',
                    'data_schema' => [
                        'label' => ['field_name' => 'label'],
                        'value' => [
                            'field_name' => 'issues'
                        ]
                    ],
                ]
            )
            ->getView();

        return $widgetAttr;
    }

    /**
     * @Route("/issues_widget_grid/{widget}",
     *      name="dashboard_issues_widget_grid",
     *      requirements={"widget"="[\w-]+"})
     * @Template("OROIssueBundle:Dashboard:issues.html.twig")
     *
     * @param $widget
     * @return array $widgetAttr
     */
    public function issueAction($widget)
    {
        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['user'] = $this->getUser();

        return $widgetAttr;
    }
}
