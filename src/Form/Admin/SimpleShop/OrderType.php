<?php

namespace App\Form\Admin\SimpleShop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\SimpleShop\Order;
use App\Entity\SimpleShop\OrderStatus;
use App\Entity\User\Address;
use App\Repository\User\AddressRepository;

/**
 * Class OrderType
 */
class OrderType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$user = $builder->getData()->getUser();
		
		$builder
			->add('status', EntityType::class, [
				'label'        => 'status',
				'class'        => OrderStatus::class,
				'choice_label' => 'label',
			])
			->add('comment', Type\TextareaType::class, [
				'label'    => 'comment',
				'required' => FALSE,
			])
			->add('shippingAddress', EntityType::class, [
				'label'         => 'delivery_address',
				'required'      => FALSE,
				'class'         => Address::class,
				'choice_label'  => 'titleCityStreet',
				'query_builder' => function(AddressRepository $er) use ($user) {
					return $er->queryByUser($user);
				},
			])
			->add('billingAddress', EntityType::class, [
				'label'         => 'billing_address',
				'required'      => FALSE,
				'class'         => Address::class,
				'choice_label'  => 'titleCityStreet',
				'query_builder' => function(AddressRepository $er) use ($user) {
					return $er->queryByUser($user);
				},
			])
			->add('items', Type\CollectionType::class, [
				'label'        => ' ',
				'entry_type'   => OrderItemType::class,
				'allow_add'    => TRUE,
				'allow_delete' => TRUE,
				//'by_reference' => false, // TODO with addItem() Exception: Order has no addItem method... with setItems()... doesn't do shit...
			])
			->add('save', Type\SubmitType::class, [
				'label' => 'save',
			]);
	}
	
	/**
	 * Configure options.
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'data_class' => Order::class,
		]);
	}
	
}