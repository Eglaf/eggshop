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
class UserUpdateType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('name', Type\TextType::class, [
				'label' => 'name',
			])
			->add('email', Type\EmailType::class, [
				'label' => 'email',
			])
			->add('oldPassword', Type\PasswordType::class, [
				'label'  => 'password_old',
				'mapped' => FALSE,
			])
			->add('plainPassword', Type\RepeatedType::class, [
				'required'        => FALSE,
				'type'            => Type\PasswordType::class,
				'invalid_message' => 'message.user.two_password_must_match',
				'options'         => ['attr' => ['class' => 'password-field']],
				'first_options'   => ['label' => 'password_new'],
				'second_options'  => ['label' => 'password_repeat'],
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
			'data_class' => User::class,
		]);
	}
	
}