<?php

namespace App\Form\Site\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\User\User;

/**
 * Class AddressType
 */
class RegistrationType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('email', Type\EmailType::class)
			->add('username', Type\TextType::class)
			->add('plainPassword', Type\RepeatedType::class, [
				'type'            => Type\PasswordType::class,
				'invalid_message' => 'The password fields must match.',
				'options'         => ['attr' => ['class' => 'password-field']],
				'required'        => TRUE,
				'first_options'   => ['label' => 'Password'],
				'second_options'  => ['label' => 'Repeat Password'],
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
			'data_class' => User::class,
			'validation_groups' => ['registration'],
		]);
	}
	
}