<?php

namespace espend\Inspector\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class HomeFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder->add('q', 'text', array(
            'required' => false,
            'constraints' => new NotBlank(),
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