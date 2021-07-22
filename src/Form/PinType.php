<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    

        $builder
            ->add('imageFile', VichImageType::class, [
                'label'=>'Image (JPG or PNG file)',
                'required' => false,
                'allow_delete' => true,
                'image_uri' => true,
                'download_label'=>false,
                'delete_label' => 'Remove Image',
                
                // 'asset_helper' => true,
            ])
            ->add('title')
            ->add('description')
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
