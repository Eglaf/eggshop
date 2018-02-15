<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Config;

/**
 * Class ConfigType
 */
class ConfigType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('code', Type\TextType::class, [
				'label' => 'admin.label.common.identifier',
				'disabled' => TRUE,
			])
			->add('description', Type\TextareaType::class, [
				'label' => 'admin.label.common.description',
				'disabled' => TRUE,
			])
			->add('value', Type\TextType::class, [
				'label' => 'admin.label.config.value',
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
			'data_class' => Config::class,
		]);
	}
	
}