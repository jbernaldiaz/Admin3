<?php

namespace IPNJ\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 

class IglesiasType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('iglesia', TextType::class)
        ->add('username')
        ->add('password', PasswordType::class)
        ->add('zona', EntityType::class, array(
            'required' => true, 
            'multiple' => false, 
            'class' => 'IPNJAdminBundle:Zonas', 
            'choice_label' => 'zona', 
            'placeholder' => 'Seleccione la zona'
            ))

        ->add('role',  ChoiceType::class, array('choices' => array(
            'Administrador' => 'ROLE_ADMIN', 
            'Supervisor' => 'ROLE_SUPER', 
            'Usuario' => 'ROLE_USER'), 'placeholder' => 'Select a role'))
        ->add('isActive', CheckBoxType::class)
        ->add('save', SubmitType::class)
             ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IPNJ\AdminBundle\Entity\Iglesias'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'iglesias';
    }


}
