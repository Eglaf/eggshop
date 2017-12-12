<?php

namespace App\Form\Admin\SimpleShop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\SimpleShop\Category as CategoryEntity;

/**
 * Class CategoryType
 */
class CategoryType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('label')
			->add('active', Type\CheckboxType::class, [
				'required' => false,
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
			'data_class' => CategoryEntity::class,
		]);
	}
	
}