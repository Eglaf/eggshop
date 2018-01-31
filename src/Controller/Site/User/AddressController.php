<?php

namespace App\Controller\Site\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Service\Serializer;
use App\Controller\AbstractEggShopController;
use App\Entity\User\Address;
use App\Form\Site\User\AddressType as AddressFormType;

/**
 * Class AddressController
 */
class AddressController extends AbstractEggShopController {
	
	/**
	 * List of addresses.
	 * @param Serializer $serializer
	 * @return array
	 *
	 * RouteName: app_site_user_address_list
	 * @Route("/user/cimek")
	 * @Template
	 */
	public function listAction(Serializer $serializer) {
		$addresses = $this->getUserAddressRepository()->findBy(['user' => $this->getUser()]);
		
		return [
			'listAsJson' => $serializer->toJson($addresses),
		];
	}
	
	/**
	 * Create an Address.
	 * @return array
	 *
	 * RouteName: app_site_user_address_create
	 * @Route("/user/uj-cim")
	 * @Template("site/user/address/form.html.twig")
	 */
	public function createAction() {
		return $this->addressForm((new Address())->setUser($this->getUser()));
	}
	
	/**
	 * Update an Address.
	 * @param Address $address
	 * @return array
	 *
	 * RouteName: app_site_user_address_update
	 * @Route("/user/cim-szerkesztes/{address}", requirements={"address"="\d+|_id_"})
	 * @Template("site/user/address/form.html.twig")
	 */
	public function updateAction(Address $address) {
		if ($address->getUser() !== $this->getUser()) {
			throw new \Exception('Address and User are not related!');
		}
		
		return $this->addressForm($address);
	}
	
	/**
	 * The form view and submit.
	 * @param Address $address
	 * @return array|RedirectResponse
	 */
	public function addressForm(Address $address) {
		// Create form.
		$form = $this->createForm(AddressFormType::class, $address, [
			'showSubmit' => TRUE,
		]);
		$form->handleRequest($this->getRq());
		
		// Save form.
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDm()->persist($address);
			$this->getDm()->flush();
			
			return $this->redirectToRoute('app_site_user_address_list');
		}
		
		// Form view.
		return [
			'address'  => $address,
			'formView' => $form->createView(),
		];
	}
	
}