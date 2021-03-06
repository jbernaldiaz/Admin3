<?php

namespace IPNJ\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\CallbackTransformer;
use IPNJ\AdminBundle\Entity\Iglesias;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


class EnviosEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 

        $this->userLogged = $options['userLogged'];
         $this->zone = $options['zone'];


    
        $builder
        ->add('fecha_at', DateType::class, [
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy', 
            'attr' => [
                'class' => 'form-control input-inline datepicker',
                'data-provide' => 'datepicker',
                'data-date-format' => 'dd-mm-yyyy'
    ]])
        ->add('mes', ChoiceType::class, array('choices' => array(
            'Enero'     => 'Enero' , 
            'Febrero'   => 'Febrero', 
            'Marzo'     => 'Marzo', 
            'Abril'     => 'Abril', 
            'Mayo'      => 'Mayo', 
            'Junio'     => 'Junio', 
            'Julio'     => 'Julio', 
            'Agosto'    => 'Agosto', 
            'Septiembre'=> 'Septiembre', 
            'Octubre'   => 'Octubre', 
            'Noviembre' => 'Noviembre', 
            'Diciembre' => 'Diciembre'
            )))
       // ->add('anio', ChoiceType::class, array('choices' => $this->getYears(2018)))
       ->add('anio', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy', 
            'attr' => [
                'class' => 'form-control input-inline datepicker',
                'data-provide' => 'datepicker',
                'data-date-format' => 'yyyy',
                
    ]])

        ->add('operacion', TextType::class)
        ->add('cajero', TextType::class)
        ->add('dDiezmo', IntegerType::class)
        ->add('fSolidario', IntegerType::class)
        ->add('cuota', IntegerType::class)
        ->add('dPersonal', IntegerType::class)
        ->add('misionera', IntegerType::class)
        ->add('rayos', IntegerType::class)
        ->add('gavillas', IntegerType::class)
        ->add('fmn', IntegerType::class)
        ->add('total', IntegerType::class)


        ->add('iglesia', EntityType::class, array(
          'class' => 'IPNJAdminBundle:Iglesias',
          'query_builder' => function ($er) {
              return $er->createQueryBuilder('u')
                ->where('u.id = :userLogged')
                ->setParameter('userLogged', $this->userLogged);
           }))

         ->add('zona', EntityType::class, array(
            'class' => 'IPNJAdminBundle:Zonas',
          'query_builder' => function ($er) {
              return $er->createQueryBuilder('u')
                ->where('u.id = :zone')
                ->setParameter('zone', $this->zone);
           }))

        ->add('save', SubmitType::class)   
               ;





    }
        
        private function getYears($min, $max='current')
    {
         $years = range($min, ($max === 'current' ? date('Y') : $max));

         return array_combine($years, $years);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'zone' => null,
            'userLogged' => null,
            'data_class' => 'IPNJ\AdminBundle\Entity\Envios'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'envios';
    }


}
