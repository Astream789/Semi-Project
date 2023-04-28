<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\AuthorFormType;
use App\Form\BookFormType;
use App\Repository\AuthorRepository;
use ContainerNxSTHge\getAuthorFormTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController

{
    private $em;
//    private $bookRepository;
    private $authorRepository;
    public function __construct(EntityManagerInterface $em, AuthorRepository $authorRepository) {
        $this->em = $em;
        $this->authorRepository = $authorRepository;
//        $this->bookRepository = $bookRepository;
    }

    //List Books
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        $author = $this->authorRepository->findAll();
//        $book   = $this->bookRepository->find($id);
        $data = [
            'title' => 'Author List',
            'author' => $author,
//            'title' => 'Author List',
//            'book' => $book,
        ];
        return $this->render('author/list.html.twig', $data);
    }
    //Create new Author
    #[Route('/createAu', name: 'create_author')]
    public function create(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorFormType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newAuthor = $form->getData();
            $this->em->persist($newAuthor);
            $this->em->flush();

            $this->addFlash('success', 'Added successfully.');
            return $this->redirectToRoute('create_author');
            }

        $data = [
            'form' => $form->createView(),
            'title' => 'ADD NEW AUTHOR',
        ];
        return $this->render('author/form.html.twig', $data);
    }



    //Edit author
    #[Route('/edit-author-{id}', name: 'update_author')]
    public function update(Request $request, $id): Response
    {
        $author = $this->authorRepository->find($id);
        $form = $this->createForm(AuthorFormType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author->setName($form->get('name')->getData());

                $this->em->flush();
                $this->addFlash('success', 'Update successfully.');
                return $this->redirectToRoute('update_author', ['id' => $id]);
            }
        $data = [
            'title' => 'EDIT AUTHOR: ',
            'author' => $author,
            'form' => $form->createView(),
        ];
        return $this->render('author/form.html.twig', $data);
    }

    //Delete Author
    #[Route('/delete-author-{id}', name: 'remove_author')]
    public function destroy($id): Response
    {
        $author = $this->authorRepository->find($id);
        $this->em->remove($author);
        $this->em->flush();
        $this->addFlash('primary', 'Remove Successfully');
        return $this->redirectToRoute('author');
    }
    //Detail_Author
    #[Route('/author/detail/{id}', name: 'app_author_detail')]
    public function genreDetail($id): Response
    {
        $author = $this->authorRepository->find($id);
        return $this->render('author/detail.html.twig',[
            'author' => $author
        ]);
    }
}
