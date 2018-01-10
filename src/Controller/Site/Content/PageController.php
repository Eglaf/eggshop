<?php

namespace App\Controller\Site\Content;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Ancient\AbstractController;
use App\Entity;
use App\Repository\Content\TextRepository;

/**
 * Class PageController
 */
class PageController extends AbstractController {
	
	/**
	 * Index page... it uses the TextPage template.
	 *
	 * RouteName: app_site_content_page_index
	 * @Route("/")
	 * @Template("site/content/page/text.html.twig")
	 *
	 * @param TextRepository $textRepository
	 * @return array
	 */
	public function indexAction(TextRepository $textRepository) {
		return [
			'textEntity' => $textRepository->findOneBy(['code' => 'index']),
		];
	}
	
	/**
	 * Text page.
	 *
	 * RouteName: app_site_content_page_text
	 * @Route("/{code}")
	 * @Template
	 *
	 * @param string $code
	 * @param TextRepository $textRepository
	 * @return array
	 */
	public function textAction($code, TextRepository $textRepository) {
		return [
			'textEntity' => $textRepository->findOneBy(['code' => $code]),
		];
		
	}
	
}