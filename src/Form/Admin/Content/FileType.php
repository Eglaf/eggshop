<?php

namespace App\Form\Admin\Content;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Egf;
use App\Entity\Content\File;

/**
 * Class FileType
 */
class FileType extends AbstractType {
	
	/**
	 * Build form.
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		// Upload only on create.
		if ( ! Egf\Util::isNaturalNumber($builder->getData()->getId())) {
			$builder->add('storageName', Type\FileType::class, [
				'label' => 'File',
			]);
		}
		
		// Other form inputs.
		$builder
			->add('label', Type\TextType::class, [
				'required' => FALSE,
			])
			->add('description', Type\TextareaType::class, [
				'required' => FALSE,
			])
			->add('active', Type\CheckboxType::class, [
				'required' => FALSE,
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
			'data_class' => File::class,
		]);
	}
	
}
