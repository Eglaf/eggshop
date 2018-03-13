<?php

namespace App\Controller\Site\User;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Util;
use App\Service\ConfigReader;
use App\Entity\User\User;
use App\Controller\AbstractEggShopController;
use App\Form\Site\User\ForgottenPasswordType;
use App\Form\Site\User\NewPasswordType;

/**
 * Class ForgottenPasswordController
 */
class ForgottenPasswordController extends AbstractEggShopController {
	
	/** @var ConfigReader */
	protected $configReader;
	
	/** @var \Swift_Mailer */
	protected $mailer;
	
	/** @var UserPasswordEncoderInterface */
	protected $passwordEncoder;
	
	/** @var TranslatorInterface */
	protected $translator;
	
	/**
	 * RegistrationController constructor.
	 * @param ConfigReader                 $configReader
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param TranslatorInterface          $translator
	 * @param \Swift_Mailer                $mailer
	 */
	public function __construct(ConfigReader $configReader, \Swift_Mailer $mailer, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator) {
		$this->configReader    = $configReader;
		$this->mailer          = $mailer;
		$this->passwordEncoder = $passwordEncoder;
		$this->translator      = $translator;
	}
	
	/**
	 * Ask for email address then send email.
	 *
	 * RouteName: app_site_user_forgottenpassword_askemailform
	 * @Route("/elfelejtett-jelszo")
	 * @Template
	 */
	public function askEmailFormAction() {
		$form = $this->createForm(ForgottenPasswordType::class, NULL);
		$form->handleRequest($this->getRq());
		
		if ($form->isSubmitted() && $form->isValid()) {
			$user = $this->getUserUserRepository()->findOneBy([
				'email' => $form->get('email')->getData(),
			]);
			
			if ($user instanceof User) {
				$user->setForgottenPasswordHash(strtolower(Util::getRandomString(16)));
				
				$this->getDm()->flush();
				
				if ($this->sendForgottenPasswordEmail($user)) {
					$this->addFlash('success', $this->translator->trans('message.success.email_sent'));
				}
				else {
					$this->addFlash('error', $this->translator->trans('message.error.email_sending_problem'));
				}
			}
			else {
				// todo ?
			}
		}
		
		return [
			'form' => $form->createView(),
		];
	}
	
	/**
	 * Ask for the new password of user.
	 * @param string $hash
	 * @return array
	 *
	 * RouteName: app_site_user_forgottenpassword_newpasswordform
	 * @Route("/uj-jelszo/{hash}")
	 * @Template
	 */
	public function newPasswordFormAction($hash) {
		$user = $this->getUserUserRepository()->findOneBy(['forgottenPasswordHash' => $hash]);
		
		if ( ! ($user instanceof User)) {
			$this->addFlash('error', $this->translator->trans('message.error.user_not_found'));
			
			$this->redirectToRoute('app_site_user_forgottenpassword_askemailform');
		}
		
		$form = $this->createForm(NewPasswordType::class, $user);
		$form->handleRequest($this->getRq());
		
		if ($form->isSubmitted() && $form->isValid()) {
			$password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
			$user
				->setPassword($password)
				->setForgottenPasswordHash(NULL);
			
			$this->getDm()->flush();
			
			$this->addFlash('success', $this->translator->trans('message.success.saved'));
			
			$this->redirectToRoute('app_site_user_login_login');
		}
		
		return [
			'form' => $form->createView(),
		];
	}
	
	/**
	 * @param User $user
	 * @return int
	 */
	protected function sendForgottenPasswordEmail(User $user) {
		$newPasswordUrl = $this->generateUrl('app_site_user_forgottenpassword_newpasswordform', [
			'hash' => $user->getForgottenPasswordHash(),
		], UrlGeneratorInterface::ABSOLUTE_URL);
		
		$message = (new \Swift_Message($this->translator->trans('email.forgotten_password.subject')))
			->setFrom($this->configReader->get('sender-email'))
			->setTo($user->getEmail())
			->setBody($this->renderView('email/forgotten_password.html.twig', [
				'newPasswordUrl' => $newPasswordUrl,
			]), 'text/html')
			->addPart($this->renderView('email/forgotten_password.txt.twig', [
				'newPasswordUrl' => $newPasswordUrl,
			]), 'text/plain');
		
		return $this->mailer->send($message);
	}
}