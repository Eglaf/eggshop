<?php

namespace App\Form\Admin\SimpleShop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\SimpleShop\Category;

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
			->add('label', Type\TextType::class, [
				'label' => 'admin.label.common.label',
			])
			->add('active', Type\CheckboxType::class, [
				'label' => 'admin.label.common.active',
				'required' => false,
			])
			->add('save', Type\SubmitType::class, [
				'label' => 'admin.label.common.save',
			]);
	}
	
	/**
	 * Configure options.
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'data_class' => Category::class,
		]);
	}
	
}