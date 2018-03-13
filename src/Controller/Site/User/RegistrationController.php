<?php

namespace App\Controller\Site\User;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use App\Controller\AbstractEggShopController;
use App\Entity\User\User;
use App\Form\Site\User\RegistrationType;
use App\Service\Content\TextEntityFinder;
use App\Service\ConfigReader;
use App\Egf\Util;

/**
 * Class RegistrationController
 */
class RegistrationController extends AbstractEggShopController {
	
	/** @var TextEntityFinder */
	protected $textFinder;
	
	/** @var ConfigReader */
	protected $configReader;
	
	/** @var UserPasswordEncoderInterface */
	protected $passwordEncoder;
	
	/** @var TranslatorInterface */
	protected $translator;
	
	/** @var \Swift_Mailer */
	protected $mailer;
	
	/**
	 * RegistrationController constructor.
	 * @param TextEntityFinder             $textFinder
	 * @param ConfigReader                 $configReader
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param TranslatorInterface          $translator
	 * @param \Swift_Mailer                $mailer
	 */
	public function __construct(TextEntityFinder $textFinder, ConfigReader $configReader, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator, \Swift_Mailer $mailer) {
		$this->textFinder      = $textFinder;
		$this->configReader    = $configReader;
		$this->passwordEncoder = $passwordEncoder;
		$this->translator      = $translator;
		$this->mailer          = $mailer;
	}
	
	/**
	 * Show registration form with submit.
	 * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * RouteName: app_site_user_registration_registration
	 * @Route("/regisztracio")
	 * @Template
	 */
	public function registrationAction() {
		$user = new User();
		$form = $this->createForm(RegistrationType::class, $user, [
			'method' => 'POST',
		]);
		$form->handleRequest($this->getRq());
		
		if ($form->isSubmitted() && $form->isValid()) {
			$password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
			$hash     = strtolower(Util::getRandomString(16));
			
			$user
				->setPassword($password)
				->setRole('ROLE_USER')
				->setActive(FALSE)
				->setActivationHash($hash);
			
			$this->getDm()->persist($user);
			
			try {
				$this->getDm()->flush();
				
				if ($this->sendConfirmEmail($user)) {
					return $this->redirectToRoute('app_site_user_registration_emailsent');
				}
				else {
					$this->addFlash('error', $this->translator->trans('message.error.email_sending_problem'));
				}
			}
			catch (UniqueConstraintViolationException $ex) {
				$this->addFlash('warning', $this->translator->trans('message.error.email_taken'));
			}
		}
		
		return [
			'form'             => $form->createView(),
			'beforeTextEntity' => $this->textFinder->get('registration-form-before'),
			'afterTextEntity'  => $this->textFinder->get('registration-form-after'),
		];
	}
	
	/**
	 * Warn user about the sent eMail.
	 * @return array
	 *
	 * RouteName: app_site_user_registration_emailsent
	 * @Route("/regisztralo-email-kikuldve")
	 * @Template
	 */
	public function emailSentAction() {
		return [
			'textEntity' => $this->textFinder->get('registration-confirm-email-sent'),
		];
	}
	
	/**
	 * User confirmed.
	 * @param string $hash
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * RouteName: app_site_user_registration_confirm
	 * @Route("/email-megerosites/{hash}")
	 */
	public function confirmAction($hash) {
		$user = $this->getUserUserRepository()->findOneBy([
			'activationHash' => $hash,
		]);
		
		if ($user instanceof User) {
			$user
				->setActivationHash(NULL)
				->setActive(TRUE);
			
			$this->getDm()->flush();
			
			$this->addFlash('success', $this->translator->trans('message.success.user_confirmed'));
			
			return $this->redirectToRoute('app_site_user_login_login');
		}
		else {
			return $this->redirectToRoute('app_site_content_page_index');
		}
	}
	
	/**
	 * Send email with confirm link to the newly registered user.
	 * @param User $user
	 * @return int Count of sent emails.
	 */
	private function sendConfirmEmail(User $user) {
		$confirmUrl = $this->generateUrl('app_site_user_registration_confirm', [
			'hash' => $user->getActivationHash(),
		], UrlGeneratorInterface::ABSOLUTE_URL);
		
		$message = (new \Swift_Message($this->translator->trans('email.registration.subject')))
			->setFrom($this->configReader->get('sender-email'))
			->setTo($user->getEmail())
			->setBody($this->renderView('email/registration.html.twig', [
				'confirmUrl' => $confirmUrl,
			]), 'text/html')
			->addPart($this->renderView('email/registration.txt.twig', [
				'confirmUrl' => $confirmUrl,
			]), 'text/plain');
		
		return $this->mailer->send($message);
	}
	
}