<?php

namespace App\Form\Site\SimpleShop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\User\Address;
use App\Form\Site\User\AddressType;

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
			->add('askingForDeliveryCheckbox', Type\CheckboxType::class, [
				'required' => FALSE,
				'mapped'   => FALSE,
			])
			->add('deliveryAddress', EntityType::class, [
				'class'        => Address::class,
				'choice_label' => 'title',
				'mapped'       => FALSE,
			])
			->add('newDeliveryAddressCheckbox', Type\CheckboxType::class, [
				'label'    => 'uj delivery',
				'required' => FALSE,
				'mapped'   => FALSE,
			])
			->add('newDeliveryAddress', AddressType::class)
			
			->add('askingForBillingCheckbox', Type\CheckboxType::class, [
				'required' => FALSE,
				'mapped'   => FALSE,
			])
			->add('billingAddress', EntityType::class, [
				'class'        => Address::class,
				'choice_label' => 'title',
				'mapped'       => FALSE,
			])
			->add('newBillingAddressCheckbox', Type\CheckboxType::class, [
				'label'    => 'uj billing',
				'required' => FALSE,
				'mapped'   => FALSE,
			])
			->add('newBillingAddress', AddressType::class)
			
			->add('save', Type\SubmitType::class, [
				'label' => 'site.form.new_order.next',
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