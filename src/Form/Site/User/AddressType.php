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
				'label' => 'site.content.profile.address.title',
				'required'   => FALSE,
			])
			->add('city', Type\TextType::class, [
				'label' => 'site.content.profile.address.city',
			])
			->add('zipCode', Type\TextType::class, [
				'label' => 'site.content.profile.address.zip_code',
			])
			->add('street', Type\TextType::class, [
				'label' => 'site.content.profile.address.street',
			])
			->add('houseNumber', Type\TextType::class, [
				'label' => 'site.content.profile.address.house_number',
			])
			->add('floor', Type\TextType::class, [
				'label' => 'site.content.profile.address.floor',
				'required' => FALSE,
			])
			->add('door', Type\TextType::class, [
				'label' => 'site.content.profile.address.door',
				'required' => FALSE,
			])
			->add('doorBell', Type\TextType::class, [
				'label' => 'site.content.profile.address.door_bell',
				'required' => FALSE,
			]);
		
		if ($options['showSubmit'] === TRUE) {
			$builder->add('save', Type\SubmitType::class, [
				'label' => 'site.common.save',
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