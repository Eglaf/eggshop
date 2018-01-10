<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Egf\Ancient\AbstractController;

/**
 * Class RedirectController
 */
class RedirectController extends AbstractController {
	
	/**
	 * Cut down trailing slash from url and redirect to the correct route.
	 *
	 * @Route("/{url}", requirements={"url" = ".*\/$"}, methods={"GET"})
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function removeTrailingSlashAction(Request $request) {
		$pathInfo   = $request->getPathInfo();
		$requestUri = $request->getRequestUri();
		
		$url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);
		
		return $this->redirect($url, 301);
	}
	
}