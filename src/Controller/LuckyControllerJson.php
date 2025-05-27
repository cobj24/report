<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookRepository;

class LuckyControllerJson extends AbstractController
{
    #[Route('/api/lucky', name: 'api_lucky')]
    public function lucky(): JsonResponse
    {
        $number = random_int(1, 100);

        return new JsonResponse([
            'lucky_number' => $number,
            'date' => (new \DateTime())->format('Y-m-d'),
            'timestamp' => (new \DateTime())->format('H:i:s')
        ]);
    }

    #[Route('/api/game', name: 'api_game', methods: ['GET'])]
    public function game(): JsonResponse
    {
        return new JsonResponse(['status' => 'ok', 'message' => 'API game placeholder']);
    }

    #[Route('/api/quote', name: 'api_quote')]
    public function quote(): JsonResponse
    {
        $quotes = [
            "Code is poetry.",
            "Don't repeat yourself. Unless it’s coffee.",
            "Simplicity is the soul of efficiency.",
        ];

        $randomQuote = $quotes[array_rand($quotes)];
        $now = new \DateTime();

        return new JsonResponse([
            'quote' => $randomQuote,
            'date' => $now->format('Y-m-d'),
            'timestamp' => $now->format('H:i:s'),
        ]);
    }

    #[Route('/api', name: 'api_landing')]
    public function apiLanding(): Response
    {
        return $this->render('api.html.twig');
    }

    #[Route('/api/library/books', name: 'api_library_books', methods: ['GET'])]
    public function getAllBooks(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();

        $data = array_map(function ($book) {
            return [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'isbn' => $book->getIsbn(),
                'author' => $book->getAuthor(),
                'image' => $book->getImage(),
            ];
        }, $books);

        return $this->json($data);
    }

    #[Route('/api/library/book/{isbn}', name: 'api_library_book_by_isbn', methods: ['GET'])]
    public function getBookByIsbn(BookRepository $bookRepository, string $isbn): JsonResponse
    {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            return $this->json(['error' => 'Book not found'], 404);
        }

        return $this->json([
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'isbn' => $book->getIsbn(),
            'author' => $book->getAuthor(),
            'image' => $book->getImage(),
        ]);
    }
}
