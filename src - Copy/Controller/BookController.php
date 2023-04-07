<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Config\Monolog\persistent;

class BookController extends AbstractController
{
    #[Route('/book', name: 'book_list')]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $books = $doctrine->getRepository('App\Entity\Book')->findAll();
        //$TgName = $books->getTag()->getTgName()->toArray();
        $tags = $doctrine->getRepository('App\Entity\Tag')->findAll();
        Return $this->render('book/index.html.twig',['books' => $books, 'tags' => $tags]);
    }
    /**
     * @Route("product/delete/{id}", name="product_delete")
     */
    public function  deleteAction(ManagerRegistry $doctrine,$id)
    {
        $em = $doctrine ->getManager();
        $book = $em ->getRepository('App\book\index.html')->find($id);
        $em ->remove($book);
        $em->flush();

        $this->addFlash(
            'error',
            'book deleted'
        );
        return $this->redirectToRoute('book_list');
    }
}
