<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;

class BookController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/books', name: 'book_list')]
    public function index(Request $request, BookRepository $bookRepository): Response
    {
        $searchQuery = $request->query->get('search', '');

        $books = $bookRepository->search($searchQuery)->getQuery()->getResult();

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'search_query' => $searchQuery,
        ]);
    }


    #[Route('/books/new', name: 'book_new')]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverImage = $form->get('coverImage')->getData();
            if ($coverImage) {
                $originalFilename = pathinfo($coverImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$coverImage->guessExtension();

                try {
                    $coverImage->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload cover image.');
                }

                $book->setCoverImage($newFilename);
            }

            $this->entityManager->persist($book);
            $this->entityManager->flush();

            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/books/{id}/edit', name: 'book_edit')]
    public function edit(Request $request, Book $book, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverImage = $form->get('coverImage')->getData();
            if ($coverImage) {
                $originalFilename = pathinfo($coverImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$coverImage->guessExtension();

                try {
                    $coverImage->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload cover image.');
                }

                // Remove old file if exists
                $oldFilename = $book->getCoverImage();
                if ($oldFilename) {
                    $filesystem = new Filesystem();
                    $filesystem->remove($this->getParameter('kernel.project_dir').'/public/uploads/'.$oldFilename);
                }

                $book->setCoverImage($newFilename);
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/books/{id}/delete', name: 'book_delete')]
    public function delete(Book $book): Response
    {
        // Remove file if exists
        $filename = $book->getCoverImage();
        if ($filename) {
            $filesystem = new Filesystem();
            $filesystem->remove($this->getParameter('kernel.project_dir').'/public/uploads/'.$filename);
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return $this->redirectToRoute('book_list');
    }

    #[Route('/books/{id}', name: 'book_show')]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}
