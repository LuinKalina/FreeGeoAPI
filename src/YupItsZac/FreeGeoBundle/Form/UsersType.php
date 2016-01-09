<?php
/**
 * Created by PhpStorm.
 * User: payleven
 * Date: 27.12.15
 * Time: 18:33
 */

namespace YupItsZac\FreeGeoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UsersType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Firstname', new TextType())
            ->add('Lastname', new TextType())
            ->add('email', new EmailType())
            ->add('plainPassword', new RepeatedType(), array(
                    'type' => new PasswordType(),
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YupItsZac\FreeGeoBundle\Entity\Users'
        ));
    }

    public function getName() {

    }
}