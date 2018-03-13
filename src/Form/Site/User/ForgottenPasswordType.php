<?php

namespace App\Form\Site\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ForgottenPasswordType
 */
class ForgottenPasswordType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('email', Type\EmailType::class, [
				'label' => 'common.email',
			])
			->add('submit', Type\SubmitType::class, [
				'label' => 'form.forgotten_password.submit',
			]);
	}
	
	/**
	 * Configure options.
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
		]);
	}
	
}