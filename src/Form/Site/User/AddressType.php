<?php

namespace App\Form\Site\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\User\Address;

/**
 * Class AddressType
 */
class AddressType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('title', Type\TextType::class, [
				'label'    => 'common.title',
				'required' => FALSE,
			])
			->add('city', Type\TextType::class, [
				'label' => 'common.city',
			])
			->add('zipCode', Type\TextType::class, [
				'label' => 'common.zip_code',
			])
			->add('street', Type\TextType::class, [
				'label' => 'common.street',
			])
			->add('houseNumber', Type\TextType::class, [
				'label' => 'common.house_number',
			])
			->add('floor', Type\TextType::class, [
				'label'    => 'common.floor',
				'required' => FALSE,
			])
			->add('door', Type\TextType::class, [
				'label'    => 'common.door',
				'required' => FALSE,
			])
			->add('doorBell', Type\TextType::class, [
				'label'    => 'common.door_bell',
				'required' => FALSE,
			]);
		
		if ($options['showSubmit'] === TRUE) {
			$builder->add('save', Type\SubmitType::class, [
				'label' => 'common.save',
			]);
		}
	}
	
	/**
	 * Configure options.
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			// 'data_class' => Address::class, // todo ?
			'showSubmit' => FALSE,
		]);
	}
	
}