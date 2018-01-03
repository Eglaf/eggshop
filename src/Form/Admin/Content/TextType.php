<?php

namespace App\Form\Admin\Content;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Content\Text;

/**
 * Class TextType
 */
class TextType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('code', Type\TextType::class, [
				'disabled' => true,
			])
			->add('text', Type\TextareaType::class)
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
			'data_class' => Text::class,
		]);
	}
	
}