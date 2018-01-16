<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 */
class SecurityController extends AbstractEggShopController {
	
	/**
	 * Login form and submit.
	 *
	 * RouteName: app_security_login
	 * @Route("/login")
	 *
	 * @param AuthenticationUtils $authUtils
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function loginAction(AuthenticationUtils $authUtils) {
		$error = $authUtils->getLastAuthenticationError();
		
		$lastUsername = $authUtils->getLastUsername();
		
		return $this->render('security/login.html.twig', [
			'last_username' => $lastUsername,
			'error'         => $error,
		]);
	}
	
}
