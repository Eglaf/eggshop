<?php

namespace App\Form\Site\SimpleShop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Doctrine\ORM\EntityManager;

/**
 * Class NewOrderType
 */
class NewOrderType extends AbstractType {
	
	/** @var EntityManager $dm */
	protected $dm;
	
	// public function __construct(EntityManager $dm) {
	// 	$this->dm = $dm;
	// }
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		foreach ($options['productIds'] as $productId) {
			var_dump($productId);
		}
		
		$builder
			->add('save', Type\SubmitType::class, [
				'label' => 'Mentes',
			]);
	}
	
	/**
	 * Configure options.
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'productIds' => [],
		]);
	}
	
}