<?php

namespace App\Controller\Site\Content;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Controller\AbstractEggShopController;
use App\Service\Content\PageEntityFinder;

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
	 * @param PageEntityFinder $pageFinder
	 * @return array
	 */
	public function indexAction(PageEntityFinder $pageFinder) {
		return [
			'textEntity' => $pageFinder->get('index'),
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
	 * @param PageEntityFinder $pageFinder
	 * @return array
	 */
	public function textAction($code, PageEntityFinder $pageFinder) {
		return [
			'textEntity' => $pageFinder->get($code),
		];
		
	}
	
}