<?php

namespace App\Form\Site\SimpleShop;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\User\User;
use App\Entity\User\Address;
use App\Form\Site\User\AddressType;
use App\Repository\User\AddressRepository;

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
		if (! ($options['user'] instanceof User)) {
			throw new \Exception('Invalid user!');
		}
		
		$user = $options['user'];
		
		$builder
			->add('askingForDeliveryCheckbox', Type\CheckboxType::class, [
				'label'    => 'form.order_address.ask_for_delivery_address',
				'required' => FALSE,
				'mapped'   => FALSE,
			])
			->add('deliveryAddress', EntityType::class, [
				'label'         => 'form.order_address.select_delivery_address',
				'class'         => Address::class,
				'choice_label'  => 'titleCityStreet',
				'mapped'        => FALSE,
				'query_builder' => function(AddressRepository $er) use ($user) {
					return $er->queryByUser($user);
				},
			])
			->add('newDeliveryAddressCheckbox', Type\CheckboxType::class, [
				'label'    => 'form.order_address.ask_for_new_delivery_address',
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
				'label'         => 'form.order_address.select_billing_address',
				'class'         => Address::class,
				'choice_label'  => 'titleCityStreet',
				'mapped'        => FALSE,
				'query_builder' => function(AddressRepository $er) use ($user) {
					return $er->queryByUser($user);
				},
			])
			->add('newBillingAddressCheckbox', Type\CheckboxType::class, [
				'label'    => 'form.order_address.ask_for_new_billing_address',
				'required' => FALSE,
				'mapped'   => FALSE,
			])
			->add('newBillingAddress', AddressType::class, [
				'label' => 'form.order_address.new_billing_address',
			])
			->add('comment', Type\TextareaType::class, [
				'label'    => 'comment',
				'required' => FALSE,
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
			'user' => NULL,
		]);
	}
	
}