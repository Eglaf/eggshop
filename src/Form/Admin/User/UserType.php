<?php

namespace App\Form\Admin\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\User\User;

/**
 * Class UserType
 */
class UserType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('username', Type\TextType::class, [
				'label' => 'name',
			])
			->add('email', Type\TextType::class, [
				'label' => 'email',
			])
			// ->add('plainPassword', Type\TextType::class, [
			// 	'required' => FALSE,
			// ])
			->add('active', Type\CheckboxType::class, [
				'label'    => 'label',
				'required' => FALSE,
			])
			->add('role', Type\ChoiceType::class, [
				'label'   => 'role',
				'choices' => [
					'Admin' => 'ROLE_ADMIN',
					'User'  => 'ROLE_USER',
				],
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
			'data_class'        => User::class,
			'validation_groups' => [''],
		]);
	}
	
}
