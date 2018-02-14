<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Egf\Ancient\AbstractController;
use App\Entity;

/**
 * Class DashboardController
 */
class DashboardController extends AbstractController {
	
	/**
	 * Dashboard of admin.
	 * @return RedirectResponse|array
	 *
	 * RouteName: app_admin_dashboard_index
	 * @Route("/admin")
	 * Template
	 */
	public function indexAction() {
		return $this->redirectToRoute('app_admin_simpleshop_order_list');
	}
	
}
