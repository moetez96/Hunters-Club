<?php

namespace JournaleBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class JournaleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('annimal',EntityType::class,array(
            'class' => 'chasseBundle:annimal',
            'placeholder'=>'Selection un animal',
            'choice_label' => 'nomAnnimal',

        ))
            ->add('nbchasse',	NumberType::class,array('attr' => array('placeholder' =>
             'Saisir nombre de chasse',)))
            ->add('lieu',EntityType::class,array(
                'class' => 'chasseBundle:lieu',
                'placeholder'=>'Selection un lieu',
                'choice_label' => 'nom',

            ))

            ->add('date',DateType::class,array(

                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'calendar'
                )))->add('description', TextareaType::class)
            ->add('image',FileType::class,array('attr'=>array( 'class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')
                ))

            ->add('save',submitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JournaleBundle\Entity\Journale'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'journalebundle_journale';
    }


}
