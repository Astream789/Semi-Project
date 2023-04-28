<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Genre;
use App\Form\BookFormType;
use App\Form\GenreFormType;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use ContainerNxSTHge\getGerneFormTypeService;
use Symfony\Component\Form\FormTypeInterface;

class GenreController extends AbstractController

{
    private $em;
    private $bookRepository;
    private $genreRepository;
    public function __construct(EntityManagerInterface $em, GenreRepository $genreRepository, BookRepository $bookRepository) {
        $this->em = $em;
        $this->genreRepository = $genreRepository;
        $this->bookRepository = $bookRepository;
    }

    //List Genre
    #[Route('/genre', name: 'app_genre')]
    public function index(): Response
    {
        $genre = $this->genreRepository->findAll();
//        $book   = $this->bookRepository->find($id);
        $data = [
            'title' => 'Genre List',
            'genre' => $genre,
//            'title' => 'Genre List',
//            'book' => $book,
        ];
        return $this->render('genre/list.html.twig', $data);
    }
    //Create new Gerne
    #[Route('/createG', name: 'create_genre')]
    public function create(Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreFormType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newGenre = $form->getData();
            $this->em->persist($newGenre);
            $this->em->flush();

            $this->addFlash('success', 'Added successfully.');
            return $this->redirectToRoute('create_genre');
        }

        $data = [
            'form' => $form->createView(),
            'title' => 'ADD NEW GENRE',
        ];
        return $this->render('genre/form.html.twig', $data);
    }



    //Edit Genre
    #[Route('/edit-genre-{id}', name: 'update_genre')]
    public function update(Request $request, $id): Response
    {
        $genre = $this->genreRepository->find($id);
        $form = $this->createForm(GenreFormType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $genre->setName($form->get('name')->getData());

            $this->em->flush();
            $this->addFlash('success', 'Update successfully.');
            return $this->redirectToRoute('update_genre', ['id' => $id]);
        }
        $data = [
            'title' => 'EDIT Genre: ',
            'genre' => $genre,
            'form' => $form->createView(),
        ];
        return $this->render('genre/form.html.twig', $data);
    }

    //Delete Genre
    #[Route('/delete-genre-{id}', name: 'remove_genre')]
    public function destroy($id): Response
    {
        $genre = $this->genreRepository->find($id);
        $book = $this->bookRepository->findAll($id);
        $this->em->remove($genre);
        $this->em->flush();
        $this->addFlash('primary', 'Remove Successfully');
        return $this->redirectToRoute('app_genre');
    }
    //Detail_Genre
    #[Route('/genre/detail/{id}', name: 'app_genre_detail')]
    public function genreDetail($id): Response
    {
        $genre = $this->genreRepository->find($id);
        return $this->render('genre/genreDetail.html.twig',[
            'genre' => $genre
        ]);
    }
}
