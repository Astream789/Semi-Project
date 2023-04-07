<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Config\Monolog\persistent;

class BookType extends AbstractType
{
    #[Route('/book/createbk', name: 'create_book')]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('BkName', TextType::class)
            //  ->remove('name')
            ->add('Detail' , TextareaType::class)
            ->add('Image' , FileType::class, [
                'label' => "Image file",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mineTypeMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('Date')
            ->add('tag', Entity::class,array('class'=>'App\Entity\Tag','choice_label'=>"TgName"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
