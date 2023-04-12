<?php

namespace App\Controller;
use App\Entity\Tag;
use App\Form\BookType;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Config\Monolog\persistent;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/book/delete/{id}", name="book_delete")
     */
    public function  deleteAction(ManagerRegistry $doctrine,$id)
    {
        $em = $doctrine ->getManager();
        $book = $em ->getRepository('App\Entity\Book')->find($id);
        $em ->remove($book);
        $em->flush();

        $this->addFlash(
            'error',
            'book deleted'
        );
        return $this->redirectToRoute('book_list');
    }
    /**
    *@Route("/book/details/{id}", name="book_details")
     */
    public function detailsAction(ManagerRegistry $doctrine, $id)
    {
        $books = $doctrine->getRepository('App\Entity\Book')->find($id);

        return $this->render('book/details.html.twig',['books'=> $books]);
    }
    /**
    *@Route("/createbk", name ="create_book", methods = {"GET","POST"})
     **/
    public function  createAction(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('Image')->getData();
            if ($form->isSubmitted() && $form->isValid()) {
                // upload file
                $Image = $form->get('Image')->getData();
                if ($Image) {
                    $originalFilename = pathinfo($Image->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $Image->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $Image->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash(
                            'error',
                            'Cannot upload'
                        );// ... handle exception if something happens during file upload
                    }
                    $book->setImage($newFilename);
                }else{
                    $this->addFlash(
                        'error',
                        'Cannot upload'
                    );// ... handle exception if something happens during file upload
                }

                $entitymanager = $doctrine->getManager();
                $entitymanager->persist($book);
                $entitymanager->flush();

                $this->addFlash(
                    'notice',
                    'New book Added'
                );
                return $this->redirectToRoute('book_list');
            }
        }
        return $this->render('book/createbk.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
