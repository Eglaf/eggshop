<?php

namespace App\Form\Site\SimpleShop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity;

/**
 * Class NewOrderType
 */
class SelectProductsType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		/** @var Entity\SimpleShop\Product $product */
		foreach ($options['productEntities'] as $product) {
			$builder->add("product{$product->getId()}", Type\NumberType::class, [
				'label'    => $product->getLabel(),
				'required' => FALSE,
				'mapped'   => FALSE,
				'data'     => (isset($options['cart'][$product->getId()]) ? $options['cart'][$product->getId()] : NULL),
			]);
		}
		
		$builder->add('save', Type\SubmitType::class, [
			'label' => 'site.common.next',
		]);
	}
	
	/**
	 * Configure options.
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'productEntities' => [],
			'cart'            => [],
		]);
	}
	
}