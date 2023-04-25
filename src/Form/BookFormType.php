<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use function Symfony\Component\DomCrawler\add;


class BookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('genre', EntityType::class, array('class' => 'App\Entity\Genre', 'choice_label' => 'name'))
            ->add('author', EntityType::class, array('class' => 'App\Entity\Author', 'choice_label' => 'name'))
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control',
//                    'type' => 'text',
                    'placeholder'=> 'Choose Date',
                    'id' => 'fecha1'
                ],
            ])
            ->add('content', TextareaType::class)
            ->add('image', FileType::class, array(
                'required' => false,
                'mapped' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
