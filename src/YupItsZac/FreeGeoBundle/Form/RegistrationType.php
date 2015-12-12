<?php

namespace YupItsZac\FreeGeoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', 'text', ['label'=>'First Name'])
            ->add('lastname', 'text', ['label' => 'Last Name'])
            ->add('email', 'email', ['label'=>'Email Address'])
            ->add('password', 'password',['label'=>'Password'])
            ->add('confirm', 'password', ['mapped' => false,'label'=>'Re-type password'])
            ->add('save', 'submit', ['label'=>'Register'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YupItsZac\FreeGeoBundle\Entity\Users'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yupitszac_freegeobundle_registration';
    }
}
