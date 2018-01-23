<?php

namespace App\Controller\Site\User;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Controller\AbstractEggShopController;
use App\Entity\User\User;
use App\Form\Site\User\RegistrationType;
use App\Service\Content\TextEntityFinder;
use App\Egf\Util;

/**
 * Class RegistrationController
 *
 * https://symfony.com/doc/current/security.html
 * https://symfony.com/doc/current/security/entity_provider.html todo
 * https://symfony.com/doc/current/doctrine/registration_form.html todo
 */
class RegistrationController extends AbstractEggShopController {
	
	/**
	 * Show registration form with submit.
	 *
	 * RouteName: app_site_user_registration_basicform
	 * @Route("/regisztracio")
	 * @Template
	 *
	 * @param TextEntityFinder             $textFinder
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function registrationAction(TextEntityFinder $textFinder, UserPasswordEncoderInterface $passwordEncoder) {
		$user = new User();
		$form = $this->createForm(RegistrationType::class, $user, [
			'method' => 'POST',
		]);
		$form->handleRequest($this->getRq());
		
		if ($form->isSubmitted() && $form->isValid()) {
			$password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
			$user
				->setPassword($password)
				->setRole('ROLE_USER')
				->setActive(FALSE)
				->setActivationHash(strtolower(Util::getRandomString(16)));
			
			$this->getDm()->persist($user);
			$this->getDm()->flush();
			
			// TODO send email!
			
			return $this->redirectToRoute('app_site_user_registration_emailsent');
		}
		
		return [
			'form'             => $form->createView(),
			'beforeTextEntity' => $textFinder->get('registration-form-before'),
			'afterTextEntity'  => $textFinder->get('registration-form-after'),
		];
	}
	
	/**
	 * Warn user about the sent eMail.
	 *
	 * RouteName: app_site_user_registration_emailsent
	 * @Route("/regisztralo-email-kikuldve")
	 * @Template
	 *
	 * @param TextEntityFinder $textFinder
	 * @return array
	 */
	public function emailSentAction(TextEntityFinder $textFinder) {
		return [
			'textEntity' => $textFinder->get('registration-confirm-email-sent'),
		];
	}
	
	/**
	 * User confirmed... redirect to login... with some flash message.
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function confirmAction() {
		// todo set to active
		
		return $this->redirectToRoute('app_site_content_page_index');
	}
	
}