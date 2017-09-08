<?php

namespace BlogBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class PostType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('title', TextType::class, [
				'label' => 'Post title:',
				'attr' => ['class' => 'form-control'],
				'required' => true,
			])
			->add('content', TextareaType::class, [
				'label' => 'Post content:',
				'attr' => ['class' => 'form-control'],
				'required' => true,
			])
			->add('category', EntityType::class, [
				'class' => 'BlogBundle:Category',
				'choice_label' => 'name',
				'label' => 'Post category:',
				'attr' => ['class' => 'form-control'],
			])
			->add('file', FileType::class, [
				'data_class' => null,
				'label' => 'Post category:',
				'attr' => ['class' => 'form-control-file'],
				'required' => false,
				'constraints' => [
					new File([
						'maxSize' => '2m',
						'maxSizeMessage' => 'RRRRRRRRRRRRRRRRRRRRRRRR',
					])
				]
			])
			->add('submit', SubmitType::class, [
				'label' => 'Save',
				'attr' => ['class' => 'btn btn-success'],
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'BlogBundle\Entity\Post'
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'blogbundle_post';
	}


}
