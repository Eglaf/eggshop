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
			$builder->add('file', Type\FileType::class, [
				'label' => 'admin.label.file.file',
			]);
		}
		
		// Other form inputs.
		$builder
			->add('label', Type\TextType::class, [
				'label' => 'admin.label.common.label',
				'required' => FALSE,
			])
			->add('description', Type\TextareaType::class, [
				'label' => 'admin.label.common.description',
				'required' => FALSE,
			])
			->add('active', Type\CheckboxType::class, [
				'label' => 'admin.label.common.active',
				'required' => FALSE,
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
			'data_class' => File::class,
		]);
	}
	
}
