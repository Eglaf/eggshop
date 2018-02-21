<?php

namespace App\Controller\Admin\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

use App\Egf\Ancient\AbstractController;
use App\Entity\User\User;
use App\Entity\User\Address;
use App\Service\Serializer;
use App\Form\Site\User\AddressType as UserAddressFormType;

/**
 * Class AddressController
 */
class AddressController extends AbstractController {
	
	/** @var TranslatorInterface */
	protected $translator;
	
	/**
	 * AddressController constructor.
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator) {
		$this->translator = $translator;
	}
	
	/**
	 * List of Addresses.
	 * @param Serializer $serializer
	 * @param User       $user
	 * @return array
	 *
	 * RouteName: app_admin_user_address_list
	 * @Route("/admin/user/{user}/address/list")
	 * @Template
	 */
	public function listAction(Serializer $serializer, User $user) {
		$addresses = $this->getDm()->getRepository(Address::class)->findBy(['user' => $user]);
		
		return [
			'user'       => $user,
			'listAsJson' => $serializer->toJson($addresses, ['attributes' => [
				'id', 'title', 'city', 'zipCode', 'street', 'houseNumber',
			]]),
		];
	}
	
	/**
	 * Create a User.
	 * @param User $user
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_user_address_create
	 * @Route("/admin/user/{user}/address/create", requirements={"user"="\d+|_id_"})
	 * @Template("admin/user/address/form.html.twig")
	 */
	public function createAction(User $user) {
		$address = (new Address())
			->setUser($user);
		
		return $this->form($address);
	}
	
	/**
	 * Update a User User.
	 * @param Address $address
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_admin_user_address_update
	 * @Route("/admin/user/address/update/{address}", requirements={"address"="\d+|_id_"})
	 * @Template("admin/user/address/form.html.twig")
	 */
	public function updateAction(Address $address) {
		return $this->form($address);
	}
	
	/**
	 * Generate form view to User User.
	 * @param Address $address
	 * @return array|RedirectResponse
	 */
	protected function form(Address $address) {
		$form = $this->createForm(UserAddressFormType::class, $address, [
			'showSubmit' => TRUE,
		]);
		$form->handleRequest($this->getRq());
		
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDm()->persist($address);
			$this->getDm()->flush();
			
			$this->addFlash('success', $this->translator->trans('message.success.saved'));
			
			return $this->redirectToRoute('app_admin_user_address_list', [
				'user' => $address->getUser()->getId(),
			]);
		}
		
		return [
			'user'     => $address->getUser(),
			'address'  => $address,
			'formView' => $form->createView(),
		];
	}
	
}
