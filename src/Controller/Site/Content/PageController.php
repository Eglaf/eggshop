<?php

namespace App\Controller\Site\Content;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Controller\AbstractEggShopController;
use App\Entity;
use App\Service\Content\TextEntityFinder;

/**
 * Class PageController
 *
 * todo https://developers.facebook.com/docs/plugins/like-button
 * todo https://developers.google.com/+/web/+1button/
 */
class PageController extends AbstractEggShopController {
	
	/**
	 * Index page... it uses the TextPage template.
	 *
	 * RouteName: app_site_content_page_index
	 * @Route("/")
	 * @Template("site/content/page/text.html.twig")
	 *
	 * @param TextEntityFinder $textFinder
	 * @return array
	 */
	public function indexAction(TextEntityFinder $textFinder) {
		return [
			'textEntity' => $textFinder->get('index'),
		];
	}
	
	/**
	 * Text page.
	 * This Route has to be configured in routes.yaml file, otherwise it overrides other annotation routes like /login or /registration.
	 *
	 * RouteName: app_site_content_page_text
	 * @Template
	 *
	 * @param string $code
	 * @param TextEntityFinder $textFinder
	 * @return array
	 */
	public function textAction($code, TextEntityFinder $textFinder) {
		return [
			'textEntity' => $textFinder->get($code),
		];
		
	}
	
}