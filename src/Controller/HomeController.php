<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $em;
    private $bookRepository;
    public function __construct(EntityManagerInterface $em, BookRepository $bookRepository) {
        $this->bookRepository = $bookRepository;
    }


    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        $book = $this->bookRepository->findAll();


        $data = [
            'title' => 'Home Page',
            'book' => $book,
        ];
        return $this->render('home/index.html.twig', $data);
    }
}
