<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private $em;
    private $bookRepository;
    public function __construct(EntityManagerInterface $em, BookRepository $bookRepository) {
        $this->em = $em;
        $this->bookRepository = $bookRepository;
    }

    //List Books
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        $book = $this->bookRepository->findAll();
        $data = [
            'title' => 'Book List',
            'book' => $book,
        ];
        return $this->render('book/list.html.twig', $data);
    }
    //Create new book
    #[Route('/create', name: 'create_book')]
    public function create(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newBook = $form->getData();
            $imagePath = $form->get('image')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    //code...
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    //throw $th;
                    return new Response($e->getMessage());
                }
                $newBook->setImage('/uploads/' . $newFileName);
            }
            $this->em->persist($newBook);
            $this->em->flush();

            $this->addFlash('success', 'Added successfully.');
            return $this->redirectToRoute('create_book');
        }
        $data = [
            'form' => $form->createView(),
            'title' => 'ADD NEW BOOK',
        ];
        return $this->render('book/form.html.twig', $data);
    }

    //Edit book
    #[Route('/edit-book-{id}', name: 'update_book')]
    public function update(Request $request, $id): Response
    {
        $book = $this->bookRepository->find($id);
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imagePath = $form->get('image')->getData();
            if ($imagePath) {
                $img = $book->getImage();
                if ($img !== null) {
                    // $file = file_exists($this->getParameter('kernel.project_dir') . $actor->getImage());
                    $file = $this->getParameter('kernel.project_dir') . '/public' . $book->getImage();
                    if ($file) {
                        $this->GetParameter('kernel.project_dir') . $book->getImage();
                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                        try {
                            //code...
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/uploads',
                                $newFileName
                            );
                        } catch (FileException $e) {
                            //throw $th;
                            return new Response($e->getMessage());
                        }

                        $book->setImage('/uploads/' . $newFileName);
                        $this->em->flush();

                        $this->addFlash('success', 'Update successfully.');
                        return $this->redirectToRoute('update_book', ['id' => $id]);
                    }
                }
            } else {
                $book->setName($form->get('name')->getData());
                $book->setDate($form->get('date')->getData());
                $book->setContent($form->get('content')->getData());
                $book->setImage($form->get('image')->getData());
                $book->setGenre($form->get('genre')->getData());
                $book->setAuthor($form->get('author')->getData());

                $this->em->flush();
                $this->addFlash('success', 'Update successfully.');
                return $this->redirectToRoute('update_book', ['id' => $id]);
            }
        }
        $data = [
            'title' => 'EDIT BOOK: ',
            'book' => $book,
            'form' => $form->createView(),
        ];
        return $this->render('book/form.html.twig', $data);
    }

    //Delete Book
    #[Route('/delete-book-{id}', name: 'remove_book')]
    public function destroy($id): Response
    {
        $book = $this->bookRepository->find($id);
        $this->em->remove($book);
        $this->em->flush();
        $this->addFlash('primary', 'Remove Successfully');
        return $this->redirectToRoute('app_book');
    }

    #[Route('/detail-{id}', name: 'detail_book')]
    public function detail($id): Response
    {
        $book = $this->bookRepository->find($id);
        $data = [
            'title' => 'Book Detail',
            'book' => $book,
        ];
        return $this->render('book/detail.html.twig', $data);
    }
}

