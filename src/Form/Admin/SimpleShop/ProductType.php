<?php

namespace App\Form\Admin\SimpleShop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\SimpleShop\Product;
use App\Entity\SimpleShop\Category;

/**
 * Class CategoryType
 */
class ProductType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('label', Type\TextType::class, [
				'label' => 'label',
			])
			->add('description', Type\TextareaType::class, [
				'label' => 'description',
				'required' => FALSE,
			])
			->add('active', Type\CheckboxType::class, [
				'label' => 'active',
				'required' => FALSE,
			])
			->add('category', EntityType::class, [
				'label' => 'category',
				'class'        => Category::class,
				'choice_label' => 'label',
			])
			->add('price', Type\NumberType::class, [
				'label' => 'price',
			])
			->add('sequence', Type\NumberType::class, [
				'label' => 'sequence',
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
			'data_class' => Product::class,
		]);
	}
	
}