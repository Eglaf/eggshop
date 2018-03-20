<?php

namespace App\Service;

use Symfony\Component\Translation\TranslatorInterface;

use App\Entity\SimpleShop\Order;

/**
 * Class NewOrderInMail
 */
class NewOrderInMail {
	
	/** @var ConfigReader */
	protected $configReader;
	
	/** @var \Twig_Environment */
	protected $twig;
	
	/** @var \Swift_Mailer */
	protected $mailer;
	
	/** @var TranslatorInterface */
	protected $translator;
	
	/**
	 * NewOrderInMail constructor.
	 * @param ConfigReader        $configReader
	 * @param \Twig_Environment   $twig
	 * @param \Swift_Mailer       $mailer
	 * @param TranslatorInterface $translator
	 */
	public function __construct(ConfigReader $configReader, \Twig_Environment $twig, \Swift_Mailer $mailer, TranslatorInterface $translator) {
		$this->configReader = $configReader;
		$this->twig         = $twig;
		$this->mailer       = $mailer;
		$this->translator   = $translator;
	}
	
	/**
	 * Send an email to the admin with order data.
	 * @param Order $order
	 * @return int
	 */
	public function sendMailWithOrder(Order $order) {
		$message = (new \Swift_Message($this->translator->trans('email.new_order.subject')))
			->setFrom($this->configReader->get('sender-email'))
			->setTo($this->configReader->get('admin-email'))
			->setBody($this->twig->render('email/new_order_to_admin.html.twig', [
				'order'                => $order,
				'deliveryPrice'        => $this->configReader->get('order-delivery-price'),
				'noDeliveryPriceAbove' => $this->configReader->get('order-no-delivery-price-above-sum'),
			]), 'text/html')
			->addPart($this->twig->render('email/new_order_to_admin.txt.twig', [
				'order'                => $order,
				'deliveryPrice'        => $this->configReader->get('order-delivery-price'),
				'noDeliveryPriceAbove' => $this->configReader->get('order-no-delivery-price-above-sum'),
			]), 'text/plain');
		
		return $this->mailer->send($message);
	}
	
}