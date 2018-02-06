<?php

namespace App\Form\Site\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\User\User;

/**
 * Class UserUpdateType
 */
class EmailPasswordType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('email', Type\EmailType::class)
			->add('oldPassword', Type\PasswordType::class, [
				'mapped' => FALSE,
			])
			->add('newPassword', Type\RepeatedType::class, [
				'required'        => FALSE,
				'mapped'          => FALSE,
				'type'            => Type\PasswordType::class,
				'invalid_message' => 'The password fields must match.',
				'options'         => ['attr' => ['class' => 'password-field']],
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
		]);
	}
	
}