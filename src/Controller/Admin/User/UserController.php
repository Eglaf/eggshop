<?php

namespace App\Controller\Admin\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

use App\Egf\Ancient\AbstractController;
use App\Entity\User\User;
use App\Service\Serializer;
use App\Form\Admin\User\UserType as UserFormType;

/**
 * Class UserController
 */
class UserController extends AbstractController {
	
	/**
	 * List of Users.
	 * @param Serializer $serializer Service to convert entities into json.
	 * @return array
	 *
	 * RouteName: app_admin_content_user_list
	 * @Route("/admin/user/list")
	 * @Template
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
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_content_user_create
	 * @Route("/admin/user/create")
	 * @Template("admin/user/user/form.html.twig")
	 */
	public function createAction(TranslatorInterface $translator) {
		return $this->form(new User(), $translator);
	}
	
	/**
	 * Update a User User.
	 * @param User $user
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_content_user_update
	 * @Route("/admin/user/update/{user}", requirements={"user"="\d+|_id_"})
	 * @Template("admin/user/user/form.html.twig")
	 */
	public function updateAction(User $user, TranslatorInterface $translator) {
		return $this->form($user, $translator);
	}
	
	/**
	 * Generate form view to User User.
	 * @param User $user
	 * @param TranslatorInterface $translator
	 * @return array|RedirectResponse
	 */
	protected function form(User $user, TranslatorInterface $translator) {
		$form = $this->createForm(UserFormType::class, $user);
		$form->handleRequest($this->getRq());
		
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDm()->persist($user);
			$this->getDm()->flush();
			
			$this->addFlash('success', $translator ->trans('message.success.saved'));
			
			return $this->redirectToRoute('app_admin_user_user_list');
		}
		
		return [
			'user'     => $user,
			'formView' => $form->createView(),
		];
	}
	
}