<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookForm;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller to manage Book entities in the library.
 * Provides CRUD operations and a database reset route.
 */
#[Route('/library')]
final class BookController extends AbstractController
{
    /**
     * List all books.
     *
     * @param BookRepository $bookRepository repository for Book entities
     *
     * @return Response the response rendering the list of books
     */
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('library/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * Create a new book.
     *
     * @param Request                $request       the HTTP request
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response rendering the form or redirecting after success
     */
    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookForm::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('library/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    /**
     * Show details of a single book.
     *
     * @param Book $book the book entity to show
     *
     * @return Response the response rendering the book details
     */
    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('library/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * Edit an existing book.
     *
     * @param Request                $request       the HTTP request
     * @param Book                   $book          the book entity to edit
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response rendering the edit form or redirecting after success
     */
    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookForm::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('library/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    /**
     * Delete a book.
     *
     * @param Request                $request       the HTTP request
     * @param Book                   $book          the book entity to delete
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response redirects to the book index after deletion
     */
    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        // Fix: Request payload token access — use get() instead of getPayload()
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Reset the library database schema and load fixtures.
     *
     * @param KernelInterface $kernel the kernel interface for console commands
     *
     * @return Response redirects to the book index with a flash message
     */
    #[Route('/library/reset', name: 'app_library_reset')]
    public function reset(KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $commands = [
            ['command' => 'doctrine:schema:drop', '--force' => true],
            ['command' => 'doctrine:schema:create'],
            ['command' => 'doctrine:fixtures:load', '--no-interaction' => true],
        ];

        foreach ($commands as $inputArgs) {
            $input = new ArrayInput($inputArgs);
            $output = new BufferedOutput();
            $application->run($input, $output);
        }

        $this->addFlash('success', 'Databasen har återställts till ursprungligt innehåll.');

        return $this->redirectToRoute('app_book_index');
    }
}
