<?php

namespace App\Controller\Admin\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

use App\Egf\Ancient\AbstractController;
use App\Entity\User\User;
use App\Service\Serializer;
use App\Form\Admin\User\UserType as UserFormType;

/**
 * Class UserController
 *
 * todo Password change
 */
class UserController extends AbstractController {
	
	/**
	 * List of Users.
	 *
	 * RouteName: app_admin_content_user_list
	 * @Route("/admin/user/list")
	 * @Template
	 *
	 * @param Serializer $serializer Service to convert entities into json.
	 * @return array
	 */
	public function listAction(Serializer $serializer) {
		$users = $this->getDm()->getRepository(User::class)->findAll();
		
		return [
			'listAsJson' => $serializer->toJson($users, ['attributes' => [
				'id', 'name', 'email', 'active', 'role',
			]]),
		];
	}
	
	/**
	 * Create a User.
	 *
	 * RouteName: app_admin_content_user_create
	 * @Route("/admin/user/create")
	 * @Template("admin/user/user/form.html.twig")
	 *
	 * @return array|RedirectResponse
	 */
	public function createAction() {
		return $this->form(new User());
	}
	
	/**
	 * Update a User User.
	 *
	 * RouteName: app_admin_content_user_update
	 * @Route("/admin/user/update/{user}", requirements={"user"="\d+|_id_"})
	 * @Template("admin/user/user/form.html.twig")
	 *
	 * @param User $user
	 * @return array|RedirectResponse
	 */
	public function updateAction(User $user) {
		return $this->form($user);
	}
	
	/**
	 * Generate form view to User User.
	 * @param User $user
	 * @return array|RedirectResponse
	 */
	protected function form(User $user) {
		$form = $this->createForm(UserFormType::class, $user);
		$form->handleRequest($this->getRq());
		
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDm()->persist($user);
			$this->getDm()->flush();
			
			return $this->redirectToRoute('app_admin_user_user_list');
		}
		
		return [
			'user'     => $user,
			'formView' => $form->createView(),
		];
	}
	
}