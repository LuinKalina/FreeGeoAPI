<?php

namespace YupItsZac\FreeGeoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AppsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('apptitle')
            ->add('appdescription')
            ->add('appwebsite')
            ->add('publickey')
            ->add('secretkey')
            ->add('status')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YupItsZac\FreeGeoBundle\Entity\Apps'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yupitszac_freegeobundle_apps';
    }
}
