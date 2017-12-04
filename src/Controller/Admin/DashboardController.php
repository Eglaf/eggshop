<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DashboardController
 */
class DashboardController extends Controller {
	
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
