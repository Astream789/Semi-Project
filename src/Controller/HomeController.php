<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Genre;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $em;
    private $bookRepository;
    private $authorRepository;
    private $genreRepository;
    public function __construct(EntityManagerInterface $em, BookRepository $bookRepository, AuthorRepository $authorRepository, GenreRepository $genreRepository) {
        $this->bookRepository = $bookRepository;
        $this->authorRepository= $authorRepository;
        $this->genreRepository= $genreRepository;
    }


    #[Route('/', name: 'app_home')]
    public function index(): Response
    {


        $books = $this->bookRepository->findAll();
        return $this->render('home/index.html.twig', [
            'books' => $books
        ]);
    }
}
