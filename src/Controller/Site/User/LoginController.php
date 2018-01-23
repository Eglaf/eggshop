<?php

namespace App\Controller\Site\User;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Controller\AbstractEggShopController;

/**
 * Class LoginController
 *
 * The logout route is configured in the "config/routes.yml" file.
 */
class LoginController extends AbstractEggShopController {
	
	/**
	 * Login form and submit.
	 *
	 * RouteName: app_site_user_login_login
	 * @Route("/login")
	 * @Template
	 *
	 * @param AuthenticationUtils $authUtils
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function loginAction(AuthenticationUtils $authUtils) {
		$error        = $authUtils->getLastAuthenticationError();
		$lastUsername = $authUtils->getLastUsername();
		
		return [
			'last_username' => $lastUsername,
			'error'         => $error,
		];
	}
	
}
