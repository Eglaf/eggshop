<?php

namespace App\Form\Site\SimpleShop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\User\Address;

/**
 * Class SelectAddressType
 */
class SelectAddressType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('deliveryAddress', EntityType::class, [
				'class'        => Address::class,
				'choice_label' => 'title',
				'mapped'       => FALSE,
			])
			->add('billingAddress', EntityType::class, [
				'class'        => Address::class,
				'choice_label' => 'title',
				'mapped'       => FALSE,
			])
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
		]);
	}
	
}