<?php

namespace App\Controller\Site\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Service\Serializer;
use App\Service\ConfigReader;
use App\Controller\AbstractEggShopController;
use App\Form\Site\User\UserUpdateType;
use App\Entity\SimpleShop\Order;
use App\Entity\User\User;

/**
 * Class ProfileController
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
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Earlier orders                                             **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * List of earlier orders.
	 * @param Serializer $serializer
	 * @return array
	 *
	 * RouteName: app_site_user_profile_earlierorders
	 * @Route("/user/korabbi-rendelesek")
	 * @Template
	 */
	public function earlierOrdersAction(Serializer $serializer) {
		$orders = $this->getSimpleShopOrderRepository()->findExtendedByUser($this->getUser());
		
		return [
			'listAsJson' => $serializer->toJson($orders, [
				'attributes' => ['id', 'comment', 'date', /*'priceSum',*/ 'status' => ['label']],
			], [
				'date' => ['date'],
			]),
		];
	}
	
	/**
	 * Details of an earlier order.
	 * @param Order        $order
	 * @param ConfigReader $configReader
	 * @return array
	 *
	 * RouteName: app_site_user_profile_earlierorderdetails
	 * @Route("/user/korabbi-rendeles-reszletek/{order}", requirements={"order"="\d+|_id_"})
	 * @Template
	 */
	public function earlierOrderDetailsAction(Order $order, ConfigReader $configReader) {
		if ($order->getUser() !== $this->getUser()) {
			throw new \Exception('You cannot see that order!');
		}
		
		return [
			'order'                => $order,
			'deliveryPrice'        => $configReader->get('order-delivery-price'),
			'noDeliveryPriceAbove' => $configReader->get('order-no-delivery-price-above-sum'),
		];
	}
	
	
	/**************************************************************************************************************************************************************
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 * Update user data                                            **         **         **         **         **         **         **         **         **         **
	 *                                                          **         **         **         **         **         **         **         **         **         **
	 *************************************************************************************************************************************************************/
	
	/**
	 * Details of an earlier order.
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param TranslatorInterface          $translator
	 * @return array|RedirectResponse
	 *
	 * RouteName: app_site_user_profile_userupdateform
	 * @Route("/user/adatok-modositas")
	 * @Template
	 */
	public function userUpdateFormAction(UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator) {
		/** @var User $user Current user. */
		$user     = $this->getUser();
		$oldEmail = $user->getEmail();
		
		// Create form.
		$form = $this->createForm(UserUpdateType::class, $user);
		$form->handleRequest($this->getRq());
		
		// If eMail was changed.
		if ($oldEmail !== $form->get('email')->getData()) {
			// Check if given eMail is used by another user.
			$existingUser = $this->getUserUserRepository()->findOneBy(['email' => $form->get('email')->getData()]);
			if ($existingUser instanceof User && $existingUser !== $user) {
				// Set back to old eMail, so the login data in session won't be invalid.
				$this->getUser()->setEmail($oldEmail);
				
				$this->addFlash('error', $translator->trans('message.error.email_taken'));
				
				return $this->redirectToRoute('app_site_user_profile_userupdateform');
			}
		}
		
		// Check and save form.
		if ($form->isSubmitted() && $form->isValid()) {
			// Check old password.
			if ($passwordEncoder->isPasswordValid($user, $form->get('oldPassword')->getData())) {
				// Password changing.
				if ($form->get('plainPassword')->getData()) {
					$newPassword = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
					$user->setPassword($newPassword);
				}
				
				$this->getDm()->flush();
				
				$this->addFlash('success', $translator->trans('message.success.user_changes_saved'));
			}
			else {
				$form->addError(new FormError($translator->trans('message.error.old_password_invalid')));
			}
		}
		
		// Form view.
		return [
			"formView" => $form->createView(),
		];
	}
	
}