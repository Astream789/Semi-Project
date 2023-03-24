<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class BookController extends AbstractController
{
    #[Route('/', name: 'book_list')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book', name: 'app_book')]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $books = $doctrine->getRepository('App\Entity\Book')->findAll();
        $tags = $doctrine->getRepository('App\Entity\Tag')->findAll();
        return $this->render('book/index.html.twig',['books' => $books, 'tags' => $tags]);
    }
    #[Route("book/details/{
    public function 
}
