<?php

namespace App\Form\Site\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

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
			->add('name', Type\TextType::class, [
				'label' => 'name',
			])
			->add('email', Type\EmailType::class, [
				'label' => 'email',
			])
			->add('phone', Type\TextType::class, [
				'label'    => 'phone',
				'required' => FALSE,
			])
			->add('plainPassword', Type\RepeatedType::class, [
				'type'            => Type\PasswordType::class,
				'invalid_message' => 'two_password_must_match',
				'options'         => ['attr' => ['class' => 'password-field']],
				'required'        => TRUE,
				'first_options'   => ['label' => 'password'],
				'second_options'  => ['label' => 'password_repeat'],
			])
			->add('termsAccepted', Type\CheckboxType::class, [
				'label'       => 'form.user.accept_terms',
				'mapped'      => FALSE,
				'constraints' => new IsTrue(),
			])
			->add('save', Type\SubmitType::class, [
				'label' => 'registration',
			]);
	}
	
	/**
	 * Configure options.
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'data_class'        => User::class,
			'validation_groups' => ['registration'],
		]);
	}
	
}