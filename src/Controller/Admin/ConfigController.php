<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Controller\AbstractEggShopController;
use App\Entity\Config;
use App\Form\Admin\ConfigType as ConfigFormType;
use App\Service\Serializer;

/**
 * Class ConfigController
 */
class ConfigController extends AbstractEggShopController {
	
	/**
	 * List of Product Configs.
	 * @param Serializer         $serializer         Service to convert entities into json.
	 * @return array
	 *
	 * RouteName: app_admin_config_list
	 * @Route("/admin/config/list")
	 * @Template
	 */
	public function listAction(Serializer $serializer) {
		$configRows = $this->getDm()->getRepository(Config::class)->findAll();
		
		return [
			'listAsJson' => $serializer->toJson($configRows),
		];
	}
	
	/**
	 * Update a Product Config.
	 *
	 * RouteName: app_admin_config_update
	 * @Route("/admin/config/update/{config}", requirements={"config"="\d+|_id_"})
	 * @Template("admin/config/form.html.twig")
	 *
	 * @param Config $config
	 * @return array|RedirectResponse
	 */
	public function updateAction(Config $config) {
		return $this->form($config);
	}
	
	/**
	 * Generate form view to Product Config.
	 * @param Config $config
	 * @return array|RedirectResponse
	 */
	protected function form(Config $config) {
		// Create form.
		$form = $this->createForm(ConfigFormType::class, $config);
		$form->handleRequest($this->getRq());
		
		// Save form.
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDm()->persist($config);
			$this->getDm()->flush();
			
			return $this->redirectToRoute('app_admin_config_list');
		}
		
		// Form view.
		return [
			"config" => $config,
			"formView"       => $form->createView(),
		];
	}
	
}