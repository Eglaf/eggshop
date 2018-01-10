<?php

namespace App\Controller\Site\SimpleShop;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use App\Egf\Ancient\AbstractController;
use App\Repository\Content\TextRepository;
use App\Form\Site\SimpleShop\NewOrderType;
use App\Entity;

/**
 * Class NewOrderController
 */
class NewOrderController extends AbstractController {
	
	/**
	 * RouteName: app_site_simpleshop_neworder_selectproducts
	 * @Route("/online-rendeles/termekek", methods={"GET"})
	 * @Template
	 *
	 * @param TextRepository $textRepository
	 * @return array
	 */
	public function selectProductsAction(TextRepository $textRepository) {
		
		$form = $this->createForm(NewOrderType::class, NULL, [
			'action'     => $this->generateUrl('app_site_simpleshop_neworder_submitproducts'),
			'method'     => 'POST',
			'productIds' => [1, 2, 3],
		]);
		
		return [
			'formView'                 => $form->createView(),
			'beforeProductsTextEntity' => $textRepository->findOneBy(['code' => 'public_order_select_products_before']),
			'afterProductsTextEntity'  => $textRepository->findOneBy(['code' => 'public_order_select_products_after']),
		];
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_submitproducts
	 * @Route("/online-rendeles/temekek-rogiztes", methods={"POST"})
	 *
	 * @return RedirectResponse
	 */
	public function submitProductsAction() {
		die("TODO " . __LINE__);
		
		return $this->redirectToRoute('app_site_simpleshop_neworder_selectaddresses');
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_selectaddresses
	 * @Route("/online-rendeles/cimvalasztas", methods={"GET"})
	 * @Template
	 *
	 * @param TextRepository $textRepository
	 * @return array
	 */
	public function selectAddressesAction(TextRepository $textRepository) {
		die("TODO " . __LINE__);
		
		return [];
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_submitaddresses
	 * @Route("/online-rendeles/cimvalasztas-rogzites", methods={"POST"})
	 *
	 * @return RedirectResponse
	 */
	public function submitAddressesAction() {
		die("TODO " . __LINE__);
		
		return $this->redirectToRoute('');
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_confirmbeforeorder
	 * @Route("online-rendeles/megerosites", methods={"GET"})
	 * @Template
	 *
	 * @param TextRepository $textRepository
	 * @return array
	 */
	public function confirmBeforeOrderAction(TextRepository $textRepository) {
		die("TODO " . __LINE__);
		
		return [];
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_submitorder
	 * @Route("online-rendeles/mentes")
	 *
	 * @return RedirectResponse
	 */
	public function submitOrderAction() {
		die("TODO " . __LINE__);
		
		return $this->redirectToRoute('');
	}
	
	/**
	 * RouteName: app_site_simpleshop_neworder_orderconfirmed
	 * @Route("/online-rendeles/mentve", methods={"GET"})
	 * @Template
	 *
	 * @param TextRepository $textRepository
	 * @return array
	 */
	public function orderConfirmedAction(TextRepository $textRepository) {
		die("TODO " . __LINE__);
		
		return [];
	}
	
}