<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;

use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Form\BookType;

class BookController extends AbstractController
{
    /**
     * @Route("/Book", name="Book")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $category = new Category();
        $category->setCatName('Category Book');

        $book = new Book();
        $book->setBkName('Book');;
        $book->setDescription('Ergonomic and stylish!');


        // relates this Book to the category
        $book->setCategory($category);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($category);
        $entityManager->persist($book);
        $entityManager->flush();

        return new Response(
            'Saved new book with id: ' . $book->getId()
            . ' and new category with id: ' . $category->getId()
        );
    }
    #[Route('/booklist', name: 'book_list')]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $books = $doctrine->getRepository('App\Entity\Book')->findAll();
        // $categoryName = $books->getCategory()->getCatName()->toArray();
        $categories = $doctrine->getRepository('App\Entity\Category')->findAll();
        return $this->render('book/index.html.twig', [
            'books' => $books,
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/book/details/{id}", name="book_details")
     */
    public  function detailsAction(ManagerRegistry $doctrine, $id)
    {
        $books = $doctrine->getRepository('App\Entity\Book')->find($id);

        return $this->render('book/details.html.twig', ['books' => $books]);
    }



    /**
     * @Route("/category/delete/{id}", name="book_deletecat")
     */
    public function deletecatAction(ManagerRegistry $doctrine, $id)
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
     * @Route("/book/delete/{id}", name="book_delete")
     */
    public function deleteAction(ManagerRegistry $doctrine, $id)
    {
        $em = $doctrine->getManager();
        $book = $em->getRepository('App\EntiTy\Book')->find($id);
        $em->remove($book);
        $em->flush();

        $this->addFlash(
            'error',
            'Book deleted'
        );

        return $this->redirectToRoute('book_list');
    }

    /**
     * @Route("/todo/edit/{id}", name="todo_edit" )
     */
    public function editAction(ManagerRegistry $doctrine, $id, Request $request)
    {
        $todo = new Book();
        $em = $doctrine->getManager();
        $todo = $em->getRepository('App\Enity\Book')->find($id);
        $form = $this->createForm(BookType::class, $todo);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            $entityManager = $doctrine->getManager();

            $entityManager->persist($todo);

            $entityManager->flush();
            return $this->redirectToRoute('Book_list');
        }

        return $this->render('Book/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/book/createbk", name="book_create", methods={"GET","POST"})
     */
    public function createAction(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // uplpad file
            $bookImage = $form->get('Image')->getData();
            if ($bookImage) {
                $originalFilename = pathinfo($bookImage->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $bookImage->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $bookImage->move(
                        $this->getParameter('bookImages_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash(
                        'error',
                        'Cannot upload'
                    ); // ... handle exception if something happens during file upload
                }
                $book->setImage($newFilename);
            } else {
                $this->addFlash(
                    'error',
                    'Cannot upload'
                ); // ... handle exception if something happens during file upload
            }
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();

            $this->addFlash(
                'notice',
                'book Added'
            );
            return $this->redirectToRoute('book_list');
        }
        return $this->renderForm('book/createbk.html.twig', ['form' => $form,]);
    }

    public function saveChanges(ManagerRegistry $doctrine, $form, $request, $book)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setName($request->request->get('book')['bkname']);
            $book->setCategory($request->request->get('book')['category']);
            $book->setDetail($request->reques->get('book'['content']));
            $book->setDescription($request->request->get('book')['description']);
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();

            return true;
        }

        return false;
    }

    /**
     * @Route("/book/bookByCat/{id}", name="bookByCat")
     */
    public  function bookByCatAction(ManagerRegistry $doctrine, $id): Response
    {
        $category = $doctrine->getRepository(Category::class)->find($id);
        $books = $category->getBook();
        $categories = $doctrine->getRepository('App\Entity\Category')->findAll();
        return $this->render('book/index.html.twig', [
            'books' => $books,
            'categories' => $categories
        ]);
    }
}
