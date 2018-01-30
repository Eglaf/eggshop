<?php

namespace App\Controller\Site\User;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Controller\AbstractEggShopController;

/**
 * Class ProfileController
 *
 * todo remove username? email only? or not?
 */
class ProfileController extends AbstractEggShopController {
	
	/**
	 * Main profile page.
	 *
	 * RouteName: app_site_user_profile_main
	 * @Route("/user/profile")
	 * @Template
	 */
	public function mainAction() {
		return [];
	}
	
	
	public function earlierOrdersAction() {
	
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Login change                                               **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	public function userUpdateAction() { // userName & email
	
	}
	
	public function passwordUpdateAction() {
	
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Addresses                                                  **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	public function addressListAction() {
	
	}
	
	public function createAddressAction() {
	
	}
	
	public function updateAddressAction() {
	
	}
	
	public function addressForm() {
	
	}
	
}