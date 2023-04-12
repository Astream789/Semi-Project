<?php

namespace App\Controller;

namespace App\Controller;
use App\Entity\Tag;
use App\Form\BookType;
use App\Form\TagType;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Config\Monolog\persistent;
use Symfony\Component\HttpFoundation\Request;
class TagController extends AbstractController
{
    #[Route('/book/tag', name: 'tag_list')]
    public function tagAction(ManagerRegistry $doctrine): Response
    {
        $books = $doctrine->getRepository('App\Entity\Book')->findAll();
        //$TgName = $books->getTag()->getTgName()->toArray();
        $tags = $doctrine->getRepository('App\Entity\Tag')->findAll();
        Return $this->render('book/tag.html.twig',['books' => $books, 'tags' => $tags]);
    }
    /**
     * @Route("product/delete/{id}", name="product_delete")
     */
    public function  deleteAction(ManagerRegistry $doctrine,$id)
    {
        $em = $doctrine ->getManager();
        $tag = $em ->getRepository('App\Entity\Tag')->find($id);
        $em ->remove($tag);
        $em->flush();

        $this->addFlash(
            'error',
            'tag deleted'
        );
        return $this->redirectToRoute('tag_list');
    }
    /**
     * @Route("/createtg", name ="create_tag", methods = {"GET","POST"})
     */
    public function createAction(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entitymanager = $doctrine->getManager();
            $entitymanager->persist($tag);
            $entitymanager->flush();

            $this->addFlash(
                'notice',
                'New tag Added'
            );
            return $this->redirectToRoute('create_tag');
        }
        return $this->render('book/createtg.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
