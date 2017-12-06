<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Ancient\AbstractController;
use App\Entity;

/**
 * Class DashboardController
 */
class DashboardController extends AbstractController {
	
	/**
	 * Dashboard of admin.
	 *
	 * RouteName: app_admin_dashboard_index
	 * @Route("/admin")
	 * @Template
	 *
	 * @return array
	 */
	public function indexAction() {
		$number = mt_rand(0, 100);
		
		return [
			'number' => $number,
		];
	}
	
}
