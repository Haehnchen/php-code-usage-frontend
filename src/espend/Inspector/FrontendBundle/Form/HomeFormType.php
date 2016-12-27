<?php

namespace espend\Inspector\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class HomeFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder->add('q', TextType::class, array(
            'required' => false,
            'constraints' => array(
              new NotBlank(),
              new Length(array('max' => 100, 'min' => 2))
            )
		));

	}

    public function setDefaultOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
          'csrf_protection' => false,
        ));
    }

	public function getName() {
		return null;
	}

}