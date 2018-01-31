<?php

namespace App\Controller\Site\User;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Service\Serializer;
use App\Controller\AbstractEggShopController;

/**
 * Class AddressController
 */
class AddressController extends AbstractEggShopController {
	
	public function addressListAction(Serializer $serializer) {
		$addresses = $this->getUserAddressRepository()->findBy(['user' => $this->getUser()]);
		
		return [
			'listAsJson' => $serializer->toJson($addresses/*, ['attributes' => ['id', 'label', 'active', 'price', 'category' => ['label']]]*/),
		];
	}
	
	public function createAddressAction() {
	
	}
	
	public function updateAddressAction() {
	
	}
	
	public function addressForm() {
	
	}
	
}