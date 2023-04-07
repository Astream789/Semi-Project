<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Config\Monolog\persistent;
class TagController extends AbstractController
{
    #[Route('/book/tag', name: 'tag_list')]
    public function tagAction(ManagerRegistry $doctrine): Response
    {
        $tags = $doctrine->getRepository('App\Entity\Tag')->findAll();
        Return $this->render('book/tag.html.twig',['tags' => $tags]);
    }
    /**
     * @Route("product/delete/{id}", name="product_delete")
     */
    public function  deleteAction(ManagerRegistry $doctrine,$id)
    {
        $em = $doctrine ->getManager();
        $tag = $em ->getRepository('App\book\tag.html')->find($id);
        $em ->remove($tag);
        $em->flush();

        $this->addFlash(
            'error',
            'tag deleted'
        );
        return $this->redirectToRoute('tag_list');
    }
}
