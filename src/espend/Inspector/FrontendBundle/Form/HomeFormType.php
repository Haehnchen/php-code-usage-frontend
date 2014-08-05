<?php

namespace espend\Inspector\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class HomeFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder->add('q', 'text', array(
            'required' => false,
            'constraints' => array(
              new NotBlank(),
              new Length(array('max' => 100, 'min' => 2))
            )
		));

	}

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
          'csrf_protection' => false,
        ));
    }

	public function getName() {
		return null;
	}

}