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
				'label' => 'site.form.profile.input.name',
			])
			->add('email', Type\EmailType::class, [
				'label' => 'site.form.profile.input.email',
			])
			->add('oldPassword', Type\PasswordType::class, [
				'label'  => 'site.form.profile.input.old_password',
				'mapped' => FALSE,
			])
			->add('newPassword', Type\RepeatedType::class, [
				'required'        => FALSE,
				'mapped'          => FALSE,
				'type'            => Type\PasswordType::class,
				'invalid_message' => 'The password fields must match.',
				'options'         => ['attr' => ['class' => 'password-field']],
				'first_options'   => ['label' => 'site.form.profile.input.new_password'],
				'second_options'  => ['label' => 'site.form.profile.input.new_password_again'],
			])
			->add('save', Type\SubmitType::class, [
				'label' => 'site.form.profile.input.submit',
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