<?php

namespace App\Form\Admin\Content;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Content\Page;

/**
 * Class PageType
 */
class PageType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('code', Type\TextType::class, [
				'label' => 'identifier',
				'disabled' => TRUE,
			])
			->add('title', Type\TextType::class, [
				'label' => 'title',
				'required' => FALSE,
			])
			->add('description', Type\TextareaType::class, [
				'label' => 'title',
				'required' => FALSE,
			])
			->add('keywords', Type\TextType::class, [
				'label' => 'keywords',
				'required' => FALSE,
			])
			->add('text', Type\TextareaType::class, [
				'label' => 'text',
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
			'data_class' => Page::class,
		]);
	}
	
}