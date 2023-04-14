<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Form\BookType;

class CategoryController extends AbstractController
{

    /**
     * @Route("/category/create", name="category_create")
     */
    public function createcatAction(ManagerRegistry$doctrine, Request $request, SluggerInterface $slugger)
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {


            $em = $doctrine->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash(
                'notice',
                'Book Added'

            );
            return $this->redirectToRoute('book_list');
        }
        return $this->renderForm('category/createCat.html.twig', ['form' => $form,]);


    }

    public function createChanges(ManagerRegistry $doctrine,$form, $request, $category)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCatname($request->request->get('category')['Cname']);
            $em = $doctrine->getManager();
            $em->persist($category);
            $em->flush();

            return true;

        }

        return false;
    }



    /**
     * @Route("/category/delete/{id}", name="book_deletecat")
     */
    public function deleteCatAction(ManagerRegistry $doctrine,$id)
    {
        $em = $doctrine->getManager();
        $book = $em->getRepository('App\Entity\Category')->find($id);
        $em->remove($book);
        $em->flush();

        $this->addFlash(
            'error',
            'Category deleted'
        );

        return $this->redirectToRoute('book_list');
    }

    /**
     * @Route("/category/editCat/{id}", name="book_editcat" )
     */
    public function editCatAction(ManagerRegistry $doctrine, $id, Request $request)
    {
        $todo= new Category();
        $em= $doctrine ->getManager();
        $todo = $em ->getRepository('App\Enity\Category')->find($id);
        $form = $this->createForm(CategoryType::class, $todo);

        $form ->handleRequest($request);


        if ($form->isSubmitted() ) {

            $entityManager = $doctrine->getManager();

            $entityManager->persist($todo);

            $entityManager->flush();
            return $this->redirectToRoute('book_list');

        }
        return $this->render('category/editCat.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
