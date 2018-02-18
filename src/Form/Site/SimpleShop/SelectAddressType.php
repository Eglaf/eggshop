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
				'label'    => 'form.order_address.ask_for_delivery_address',
				'required' => FALSE,
				'mapped'   => FALSE,
			])
			->add('deliveryAddress', EntityType::class, [
				'label'        => 'form.order_address.select_delivery_address',
				'class'        => Address::class,
				'choice_label' => 'titleCityStreet',
				'mapped'       => FALSE,
			])
			->add('newDeliveryAddressCheckbox', Type\CheckboxType::class, [
				'label' => 'form.order_address.ask_for_new_delivery_address',
				'required' => FALSE,
				'mapped'   => FALSE,
			])
			->add('newDeliveryAddress', AddressType::class, [
				'label' => 'form.order_address.new_delivery_address',
			])
			->add('askingForBillingCheckbox', Type\CheckboxType::class, [
				'label'    => 'form.order_address.ask_for_billing_address',
				'required' => FALSE,
				'mapped'   => FALSE,
			])
			->add('billingAddress', EntityType::class, [
				'label'        => 'form.order_address.select_billing_address',
				'class'        => Address::class,
				'choice_label' => 'titleCityStreet',
				'mapped'       => FALSE,
			])
			->add('newBillingAddressCheckbox', Type\CheckboxType::class, [
				'label' => 'form.order_address.ask_for_new_billing_address',
				'required' => FALSE,
				'mapped'   => FALSE,
			])
			->add('newBillingAddress', AddressType::class, [
				'label' => 'form.order_address.new_billing_address',
			])
			->add('save', Type\SubmitType::class, [
				'label' => 'next',
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